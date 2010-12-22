if ( !exports ) var exports = {};

(function(S, undefined) {
var instances = {};

S.factory = {
    getInstance: function (key, creator) {
        if ( !instances.hasOwnProperty(key) ) {
            instances[key] = creator();
        }
        return instances[key];
    },

    /**
     * @param options
     *   - browser name of browser (chrome, firefox, etc.)
     *   - storage storage type, eg: exports.SqliteStorage
     */
    init: function(options) {
        var self = this;
        this.options = options || {};
        instances = {};
        if ( options.browser == "chrome" ) {
            self.getOAuth = function () {
                return self.getInstance("oauth", function() {
                    return ChromeExOAuth.initBackgroundPage(options.consumer);
                });
            };
        } else {
            self.getOAuth = function () {
                return self.getInstance("oauth", function() {
                    // @import oauth
                    return new S.oauth(options.consumer, self.getStorage("oauth"));
                });
            };
        }
    },

    getStorage: function (namespace) {
        // @import storage
        return new S.storage(namespace, this.options.storage);
    },

    getCache: function () {
        var self = this;
        return this.getInstance("cache", function () {
            // @import cache
            return new S.cache({
                max_size: self.options.cache_size,
                expire_time: self.options.cache_expire_time,
                storage: self.getStorage("cache")
            });
        });
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
