if ( !exports ) var exports = {};

(function(S, undefined) {
    if ( !S.browser ) S.browser = {};
    S.browser.extension = {
        sendRequest: function(request, responseCallback) {
            if ( typeof responseCallback == "undefined" ) {
                responseCallback = function () {};
            }
            chrome.extension.sendRequest(request, responseCallback);
        },

        onRequest: {
            addListener: function(listener) {
                chrome.extension.onRequest.addListener(listener);
            },

            removeListener: function(listener) {
                chrome.extension.onRequest.removeListener(listener);
            }
        },

        getURL: function(path) {
            return chrome.extension.getURL(path||'');
        }
    }
})(exports);
