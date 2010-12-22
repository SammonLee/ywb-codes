(function(S, undefined) {
    // @import storage
    // @import cache

    var storage;
    var cache;
    module("cache", {
        setup: function () {
            S.mockStorage.clear();
            storage = new S.storage("cache", S.mockStorage);
            cache = new S.cache({storage: storage});
        }
    });

    test("get/set", function () {
        cache.clear();
        ok(!cache.has("foo"), "has");
        ok(cache.get("foo") === null, "get miss item");
        cache.set("foo", "bar");
        equals(cache.get("foo"), "bar", "get after set");
    });

    test("clear", function () {
        cache.set("foo", "bar");
        equals(S.mockStorage.length, 1, "set");
        cache.clear();
        same(S.mockStorage.length, 0, "clear");
    });

    test("purge", function () {
        var cache = new S.cache({max_size: 5, storage: storage});
        for ( var i=0; i<5; i++ ) {
            cache.set("foo"+i, i);
        }
        equals(S.mockStorage.length, 5, "set to max_size");
        cache.set("foo5", 5);
        equals(S.mockStorage.length, 3, "purge to fill_factor");
    });

    asyncTest("expire", function () {
        var cache = new S.cache({expire_time: 0.100, storage: storage});
        cache.set("foo", "bar");
        ok(cache.has("foo"), "set");
        setTimeout(function() {
            ok(!cache.has("foo"), "expire");
            start();
        }, 200);
    });
})(exports);
