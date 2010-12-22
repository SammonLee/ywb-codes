(function(S) {
    module("pageAsyncCache");
    var generator = function(max) {
        var count = 1; var size = 2;
        return function (callback) {
            var results = [];
            if ( count <= max ) {
                var end = Math.min(count+size-1, max);
                for ( var i=count; i<=end; i++ ) {
                    results.push(i);
                }
                count = end+1;
                size++;
            }
            callback(results);
        };
    };
    test("generator next", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5}, true);
        pager.next( function (results) { same(results, [1,2,3,4,5]); })
        pager.next( function (results) { same(results, [6,7,8,9]); })
        pager.next( function (results) { same(results, []); })

        pager = S.PageCache(generator(10), {PageSize: 5}, true);
        pager.next( function (results) { same(results, [1,2,3,4,5]); })
        pager.next( function (results) { same(results, [6,7,8,9,10]); })
        pager.next( function (results) { same(results, []); })
    });

    test("generator previous", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5}, true);
        var ignore = function() {};
        pager.previous(function (results) { same(results, []); })
        pager.next(ignore);
        pager.previous(function (results) { same(results, []); })
        pager.next(ignore); 
        pager.previous(function (results) { same(results, [1,2,3,4,5]); })
        pager.next(ignore);
        pager.previous(function (results) { same(results, [1,2,3,4,5]); })

        pager = S.PageCache(generator(10), {PageSize: 5}, true);
        pager.next(ignore); pager.next(ignore); 
        pager.previous(function (results) { same(results, [1,2,3,4,5]); })
        pager.next(ignore); pager.next(ignore);
        pager.previous(function (results) { same(results, [1,2,3,4,5]); })
    });

    test("generator hasNext", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5}, true);
        var ignore = function() {};
        ok(pager.hasNext(), "hasNext");
        pager.next(ignore);
        ok(pager.hasNext(), "hasNext");
        pager.next(function(results) { same(results, [6,7,8,9]) });
        ok(!pager.hasNext(), "hasNext");
        pager.previous(ignore);
        ok(pager.hasNext(), "hasNext");

        pager = S.PageCache(generator(10), {PageSize: 5}, true);
        pager.next(ignore); pager.next(ignore); // page_no=1
        ok(pager.hasNext(), "hasNext");
        pager.next(ignore);               // page_no=1
        ok(!pager.hasNext(), "hasNext");
        pager.previous(ignore);
        ok(pager.hasNext(), "hasNext");
    });

    test("generator hasPrevious", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5}, true);
        var ignore = function() {};
        ok(!pager.hasPrevious());
        pager.next(ignore);
        ok(!pager.hasPrevious());
        pager.next(ignore);
        ok(pager.hasPrevious());
    });

    test("generator getTotalPages", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5}, true);
        var ignore = function() {};
        ok(isNaN(pager.getTotalPages()));
        pager.next(ignore); pager.next(ignore);
        equals(pager.getTotalPages(), 2);

        pager = S.PageCache(generator(10), {PageSize: 5}, true);
        ok(isNaN(pager.getTotalPages()));
        pager.next(ignore); pager.next(ignore);
        ok(isNaN(pager.getTotalPages()));
        pager.next(ignore);
        equals(pager.getTotalPages(), 2);
    });

    test("generator get", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5}, true);
        pager.get(1, function(results) { same(results, {TotalPages:NaN, Entries:[1,2,3,4,5]}) });
        pager.get(2, function(results) { same(results, {TotalPages:2, Entries:[6,7,8,9]}) });
        pager.get(1, function(results) { same(results, {TotalPages:2, Entries:[1,2,3,4,5]}) });

        var pager = S.PageCache(generator(10), {PageSize: 5}, true);
        pager.get(1, function(results) { same(results, {TotalPages:NaN, Entries:[1,2,3,4,5]}) });
        pager.get(2, function(results) { same(results, {TotalPages:NaN, Entries:[6,7,8,9,10]}) });
        pager.get(3, function(results) { same(results, {TotalPages:2, Entries:[]}) });
        pager.get(2, function(results) { same(results, {TotalPages:2, Entries:[6,7,8,9,10]}) });
    });

    var pager_generator = function(page_size,total_entries, get_entries){
        var generator = {PageSize: page_size};
        generator.get = function(page_no, callback){
            var results = [];
            var start = (page_no-1) * generator.PageSize + 1;
            var end = Math.min(start+generator.PageSize-1, total_entries);
            for ( var i=start; i<=end; i++ ) {
                results.push(i);
            }
            results = { TotalPages: Math.ceil(total_entries/generator.PageSize), Entries: results };
            if ( get_entries ) {
                results.TotalEntries = total_entries;
            }
            callback(page_no, results);
        };
        return generator;
    };

    test("pager next", function() {
        var pager = S.PageCache(pager_generator(10, 9), {PageSize: 5}, true);
        pager.next( function (results) { same(results, [1,2,3,4,5]); })
        pager.next( function (results) { same(results, [6,7,8,9]); })
        pager.next( function (results) { same(results, []); })

        pager = S.PageCache(pager_generator(10, 11), {PageSize: 5}, true);
        pager.next( function (results) { same(results, [1,2,3,4,5]); })
        pager.next( function (results) { same(results, [6,7,8,9,10]); })
        pager.next( function (results) { same(results, [11]); })
        pager.next( function (results) { same(results, []); })
        
        pager = S.PageCache(pager_generator(3, 10), {PageSize: 5}, true);
        pager.next( function (results) { same(results, [1,2,3,4,5]); })
        pager.next( function (results) { same(results, [6,7,8,9,10]); })
        pager.next( function (results) { same(results, []); })

        pager = S.PageCache(pager_generator(3, 12), {PageSize: 6}, true);
        pager.next( function (results) { same(results, [1,2,3,4,5,6]); })
        pager.next( function (results) { same(results, [7,8,9,10,11,12]); })
        pager.next( function (results) { same(results, []); })
    });

    test("pager previous", function() {
        var pager = S.PageCache(pager_generator(10, 9), {PageSize: 5}, true);
        var ignore = function() {};
        pager.previous(function (results) { same(results, []); })
        pager.next(ignore);
        pager.previous(function (results) { same(results, []); })
        pager.next(ignore); 
        pager.previous(function (results) { same(results, [1,2,3,4,5]); })
        pager.next(ignore);
        pager.previous(function (results) { same(results, [1,2,3,4,5]); })
    });

    test("pager get", function(){
        var pager = S.PageCache(pager_generator(10, 9), {PageSize: 5}, true);
        pager.get(1, function(results) { same(results, {TotalPages:2, Entries:[1,2,3,4,5]}) });
        pager.get(2, function(results) { same(results, {TotalPages:2, Entries:[6,7,8,9]}) });

        pager = S.PageCache(pager_generator(10, 12), {PageSize: 5}, true);
        pager.get(1, function(results) { same(results, {TotalPages:4, Entries:[1,2,3,4,5]}) });
        pager.get(2, function(results) { same(results, {TotalPages:4, Entries:[6,7,8,9,10]}) });
        pager.get(3, function(results) { same(results, {TotalPages:3, Entries:[11,12]}) });

        pager = S.PageCache(pager_generator(10, 12, true), {PageSize: 5}, true);
        pager.get(1, function(results) { same(results, {TotalPages:3, Entries:[1,2,3,4,5]}) });

        pager = S.PageCache(pager_generator(3, 12), {PageSize: 6}, true);
        pager.get(1, function(results) { same(results, {TotalPages:2, Entries:[1,2,3,4,5,6]}) });
        pager.get(2, function(results) { same(results, {TotalPages:2, Entries:[7,8,9,10,11,12]}) });
    })
})(exports);
