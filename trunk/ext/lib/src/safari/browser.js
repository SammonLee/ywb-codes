(function(S, undefined) {
    var callback_id = 1;
    var generate_callback_id = function() {
        return String(callback_id++);
    };
    var response_callbacks = {};
    var request_listeners = [];
    var response_handler = function(event) {
        var message = event.message;
        if ( typeof message.response_callback != "undefined" ) {
            var callback = message.response_callback;
            if ( callback && (callback in response_callbacks) ) {
                response_callbacks[callback](message.response);
                delete response_callbacks[callback];
            }
        }
    };

    var message_handler = function (event) {
        var message = event.message;
        if ( typeof message.request != "undefined" ) {
            if ( request_listeners.length == 0 ) {
                return;
            }
            // send from scripts directly
            var sendResponse;
            if ( message.callback ) {
                sendResponse = function (data) {
                    event.target.page.dispatchMessage(
                        event.name,
                        {
                            response_callback: message.callback,
                            response: data,
                            response_topic: message.request
                        }
                    );
                };
            } else {
                sendResponse = function () {};
            }
            for ( var i=0; i<request_listeners.length; i++ ) {
                request_listeners[i](message.request, { tab: event.target }, sendResponse);
            }
        } else {
            // reply from scripts
            response_handler(event);
        }
    };
    if ( typeof safari != "undefined" ) {
        safari.application.addEventListener("message", message_handler, false);
    }

    S.browser = {
        name: "safari",
        extension: {
            baseURI: safari.extension.baseURI,

            getURL: function(path) {
                return this.baseURI+path;
            },

            onRequest: {
                addListener: function(listener) {
                    request_listeners.push(listener);
                },

                removeListener: function(listener) {
                    for ( var i=0; i<request_listeners.length; i++ ) {
                        if ( listener == request_listeners[i] ) {
                            request_listeners.splice(i, 1);
                            i--;
                        }
                    }
                }
            }
        },

        tabs: {
            create: function(properties, callback) {
                if ( typeof properties.selected == "undefined" ) properties.selected = true;
                var tab = safari.application.activeBrowserWindow.openTab();
                tab.url = properties.url;
                if ( callback ) {
                    callback(tab);
                }
            },

            getCurrent: function(callback) {
                callback(safari.application.activeBrowserWindow.activeTab);
            },

            sendRequest: function(tab, request, responseCallback) {
                var message = { request: request };
                if ( typeof responseCallback == "function" ) {
                    var callback = generate_callback_id();
                    message.callback = callback;
                    response_callbacks[callback] = responseCallback;
                }
                tab.page.dispatchMessage( request.topic, message );
            }
        },
    }
})(exports);
