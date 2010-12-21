/**
 * page_cache.js - Smart pager
 * Copyright (c) 2010 Ookong Ltd. Co. (ookong.com).
 */
if ( !exports ) var exports = {};
(function(S, undefined) {
/**
 * interface PageCache {
 *    function next([callback]);
 *    function previous([callback]);
 *    function hasNext();
 *    function hasPrevious();
 *    function getTotalPages();
 *    function get(page_no[, callback]);
 *    function reset();
 * }
 */
S.PageCache = function (generator, settings, async) {
    if ( async ) {
        return new S.PageCache.async(generator, settings);
    } else {
        return new S.PageCache.sync(generator, settings);
    }
};

/**
 * create page cache.
 * There are two types of generator:
 *  - function() {}
 *    only returns some entries, but don't known how many pages will
 *    get, and sometimes the number of entries does not equals each
 *    time. In this occasion, the generator is a function call with
 *    no arguments, and return an array of entries. When it returns
 *    an empty array, it means no more entries.
 *  - { PageSize: page_size, get: function(page_no) {} }
 *    return page_size entries each time and knows how many pages will
 *    gets. get function will return an object like:
 *    { TotalPages: total_pages, TotalEntries: total_entries, Entries: [] }
 *    The "TotalEntries" field is optional. start page is 1
 *
 * @param Function generator
 * @param object settings page cache options:
 *    - PageSize max items for one page
 */
S.PageCache.sync = function(generator, settings) {
    if ( arguments.length == 0 ) {
        return;
    }
    // internal page_no start from 0
    this.page_no = -1;
    this.pager_total_pages = undefined;
    this.pager_total_entries = undefined;
    this.cache = [];
    if ( typeof generator == "function" ) {
        this.generator = generator;
        this.generator_finished = false;
    } else {
        this.pager = generator.get;
        this.pager_page_size = generator.PageSize;
    }
    this.page_size = settings.PageSize;
};

S.PageCache.sync.prototype = {
    /**
     * get entries in the next page
     *
     * @return Array the entries in the next page.
     *       return empty array if no more entries
     */
    next: function () {
        var entries = [];
        if ( this.hasNext() ) {
            this.page_no++;
            entries = this.get(this.page_no+1).Entries;
            if ( entries.length == 0 ) {
                this.page_no--;
            }
        }
        return entries;
    },

    /**
     * get entries in the previous page
     *
     * @return Array the entries in the previous page.
     *       returns empty array if no previous page
     */
    previous: function() {
        var entries = [];
        if ( this.hasPrevious() ) {
            this.page_no--;
            entries = this.get(this.page_no+1).Entries;
        }
        return entries;
    },

    /**
     * check whether more entries to get
     *
     * @return bool
     */
    hasNext: function () {
        var total = this.getTotalPages();
        return isNaN(total) ? true : this.page_no+1<total;
    },

    /**
     * check whether there is previous page
     *
     * @return bool
     */
    hasPrevious: function () {
        return this.page_no > 0;
    },

    /**
     * gets total pages number
     *
     * @return int total pages. If total pages is not determined, return NaN
     */
    getTotalPages: function () {
        var total_entries;
        if ( this.pager ) {
            if ( typeof this.pager_total_pages == "undefined" ) {
                return NaN;
            }
            if ( typeof this.pager_total_entries == "undefined" ) {
                if ( typeof this.cache[this.pager_total_pages-1] != "undefined" ) {
                    total_entries = (this.pager_total_pages-1) * this.pager_page_size
                        + this.cache[this.pager_total_pages-1].Entries.length;
                } else {
                    total_entries = this.pager_total_pages * this.pager_page_size;
                }
            } else {
                total_entries = this.pager_total_entries;
            }
        } else {
            if ( !this.generator_finished ) {
                return NaN;
            } else {
                total_entries = this.cache.length;
            }
        }
        return Math.ceil( total_entries / this.page_size );
    },

    /**
     * gets entries in given page
     *
     * @param int page_no start from 1
     * @return object a object as {TotalPages: pages, Entries: []}
     */
    get: function (page_no) {
        page_no = parseInt(page_no);
        if ( isNaN(page_no) || page_no < 1 ) {
            page_no = 1;
        }
        var entries = [];
        var start = (page_no-1) * this.page_size;
        var end = page_no * this.page_size;
        if ( this.pager ) {
            var page_start = Math.floor(start/this.pager_page_size);
            var page_end = Math.ceil(end/this.pager_page_size);
            for ( var i=page_start; i<page_end; i++ ) {
                if ( typeof this.cache[i] == "undefined" ) {
                    this.cache[i] = this.pager(i+1);
                }
                var offset = this.pager_page_size*i;
                var slice_start = offset>=start ? 0 : start-offset;
                var slice_end = offset+this.pager_page_size > end ? end - offset : this.pager_page_size;
                entries.push.apply(entries, this.cache[i].Entries.slice(slice_start, slice_end));
                if ( typeof this.pager_total_pages == "undefined" ) {
                    this.pager_total_pages = this.cache[i].TotalPages;
                }
                page_end = Math.min(page_end, this.pager_total_pages);
            }
            if ( typeof this.cache[page_start] != "undefined" && this.cache[page_start].TotalEntries ) {
                this.pager_total_entries = this.cache[page_start].TotalEntries;
            }
        } else {
            var next;
            while ( !this.generator_finished && this.cache.length < end ) {
                next = this.generator();
                if ( next.length == 0 ) {
                    this.generator_finished = true;
                }
                this.cache.push.apply(this.cache, next);
            }
            entries = this.cache.slice(start, end);
        }
        return { TotalPages: this.getTotalPages(), Entries: entries };
    },

    /**
     * reset page offset
     */
    reset: function () {
        this.page_no = -1;
    }
};

/**
 * asynchronize get page.
 * Similar to S.PageCache, there are two types of generator:
 *  - function( page_cb = function(entries){} ) {}
 *  the page_cb is a function with one argument as an array of entries in the page
 *  - { PageSize: page_size, get: function(page_no, page_cb = function(page_no, results){} ) {} }
 *  the page_cb is a function with two arguments, first is the current page number,
 *  second is an object {TotalPages: pages, TotalEntries: total_entries, Entries: []}.
 *  the "TotalEntries" field is optional.
 */
S.PageCache.async = function (generator, settings) {
    S.PageCache.sync.call(this, generator, settings);
};
S.PageCache.async.prototype = new S.PageCache.sync();
S.PageCache.async.prototype.constructor = S.PageCache.async;

/**
 * get entries in the next page
 *
 * @param Function callback=function(entries) {} A function called when entries ready
 */
S.PageCache.async.prototype.next = function(callback) {
    if ( this.hasNext() ) {
        this.page_no++;
        var self = this;
        this.get(this.page_no+1, function(results) {
            var entries = results.Entries;
            if ( entries.length == 0 ) {
                self.page_no--;
            }
            callback(entries);
        });
    } else {
        callback([]);
    }
};

/**
 * get entries in the previous page
 *
 * @param Function callback=function(entries) {} A function called when entries ready
 */
S.PageCache.async.prototype.previous = function(callback) {
    if ( this.hasPrevious() ) {
        this.page_no--;
        this.get(this.page_no+1, function(results) {
            callback(results.Entries);
        });
    } else {
        callback([]);
    }
};

/**
 * get entries in the given page
 *
 * @param int page_no
 * @param Function callback=function(results, page_no) {} A function called when entries ready
 *   the results is an object like {TotalPages: pages, Entries: []}
 */
S.PageCache.async.prototype.get = function(page_no, callback) {
    var self = this;
    page_no = parseInt(page_no);
    if ( isNaN(page_no) || page_no < 1 ) {
        page_no = 1;
    }
    var start = (page_no-1) * this.page_size;
    var end = page_no * this.page_size;
    var entries = [];
    var finish = function() {
        callback({ TotalPages: self.getTotalPages(), Entries: entries }, page_no);
    };
    if ( this.pager ) {
        var page_start = Math.floor(start/this.pager_page_size);
        var page_end = Math.ceil(end/this.pager_page_size);
        var get_page = function (pager_page_no, entries) {
            if ( pager_page_no >= page_end ) {
                if ( typeof self.cache[page_start] != "undefined" && self.cache[page_start].TotalEntries ) {
                    self.pager_total_entries = self.cache[page_start].TotalEntries;
                }
                finish();
                return;
            }
            var cb = function(pager_page_no, results) {
                pager_page_no = pager_page_no-1;
                self.cache[pager_page_no] = results;
                var offset = self.pager_page_size*pager_page_no;
                var slice_start = offset>=start ? 0 : start-offset;
                var slice_end = offset+self.pager_page_size > end ? end - offset : self.pager_page_size;
                entries.push.apply(entries, self.cache[pager_page_no].Entries.slice(slice_start, slice_end));
                if ( typeof self.pager_total_pages == "undefined" ) {
                    self.pager_total_pages = self.cache[pager_page_no].TotalPages;
                }
                page_end = Math.min(page_end, self.pager_total_pages);
                get_page(pager_page_no+1, entries);
            };
            if ( typeof self.cache[pager_page_no] == "undefined" ) {
                self.pager(pager_page_no+1, cb);
            } else {
                cb(pager_page_no+1, self.cache[pager_page_no]);
            }
        };
        get_page(page_start, entries);
    } else {
        if ( !this.generator_finished && this.cache.length < end ) {
            var cb = function(next) {
                if ( next.length == 0 ) {
                    self.generator_finished = true;
                }
                self.cache.push.apply(self.cache, next);
                if ( !self.generator_finished && self.cache.length < end ) {
                    self.generator(cb);
                } else {
                    entries = self.cache.slice(start, end);
                    finish();
                }
            };
            this.generator(cb);
        } else {
            entries = self.cache.slice(start, end);
            finish();
        }
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
