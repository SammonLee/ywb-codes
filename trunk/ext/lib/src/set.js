/**
 * set.js - Basic set operations (diff, intersect, union)
 * Copyright (c) 2010 Ookong Ltd. Co. (ookong.com).
 *
 * SYNOPSIS
 *  var set1 = new S.set([1, 2]);
 *  var set2 = new S.set([2, 3]);
 *  set1.union(set2).toArray();     // [1, 2, 3]
 *  set1.intersect(set2).toArray(); // [2]
 *  set1.diff(set2).toArray();      // [1]
 *  // create set with hash function
 *  var set = new S.set(
 *    [{name: "John", age: 26}, {name: "Tom", age: 27}],
 *    function ( elem ) { return elem.name; }
 *  );
 *
 * DESCRIPTION
 * A set is constructed by an array of elements and a hash function.
 * The hash function map all elements in the array to a string.
 * Same string will reduce the elements to same element in the set.
 */

if ( !exports ) var exports = {};
(function(S, undefined) {
    // @import util
/**
 * create set
 *
 * @param Array arr the array with elements to add to the set
 * @param Function hashFunc the hash function to map element to a string
 */
S.set = function (elements, hashFunc){
    this.setHashFunction(hashFunc);
    this.setElements(elements);
};

S.set.prototype = {
    setElements: function (elements) {
        this.elements = {};
        if ( !S.is_array(elements) ) {
            return;
        }
        for ( var i in elements ) {
            this.add(elements[i]);
        }
    },

    getElements: function () {
        return this.toArray();
    },

    toArray: function () {
        var arr = [];
        this.each(function (key, elem) { arr.push(elem); });
        return arr;
    },

    setHashFunction: function (hashFunc) {
        function identity(elem) {
            return elem;
        }
        this.hash = (typeof hashFunc == "undefined" ? identity : hashFunc);
    },

    getHashFunction: function () {
        return this.hash;
    },

    add: function (elem) {
        this.elements[this.hash(elem)] = elem;
    },

    has: function (elem) {
        var key = this.hash(elem);
        return key in this.elements;
    },

    each: function (callback){
        for ( var prop in this.elements ) {
            if ( this.elements.hasOwnProperty(prop) ) {
                callback(prop, this.elements[prop]);
            }
        }
    },

    intersect: function ( aSet ) {
        var common = new S.set([], this.hash);
        this.each(
            function (key, elem) {
                if ( key in aSet.elements ) {
                    common.elements[key] = elem;
                }
            }
        );
        return common;
    },

    diff: function( aSet ){
        var diff = new S.set([], this.hash);
        this.each(
            function (key, elem) {
                if ( !(key in aSet.elements) ) {
                    diff.elements[key] = elem;
                }
            }
        );
        return diff;
    },

    union: function (aSet) {
        var union = new S.set([], this.hash);
        var cb = function (key, elem) { union.elements[key] = elem; };
        this.each(cb);
        aSet.each(cb);
        return union;
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
