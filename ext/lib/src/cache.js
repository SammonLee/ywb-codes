/**
 * cache.js - Cache data using local storage
 * Copyright (c) 2010 Ookong Ltd. Co. (ookong.com).
 *
 * SYNOPSIS
 *  var cache = new S.cache();
 *  var data = cache.get("key");
 *  if ( data === null ) {
 *      data = "value";
 *      cache.set("key", data);
 *  }
 *
 * DEPENDENCES
 *  - storage.js
 */
if ( !exports ) var exports = {};
(function(S, undefined){
    // @import console
    // @import storage
/**
 * create cache
 *
 * @param int max_size max cache items in the storage. Default 50.
 *            0 or negative value means no limit on the number of items
 * @param int expire_time seconds since the item created should expire. Default 3600.
 *            0 or negative value to means no expiration.
 * @param S.storage storage storage object. Default using
 *        storage using namespace `cache' with localStorage
 */
S.cache = function (options){
    options = options || {};
    this.max_size = options.max_size || 50;
    this.expire_time = options.expire_time || 3600;
    this.storage = options.storage || new S.storage('cache');
    this.fill_factor = options.fill_factor || 0.62;
    if ( typeof options.count == "undefined" ) {
        var total = 0;
        this.storage.each(function() { total++ });
        options.count = total;
    }
    this._count = options.count;
};

S.cache.prototype = {
    /**
     * checks the item exists or not
     *
     * @param String key the key to refer to the object
     * @return bool return true if cache exists and is valid
     */
    has: function (key) {
        return this._get_item(key) != null;
    },

    /**
     * gets an item in the cache
     *
     * @param String key the key to refer to the object
     * @return object value of the item, or null if cache not exists or expired
     */
    get: function (key) {
        var item = this._get_item(key);
        var console = S.console || { debug: function () {} };
        if ( item != null ) {
            console.debug("CACHE hit " + key);
            return item.value;
        } else {
            console.debug("CACHE miss " + key);
            return null;
        }
    },

    /**
     * sets an item in the cache
     *
     * @param String key the key to refer to the object
     * @param object value value of the item
     * @param object options an optional parameter controls caching options:
     *     - expire_time seconds for the item should expire
     *     - callback A function that gets called when the item is purged from cache.
     *                Called with two parameters: the key and value of the cache item
     */
    set: function (key, value, options) {
        if ( key == null || key == '' ) {
            throw new Error("key cannot be null or empty");
        }
        if ( typeof options == "undefined" ) {
            options = { };
        }
        if ( typeof options.expire_time == 'undefined' ){
            options.expire_time = this.expire_time;
        }
        options.expire_time = new Date().getTime() + options.expire_time * 1000;
        if ( !this.storage.has_object(key) ) {
            this._count++;
        }
        this.storage.set_object(key, {
            value: value,
            key: key,
            options: options
        });
        if ( this.max_size > 0 && (this._count > this.max_size) ) {
            this._purge();
        }
    },

    /**
     * clears all items
     */
    clear: function () {
        this.storage.clear();
    },

    /**
     * removes the item
     *
     * @param String key the key to refer to the object
     */
    remove: function (key) {
        if ( !this.storage.has_object(key) ) {
            return;
        }
        var item = this.storage.get_object(key);
        this.storage.remove_object(key);
        if ( this._count > 0 ) {
            this._count--;
        }
        // if there is a callback function, call it at the end of execution
        if (item.options.callback != null) {
            var callback = function() {
                item.options.callback(item.key, item.value);
            };
            setTimeout(callback, 0);
        }
    },

    _is_expired: function (item) {
        return item.options.expire_time
            && item.options.expire_time < (new Date()).getTime();
    },

    _get_item: function (key) {
        var item = this.storage.get_object(key);
        if ( item != null ) {
            if ( typeof item.options == "undefined" ) {
                // invalid cache item
                this.storage.remove_object(key);
                item = null;
            } else if ( this._is_expired(item) ) {
                this.remove(key);
                item = null;
            }
        }
        return item;
    },

    _purge: function () {
        var purge_size = Math.floor(this.max_size * this.fill_factor);
        if ( purge_size <= 0 ) {
            return;
        }
        // random remove [removeCount] items
        var removeCount = this._count - purge_size;
        if ( removeCount <= 0 ) {
            return;
        }
        var keys = [];
        this.storage.each(function (key) {keys.push(key);});
        var shuffle = function ( arr ) {
            var j, x;
            for ( var i=arr.length-1; i; i-- ) {
                j = Math.floor(Math.random()*i+1);
                x = arr[i];
                arr[i] = arr[j];
                arr[j] = x;
            }
        };
        shuffle(keys);
        for ( var i=0; i<removeCount; i++ ) {
            this.remove(keys[i]);
        }
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
