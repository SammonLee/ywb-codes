if ( !exports ) var exports = {};
(function(S, undefined) {
    var callback_id = 1;
    var generate_callback_id = function() {
        return String(callback_id++);
    };
    var response_callbacks = {};
    var request_listeners = [];
    var response_handler = function(event) {
        var message = event.data;
        if ( typeof message.response_callback != "undefined" ) {
            var callback = message.response_callback;
            if ( callback && (callback in response_callbacks) ) {
                response_callbacks[callback](message.response);
                delete response_callbacks[callback];
            }
        }
    };

    var message_handler = function (event) {
        var message = event.data;
        if ( typeof message.request != "undefined" ) {
            if ( request_listeners.length == 0 ) {
                return;
            }
            // send from scripts directly
            var sendResponse;
            if ( message.callback ) {
                sendResponse = function (data) {
                    try {
                        event.source.postMessage(
                            {
                                response_callback: message.callback,
                                response: data,
                                response_topic: message.request
                            }
                        );
                    } catch (e) {
                        S.console.debug("send response failed: " + e);
                    }
                };
            } else {
                sendResponse = function () {};
            }
            for ( var i=0; i<request_listeners.length; i++ ) {
                request_listeners[i](message.request, { tab: { page: event.source, url: event.origin } }, sendResponse);
            }
        } else {
            // reply from scripts
            response_handler(event);
        }
    };
    if ( typeof opera != "undefined" ) {
        opera.extension.addEventListener("message", message_handler, false);
    }

    S.browser = {
        name: "opera",
        extension: {
            baseURI: '',

            getURL: function(path) {
                return this.baseURI+path;
            },

            onRequest: {
                addListener: function(listener) {
                    request_listeners.push(listener);
                },

                removeListener: function(listener) {
                    for ( var i=request_listeners.length; i--; ) {
                        if ( listener == request_listeners[i] ) {
                            request_listeners.splice(i, 1);
                        }
                    }
                }
            }
        },

        tabs: {
            create: function(properties, callback) {
                if ( typeof properties.selected == "undefined" ) properties.selected = true;
                opera.extension.tabs.create({url: properties.url, focused: properties.selected});
                if ( callback ) {
                    this.getCurrent(callback);
                }
            },

            getCurrent: function(callback) {
                var tab = opera.extension.tabs.getFocused();
                if (tab) callback(tab);
            },

            sendRequest: function(tab, request, responseCallback) {
                var message = { request: request };
                if ( typeof responseCallback == "function" ) {
                    var callback = generate_callback_id();
                    message.callback = callback;
                    response_callbacks[callback] = responseCallback;
                }
                tab.postMessage(message);
            }
        }
    }
})(exports);
