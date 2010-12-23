/**
 * browser.js - emulate chrome.extension.* and chrome.tabs.* for firefox
 * Copyright (c) 2010 Ookong Ltd. Co. (ookong.com).
 *
 * interface browser = {
 *     name: String,
 *     tabs: {
 *         function executeScript(tab, details[, callback]);
 *         function create(options[, callback]);
 *         function getCurrent(callback)
 *         function sendRequest(tab, request, responseCallback);
 *     },
 *     extension: {
 *         baseURI: String,
 *         function getURL(path);
 *         onRequest: {
 *             function addListener(function listener(request, sender, sendResponse) {}),
 *             function removeListener(listener);
 *         }
 *     }
 * }
 */
if ( !exports ) var exports = {};
(function(S, undefined) {
    // @import util
    // @import console

    var getFileContent = function( file ) {
        var ioService=Components.classes["@mozilla.org/network/io-service;1"]
            .getService(Components.interfaces.nsIIOService);
        var scriptableStream=Components
            .classes["@mozilla.org/scriptableinputstream;1"]
            .getService(Components.interfaces.nsIScriptableInputStream);
        var unicodeConverter=Components
            .classes["@mozilla.org/intl/scriptableunicodeconverter"]
            .createInstance(Components.interfaces.nsIScriptableUnicodeConverter);
        unicodeConverter.charset="UTF-8";

        var channel=ioService.newChannel( file, "UTF-8", null);
        var input=channel.open();
        scriptableStream.init(input);
        var str=scriptableStream.read(input.available());
        scriptableStream.close();
        input.close();

        try {
            return unicodeConverter.ConvertToUnicode(str);
        } catch (e) {
            return str;
        } 
    };

    var self = { name: "firefox" };
    self.tabs = {};

    /**
     * get tab object
     * @param Document doc
     */
    self.tabs.getTab = function(doc, tab) {
        return {
            document: doc,
            url: (typeof doc.location != "undefined" ? doc.location.href : undefined),
            tab: tab
        }
    };

    self.tabs.create = function(options, callback) {
        var wm = Components.classes["@mozilla.org/appshell/window-mediator;1"]  
            .getService(Components.interfaces.nsIWindowMediator);  
        var gBrowser = wm.getMostRecentWindow("navigator:browser").gBrowser;
        var tab = gBrowser.addTab(options.url);
        var thetab = {"tab": tab};
        if (options.selected) {
            gBrowser.selectedTab = tab;
        }
        var newTabBrowser = gBrowser.getBrowserForTab(tab);
        if ( callback ) {
            var listener = function() {
                newTabBrowser.removeEventListener("load", listener, true);
                callback(self.tabs.getTab(newTabBrowser.contentDocument, tab));
            }
            newTabBrowser.addEventListener("load", listener, true);
        }
    };

    self.tabs.isIframe = function(doc) {
        var win = doc.defaultView;
        return win.top !== win;
    };

    /**
     * execute content script on select tab
     * @param object tab
     * @param object details
     *      - code: string
     *      - file: string for file name or array of file name
     *      - js_version: js version, see https://developer.mozilla.org/en/Components.utils.evalInSandbox#Optional_Arguments
     *      - filename: filename for display error
     *      - line: line
     *      - all_frames: whether the content script runs in all frames
     */
    self.tabs.executeScript = function(tab, details, callback) {
        details = S.extend({ js_version: 1.8, line: 1, all_frames: false }, details);
        if ( !details.all_frames && self.tabs.isIframe(tab.document) ) {
            return;
        }
        var unsafeWin = tab.document.defaultView;
        var safeWin = new XPCNativeWrapper(unsafeWin);
        var sandbox = new Components.utils.Sandbox(safeWin);
        sandbox.window = safeWin;
        sandbox.document = sandbox.window.document;
        sandbox.unsafeWindow = unsafeWin;
        sandbox.__proto__ = sandbox.window;
        sandbox.exports = {
            browser: {
                extension: new self.tabs.extension(tab.document)
            },
            console: S.console
        };
        var code = '';
        if ( typeof details.code != "undefined" ) {
            code = details.code;
        } else if ( typeof details.file != "undefined" ) {
            if ( S.is_array(details.file) ) {
                for ( var i=0; i<details.file.length; i++ ) {
                    code += getFileContent(details.file[i]) + "\n";
                }
            } else {
                code = getFileContent(details.file);
            }
        }
        if ( code ) {
            S.console.debug('execute: ' + JSON.stringify(details));
            Components.utils.evalInSandbox(code, sandbox, details.js_version, details.filename, details.line);
        }
        if ( callback ) callback(tab);
    };

    self.tabs.event_id = S.get_random_string()+".tabs.request";
    self.tabs.request = {};

    self.tabs.getCurrent = function(callback) {
        var wm = Components.classes["@mozilla.org/appshell/window-mediator;1"]  
            .getService(Components.interfaces.nsIWindowMediator);  
        var gBrowser = wm.getMostRecentWindow("navigator:browser").gBrowser;
        callback(self.tabs.getTab(gBrowser.contentDocument, gBrowser.selectedTab));
    };

    self.tabs.sendRequest = function(tab, request, requestCallback) {
        if ( tab.document ) {
            var evt = tab.document.createEvent("Events");
            evt.initEvent(self.tabs.event_id, true, false);
            self.tabs.request = {
                data: request,
                callback: requestCallback
            };
            tab.document.dispatchEvent(evt);
        } else {
            S.console.error("cannot not send request without document");
        }
    };

    self.tabs.extension = function(doc) {
        this.document = doc;
        this.event_id = self.tabs.event_id;

        // listeners for request send by self.tabs.sendRequest
        var listeners = [];
        var handler = function(evt) {
            var request = self.tabs.request;
            for ( var i=0; i<listeners.length; i++ ) {
                listeners[i](request.data, null, request.callback);
            }
        };

        this.onRequest = {
            addListener: function(listener) {
                listeners.push(listener);
            },
            
            removeListener: function(listener) {
                for ( var i=0; i<listeners.length; i++ ) {
                    if ( listener == listeners[i] ) {
                        listeners.splice(i, 1);
                        i--;
                    }
                }
            }
        };
        doc.addEventListener(self.tabs.event_id, handler, false, true );
    };

    // listeners for request send by content script using self.extension.sendRequest
    self.tabs.listeners = [];
    self.tabs.extension.prototype.sendRequest = function(request, responseCallback) {
        if ( typeof responseCallback!="function" ) {
            responseCallback = function() {};
        }
        var listeners = self.tabs.listeners;
        for ( var i=0; i<listeners.length; i++ ) {
            listeners[i](request, { tab: self.tabs.getTab(this.document) }, responseCallback);
        }
    };
    self.tabs.extension.prototype.getURL = function(path) {
        return self.extension.getURL(path);
    };

    self.extension = {};
    self.extension.onRequest = {
        addListener: function(listener) {
            self.tabs.listeners.push(listener);
        },
        removeListener: function(listener) {
            var listeners = self.tabs.listeners;
            for ( var i=listeners.length; i--; ) {
                if ( listener == listeners[i] ) {
                    listeners.splice(i, 1);
                }
            }
        }
    };
    self.extension.getURL = function(path) {
        return self.extension.baseURI + path;
    };

    S.browser = self;
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
