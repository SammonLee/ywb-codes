(function(S, undefined) {
    module("firefoxBrowser");
    // @import browser
    S.browser.extension.baseURI = 'chrome://extlib_test/content/';

    test("name", function() {
        equal(S.browser.name, "firefox", "name = firefox");
    });
 
    test("event-id", function() {
        ok(S.browser.tabs.event_id.match(/\w+.tabs.request/), "event_id");
    });

    test("extension.getURL", function() {
        equal(S.browser.extension.getURL('foo'), "chrome://extlib_test/content/foo", "getURL");
    });

    test("tabs.getCurrent", function() {
        S.browser.tabs.getCurrent(function(tab) {
            ok(tab.url.match(/^chrome:\/\/\w+\/content\/tests\/runTests.html/, "tab url"));
            ok(tab.document instanceof Document, "tab document");
            ok(typeof tab.tab == "object", "tab");
        });
    });

    test("tabs.executeScript", 2, function() {
        var i = 0;
        var listener = function(request, sender, sendResponse) {
            i++;
            if ( i == 1 ) {
                ok(request.match(/^chrome:\/\//), 'get request from content script');
                sendResponse('hello');
            } else {
                equal(request, 'hello', 'response');
            }
        };
        S.browser.extension.onRequest.addListener(listener);
        S.browser.tabs.getCurrent(function(tab) {
            S.browser.tabs.executeScript(tab, {
                code: 'exports.browser.extension.sendRequest('
                    + '  document.location.href, function(response) {'
                    + '    exports.browser.extension.sendRequest(response);'
                    + ' });'
            });
        });
        S.browser.extension.onRequest.removeListener(listener);
    });

    asyncTest("tabs.create", 5, function() {
        var i = 0;
        var listener = function(request, sender, sendResponse) {
            i++;
            if ( i == 1 ) {
                equal(request, 'http://www.baidu.com/', 'get request from content script');
                sendResponse('hello');
            } else {
                equal(request, 'hello', 'response');
            }
        };
        S.browser.extension.onRequest.addListener(listener);
        S.browser.tabs.create({
            url: "http://www.baidu.com/"
        }, function(tab) {
            equal(tab.url, "http://www.baidu.com/", "tab url");
            ok(tab.document instanceof Document, "tab document");
            ok(typeof tab.tab == "object", "tab");
            S.browser.tabs.executeScript(tab, {
                code: 'exports.browser.extension.sendRequest('
                    + '  document.location.href, function(response) {'
                    + '    exports.browser.extension.sendRequest(response);'
                    + ' });'
            });
            S.browser.extension.onRequest.removeListener(listener);
            start();
        });
    });

    test("extension", 1, function() {
        var listener = function(request, sender, sendResponse) {
            equal(request, 'test');
        };
        S.browser.extension.onRequest.addListener(listener);
        var ext = new S.browser.tabs.extension(document);
        ext.sendRequest('test');
        S.browser.extension.onRequest.removeListener(listener);
    });
})(exports);
