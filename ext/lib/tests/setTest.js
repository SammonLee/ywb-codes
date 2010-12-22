(function(S, undefined){
    // @import set
    module("set");
    test("all", function () {
        var a = new S.set([1, 2]);
        var b = new S.set([2, 3]);

        same(a.union(b).toArray(), [1, 2, 3], 'union');
        same(a.intersect(b).toArray(), [2], 'intersect');
        same(a.diff(b).toArray(), [1], 'diff');
    });

    test("hashed", function () {
        var hash = function ( elem ) { return elem.ASIN; };
        var a = new S.set(
            [{'ASIN': 'a'}, {'ASIN': 'b'}], hash
        );
        var b = new S.set(
            [{'ASIN': 'b'}, {'ASIN': 'c'}], hash
        );

        same(a.union(b).toArray(), [{ASIN: "a"}, {'ASIN':'b'}, {ASIN:"c"}], "union");
        same(a.intersect(b).toArray(), [{'ASIN':'b'}], "intersect");
        same(a.diff(b).toArray(), [{'ASIN':'a'}], "diff");
    });
})(exports);
