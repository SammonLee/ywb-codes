(function(S, undefined) {
    // @import storage
    var storage;
    module("storage", {
        setup: function(){
            S.mockStorage.clear();
            storage = new S.storage("test", S.mockStorage);
        }
    });
    
    test("string", function () {
        storage.set("foo", "bar");
        equals(storage.get("foo"), "bar", "set");
        ok(storage.has("foo"), "has");
        storage.each(
            function ( key ) {
                equals(key, "foo", "each");
            }
        );
        storage.remove("foo");
        ok(!storage.has("foo"), "remove");
    });

    test("object", function () {
        storage.set_object("foo", {f: 1});
        same( storage.get_object("foo"), {f: 1}, "get/set object" );
        storage.clear();
        ok(!storage.has("foo"), "clear");

        storage.set("foo", "{");
        ok( storage.get_object("foo") == null, "invalid json");
    });

    test("keys", function() {
        storage.set('key', 1);
        storage.set('key2', 'a');
        same(storage.keys(), ['key', 'key2']);
    });
})(exports);
