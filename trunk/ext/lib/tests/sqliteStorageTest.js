(function(S, undefined) {
    // @import sqlite-storage
    var file = Components.classes["@mozilla.org/file/local;1"].  
        createInstance(Components.interfaces.nsILocalFile);
    file.initWithPath("/tmp/test.sqlite");
    if ( file.exists() ) {
        // empty file
        var foStream = Components.classes["@mozilla.org/network/file-output-stream;1"].  
            createInstance(Components.interfaces.nsIFileOutputStream);
        foStream.init(file, 0x04 | 0x08 | 0x20, 0666, 0)
        foStream.write('', 0);
        foStream.close();
    }
    S.SqliteStorage.init({ file: file });
    module("sqliteStorage");

    test("getItem", function() {
        equal(S.SqliteStorage.getItem("foo"), null);
    });

    test("hasItem", function() {
        ok(!S.SqliteStorage.hasItem("foo"));
    });

    test("setItem", function() {
        S.SqliteStorage.setItem("key", "value");
        equal(S.SqliteStorage.getItem("key"), "value");
        ok(S.SqliteStorage.hasItem("key"));
    });

    test("removeItem", function() {
        S.SqliteStorage.setItem("key2", "value");
        S.SqliteStorage.removeItem("key2");
        ok(!S.SqliteStorage.hasItem("key2"));
    });

    test("each", function() {
        S.SqliteStorage.setItem("key", "value");
        S.SqliteStorage.setItem("key2", "value");
        var items = [];
        S.SqliteStorage.each(function(k, v) {
            items.push([k, v]);
        });
        same(items, [['key', 'value'], ['key2', 'value']]);
    });
})(exports);
