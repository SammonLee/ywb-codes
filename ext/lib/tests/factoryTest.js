(function(S, undefined){
    // @import factory
    // @import storage
    // @import cache
    module("factory", {
        setup: function() {
            S.factory.init({storage: S.mockStorage});
        }
    });

    test("getStorage", function() {
        var storage = S.factory.getStorage("test");
        ok( storage instanceof S.storage, "storage" );
        equal( storage.namespace, "test" );
    });

    test("getCache", function() {
        var cache = S.factory.getCache();
        ok( cache instanceof S.cache, "cache" );
        equal( cache.storage.namespace, "cache", "cache namespace");
        equal( S.factory.getCache(), cache, "object cached" );
    });
})(exports);
