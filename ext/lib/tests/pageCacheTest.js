(function(S, undefined) {
    module("pageCache");
    var generator = function(max) {
        var count = 1; var size = 2;
        return function () {
            var results = [];
            if ( count <= max ) {
                var end = Math.min(count+size-1, max);
                for ( var i=count; i<=end; i++ ) {
                    results.push(i);
                }
                count = end+1;
                size++;
            }
            return results;
        };
    };

    test("generator next", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5});
        same(pager.next(), [1,2,3,4,5]);
        same(pager.next(), [6,7,8,9]);
        same(pager.next(), []);

        pager = S.PageCache(generator(10), {PageSize: 5});
        same(pager.next(), [1,2,3,4,5]);
        same(pager.next(), [6,7,8,9,10]);
        same(pager.next(), []);
    });

    test("generator previous", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5});
        same(pager.previous(), []);
        pager.next();
        same(pager.previous(), []);
        pager.next(); 
        same(pager.previous(), [1,2,3,4,5]);
        pager.next();
        same(pager.previous(), [1,2,3,4,5]);

        pager = S.PageCache(generator(10), {PageSize: 5});
        pager.next(); pager.next(); 
        same(pager.previous(), [1,2,3,4,5]);
        pager.next(); pager.next();
        same(pager.previous(), [1,2,3,4,5]);
    });

    test("generator hasNext", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5});
        ok(pager.hasNext(), "hasNext");
        pager.next();
        ok(pager.hasNext(), "hasNext");
        same(pager.next(), [6,7,8,9]);
        ok(!pager.hasNext(), "hasNext");
        pager.previous();
        ok(pager.hasNext(), "hasNext");

        pager = S.PageCache(generator(10), {PageSize: 5});
        pager.next(); pager.next(); // page_no=1
        ok(pager.hasNext(), "hasNext");
        pager.next();               // page_no=1
        ok(!pager.hasNext(), "hasNext");
        pager.previous();
        ok(pager.hasNext(), "hasNext");
    });

    test("generator hasPrevious", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5});
        ok(!pager.hasPrevious());
        pager.next();
        ok(!pager.hasPrevious());
        pager.next();
        ok(pager.hasPrevious());
    });

    test("generator getTotalPages", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5});
        ok(isNaN(pager.getTotalPages()));
        pager.next(); pager.next();
        equals(pager.getTotalPages(), 2);

        pager = S.PageCache(generator(10), {PageSize: 5});
        ok(isNaN(pager.getTotalPages()));
        pager.next(); pager.next();
        ok(isNaN(pager.getTotalPages()));
        pager.next();
        equals(pager.getTotalPages(), 2);
    });

    test("generator getPage", function() {
        var pager = S.PageCache(generator(9), {PageSize: 5});
        same(pager.get(1), {TotalPages:NaN, Entries:[1,2,3,4,5]});
        same(pager.get(2), {TotalPages: 2, Entries: [6,7,8,9]})
        same(pager.get(1), {TotalPages: 2, Entries: [1,2,3,4,5]});

        var pager = S.PageCache(generator(10), {PageSize: 5});
        same(pager.get(1), {TotalPages: NaN, Entries: [1,2,3,4,5]});
        same(pager.get(2), {TotalPages: NaN, Entries: [6,7,8,9,10]});
        same(pager.get(3), {TotalPages: 2, Entries: []});
        same(pager.get(2), {TotalPages: 2, Entries: [6,7,8,9,10]});
    });

    var pager_generator = function(page_size,total_entries, get_entries){
        var generator = {PageSize: page_size};
        generator.get = function(page_no){
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
            return results;
        };
        return generator;
    };

    test("pager next", function() {
        var pager = S.PageCache(pager_generator(10, 9), {PageSize: 5});
        same(pager.next(), [1,2,3,4,5]);
        same(pager.next(), [6,7,8,9]);
        same(pager.next(), []);

        pager = S.PageCache(pager_generator(10, 11), {PageSize: 5});
        same(pager.next(), [1,2,3,4,5]);
        same(pager.next(), [6,7,8,9,10]);
        same(pager.next(), [11]);
        same(pager.next(), []);
        
        pager = S.PageCache(pager_generator(3, 10), {PageSize: 5});
        same(pager.next(), [1,2,3,4,5]);
        same(pager.next(), [6,7,8,9,10]);
        same(pager.next(), []);

        pager = S.PageCache(pager_generator(3, 12), {PageSize: 6});
        same(pager.next(), [1,2,3,4,5,6]);
        same(pager.next(), [7,8,9,10,11,12]);
        same(pager.next(), []);
    });

    test("pager previous", function() {
        var pager = S.PageCache(pager_generator(10, 9), {PageSize: 5});
        same(pager.previous(), []);
        pager.next();
        same(pager.previous(), []);
        pager.next();
        same(pager.previous(), [1,2,3,4,5]);
        pager.next();
        same(pager.previous(), [1,2,3,4,5]);
    });

    test("pager getPage", function(){
        var pager = S.PageCache(pager_generator(10, 9), {PageSize: 5});
        same(pager.get(1), {TotalPages: 2, Entries: [1,2,3,4,5]});
        same(pager.get(2), {TotalPages: 2, Entries: [6,7,8,9]});

        pager = S.PageCache(pager_generator(10, 12), {PageSize: 5});
        same(pager.get(1), {TotalPages: 4, Entries: [1,2,3,4,5]});
        same(pager.get(2), {TotalPages: 4, Entries: [6,7,8,9,10]});
        same(pager.get(3), {TotalPages: 3, Entries: [11,12]});

        pager = S.PageCache(pager_generator(10, 12, true), {PageSize: 5});
        same(pager.get(1), {TotalPages: 3, Entries: [1,2,3,4,5]});

        pager = S.PageCache(pager_generator(3, 11), {PageSize: 6});
        same(pager.get(1), {TotalPages: 2, Entries: [1,2,3,4,5,6]});
        same(pager.get(2), {TotalPages: 2, Entries: [7,8,9,10,11]});
    })
})(exports);
