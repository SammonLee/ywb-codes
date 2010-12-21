/**
 * storage.js - Stores both object and string with namespace
 * Copyright (c) 2010 Ookong Ltd. Co. (ookong.com).
 *
 * SYNOPSIS
 *  var storage = new S.storage("test");
 *  storage.set("key", "value");
 *  storage.get("key");                  // "value"
 *  storage.has("key");                  // true
 *  storage.remove("key");
 *  storage.set_object("key", [1, 2]);
 *  storage.get_object("key");           // [1, 2]
 *
 * DESCRIPTION
 * S.storage VS localStorage:
 *  1. S.storage has an abstract level for low level storage.
 *     Default it use localStorage, and it should easily switch to other
 *     storage those implement S.localStorage interface.
 *  2. S.storage has namespace.
 *  3. S.storage can store object directly.
 */

if ( !exports ) var exports = {};

(function(S, undefined) {
    // @import console
    var console = S.console || { debug: function () {} };
/**
 * interface localStorage {
 *    function getItem(String key);
 *    function setItem(String key, String val);
 *    function hasItem(String key);
 *    function removeItem(String key);
 *    function each(function ( String key, String val ) {});
 * }
 */
S.localStorage = {
    getItem: function (key) {
        return localStorage.getItem(key);
    },

    setItem: function (key, val) {
        return localStorage.setItem(key, val);
    },

    hasItem: function (key) {
        return localStorage.getItem(key) !== null;
    },

    removeItem: function (key) {
        return localStorage.removeItem(key);
    },

    each: function (callback) {
        for ( var i=localStorage.length; i--; ) {
            var key = localStorage.key(i);
            callback(key, localStorage.getItem(key));
        }
    }
};

S.storage = function ( namespace, storage ) {
    this.setNamespace(namespace);
    this.setStorage(storage);
};

S.storage.prototype = {
    seperator: '/',

    setStorage: function (storage) {
        this.storage = (typeof storage == 'undefined' ? S.localStorage : storage);
    },

    getStorage: function () {
        return this.storage;
    },

    setNamespace: function(namespace) {
        this.namespace = String(namespace||'');
    },

    getNamespace: function() {
        return this.namespace;
    },

    getKey: function(key) {
        return (this.namespace ? this.namespace + this.seperator : this.namespace) + key;
    },

    has: function (key) {
        return this.storage.hasItem(this.getKey(key));
    },

    get: function (key) {
        return this.storage.getItem(this.getKey(key));
    },

    set: function (key, value) {
        return this.storage.setItem(this.getKey(key), value);
    },

    remove: function (key) {
        return this.storage.removeItem(this.getKey(key));
    },

    has_object: function (key) {
        return this.has(key);
    },

    get_object: function (key) {
        var data = this.get(key);
        try {
            return typeof data == 'string' ? JSON.parse(data) : null;
        } catch (e) {
            return null;
        }
    },

    set_object: function (key, data) {
        return this.set(key, JSON.stringify(data));
    },

    remove_object: function (key) {
        return this.remove(key);
    },

    /**
     * removes all items
     */
    clear: function () {
        var self = this;
        this.each( function (key) {
            self.remove(key);
        });
    },

    /**
     * calls function on each item
     *
     * @param Function callback A function called with one parameter: the item key
     */
    each: function (callback) {
        var prefix = this.getKey('');
        this.storage.each(
            function (key, val) {
                if ( key.indexOf(prefix) == 0 ) {
                    // console.debug("call each on: " + key)
                    callback( key.substr(prefix.length) );
                } else {
                    // console.debug("key '"+key+"' not in namespace: " + prefix);
                }
            }
        );
    },

    keys: function() {
        var keys = [];
        this.each(function(key) {
            keys.push(key);
        });
        return keys;
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
