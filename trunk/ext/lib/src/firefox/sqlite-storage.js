/**
 * sqlite-storage.js - Implement localStorage interface using sqlite database
 * Copyright (c) 2010 Ookong Ltd. Co. (ookong.com).
 *
 * SYNOPSIS
 *  S.SqliteStorage.setItem("key", "value");
 *  S.SqliteStorage.getItem("key");         // "value"
 *  S.SqliteStorage.hasItem("key");         // true
 *  S.SqliteStorage.removeItem("key");
 *
 * DESCRIPTION
 * SqliteStorage implements localStorage interface, storage data in sqlite
 * database.
 */
if ( !exports ) var exports = {};

(function(S, undefined) {
S.SqliteStorage = {
    /**
     * set storage options
     * @param String file database file name.
     */
    init: function (options) {
        this.file = options.file;
        this.conn = undefined;
    },

    getConnection: function() {
        if ( typeof this.conn == "undefined" ) {
            var file;
            if ( typeof this.file == "string" ) {
                file = Components.classes["@mozilla.org/file/directory_service;1"]
                    .getService(Components.interfaces.nsIProperties)
                    .get("ProfD", Components.interfaces.nsIFile);
                file.append(this.file);
            } else {
                file = this.file;
            }
            var storageService = Components.classes["@mozilla.org/storage/service;1"]
                .getService(Components.interfaces.mozIStorageService);
            var conn = storageService.openDatabase(file);
            if ( conn && conn.connectionReady ) {
                // create table if need
                if ( !conn.tableExists("storage") ) {
                    conn.createTable("storage", "key TEXT PRIMARY KEY ON CONFLICT REPLACE, value TEXT");
                }
                this.setConnetion(conn);
            } else {
                throw "Connection failed!";
            }
        }
        return this.conn;
    },

    setConnetion: function(conn) {
        this.conn = conn;
    },

    setItem: function (key, val) {
        var sql = "REPLACE INTO storage VALUES (?1, ?2)";
        var stmt = this.getConnection().createStatement(sql);
        stmt.bindUTF8StringParameter(0, key);
        stmt.bindUTF8StringParameter(1, val);
        stmt.execute();
    },

    getItem: function (key) {
        var sql = "SELECT value FROM storage WHERE key = ?1";
        var stmt = this.getConnection().createStatement(sql);
        var val;
        stmt.bindUTF8StringParameter(0, key);
        if ( stmt.executeStep() ) {
            val = stmt.getUTF8String(0);
        }
        stmt.reset();
        return val;
    },

    hasItem: function (key) {
        return typeof this.getItem(key) != "undefined";
    },

    removeItem: function (key) {
        var sql = "DELETE FROM storage where key = ?1";
        var stmt = this.getConnection().createStatement(sql);
        stmt.bindUTF8StringParameter(0, key);
        stmt.execute();
    },

    each: function ( callback ) {
        var sql = "SELECT key,value FROM storage";
        var stmt;
        stmt = this.getConnection().createStatement(sql);
        while ( stmt.executeStep() ) {
            callback(stmt.getUTF8String(0), stmt.getUTF8String(1));
        }
        stmt.reset();
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
