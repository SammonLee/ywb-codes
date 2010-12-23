if ( !exports ) var exports = {};
(function(S, undefined) {
    var callback_id = 1;
    var generate_callback_id = function() {
        return String(callback_id++);
    };
    var response_callbacks = {};
    var request_listeners = [];

    var response_handler = function (event) {
        var message = event.message;
        if ( typeof message.response_callback != "undefined" ) {
            var callback = message.response_callback;
            if ( callback && (callback in response_callbacks) ) {
                response_callbacks[callback](message.response);
                delete response_callbacks[callback];
            }
        }
    };

    var message_handler = function(event) {
        var message = event.message;
        if ( typeof message.request != "undefined" ) {
            if ( request_listeners.length == 0 ) {
                return;
            }
            // send from background
            var request = message.request;
            var sendResponse = function(response) {
                safari.self.tab.dispatchMessage(
                    request.topic,
                    {
                        response_topic: request.topic,
                        response_callback: message.callback,
                        response: response
                    }
                );
            };
            for ( var i=0; i<request_listeners.length; i++ ) {
                request_listeners[i](request, null, sendResponse);
            }
        } else {
            response_handler(event);
        }
    };
    if ( typeof safari != "undefined" ) {
        safari.self.addEventListener("message", message_handler, false);
    }

    if ( !S.browser ) S.browser = {};
    S.browser.extension = {
        baseURI: safari.extension.baseURI,
        
        sendRequest : function ( request, responseCallback ) {
            var message = { request: request };
            if ( typeof responseCallback == "function" ) {
                var callback = generate_callback_id();
                response_callbacks[callback] = responseCallback;
                message.callback = callback;
            }
            safari.self.tab.dispatchMessage(request.topic, message);
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
        },

        getURL: function( url){
            return this.baseURI + url;
        }
    }
})(exports);

