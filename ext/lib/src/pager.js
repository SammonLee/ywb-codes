/**
 * pager.js - Simple pager operations
 * Copyright (c) 2010 Ookong Ltd. Co. (ookong.com).
 *
 * SYNOPSIS
 *   var pager = new S.pager(10, 2);
 *   pager.getCurrentPage();   // 2
 *   pager.getStartPage();     // 1
 *   pager.getEndPage();       // 10
 *   pager.hasPrevious();      // true
 *   pager.hasNext();          // true
 */

if ( !exports ) var exports = {};

(function(S, undefined) {
/**
 * create pager
 *
 * @param int total_pages total page number
 * @param int current_page current page number
 * @param int buffer how many pages to display(default 10).
 */
S.pager = function (total_pages, current_page, buffer) {
    this.setTotalPages(total_pages || 0);
    this.setCurrentPage(current_page || 1);
    this.setBuffer(buffer || 10);
};

S.pager.prototype = {
    getBuffer: function () {
        return this.buffer;
    },

    setBuffer: function (buffer) {
        buffer = parseInt(buffer);
        if ( buffer && buffer > 0) {
            this.buffer = buffer;
        }
    },

    getTotalPages: function () {
        return this.total_pages;
    },

    setTotalPages: function (n) {
        n = parseInt(n);
        if ( n > this.current_page ) {
            this.current_page = 1;
        }
        return this.total_pages = n;
    },

    getCurrentPage: function () {
        return this.current_page;
    },

    setCurrentPage: function (n) {
        n = parseInt(n);
        if ( n > this.total_pages ) {
            n = this.total_pages;
        } else if ( n < 1 ) {
            n = 1;
        }
        return this.current_page = n;
    },

    getStartPage: function() {
        var start_page = Math.max( this.current_page - Math.floor(this.buffer/2), 1 );
        if ( start_page + this.buffer - 1 > this.total_pages ) {
            start_page = Math.max( this.total_pages+1-this.buffer, 1);
        }
        return start_page;
    },

    getEndPage: function () {
        return Math.min( this.getStartPage() + this.buffer -1, this.total_pages );
    },

    hasPrevious : function () {
        return this.current_page > 1;
    },

    hasNext : function () {
        return this.current_page < this.total_pages;
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
