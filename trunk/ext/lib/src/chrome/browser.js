if ( !exports ) var exports = {};

(function(S, undefined) {
    S.browser = {
        name: "chrome",
        extension: chrome.extension,
        tabs: {
            create: function(properties, callback) {
                chrome.tabs.create(properties, callback);
            },

            getCurrent: function(callback) {
                chrome.windows.getCurrent(function (win) {
                    chrome.tabs.getSelected(win.id, callback);
                });
            },

            sendRequest: function(tab, request, responseCallback) {
                chrome.tabs.sendRequest(tab.id, request, responseCallback);
            },

            executeScript: function(tab, details, callback) {
                chrome.tabs.executeScript(tab.id, details, callback);
            }
        }
    }
})(exports);
