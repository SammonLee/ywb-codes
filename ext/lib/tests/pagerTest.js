(function(S, undefined) {
    module("pager");
    test("total=10, current=1", function () {
        var pager = new S.pager(10, 1);
        equals(pager.getCurrentPage(), 1, 'getCurrentPage()');
        equals(pager.getStartPage(), 1, 'getStartPage()');
        equals(pager.getEndPage(), 10, 'getEndPage()');
        equals(pager.getTotalPages(), 10, 'getTotalPages()');
        ok(!pager.hasPrevious(), 'hasPrevious()');
        ok(pager.hasNext(), 'hasNext()');
    });

    test("total=10, current=5", function () {
        var pager = new S.pager(10, 5);
        equals(pager.getCurrentPage(), 5, 'getCurrentPage()');
        equals(pager.getStartPage(), 1, 'getStartPage()');
        equals(pager.getEndPage(), 10, 'getEndPage()');
        equals(pager.getTotalPages(), 10, 'getTotalPages()');
        ok(pager.hasPrevious(), 'hasPrevious()');
        ok(pager.hasNext(), 'hasNext()');
    });

    test("total=10, current=7", function () {
        var pager = new S.pager(10, 7);
        equals(pager.getCurrentPage(), 7, 'getCurrentPage()');
        equals(pager.getStartPage(), 1, 'getStartPage()');
        equals(pager.getEndPage(), 10, 'getEndPage()');
        equals(pager.getTotalPages(), 10, 'getTotalPages()');
        ok(pager.hasPrevious(), 'hasPrevious()');
        ok(pager.hasNext(), 'hasNext()');
    });

    test("total=10, current=10", function () {
        var pager = new S.pager(10, 10);
        equals(pager.getCurrentPage(), 10, 'getCurrentPage()');
        equals(pager.getStartPage(), 1, 'getStartPage()');
        equals(pager.getEndPage(), 10, 'getEndPage()');
        equals(pager.getTotalPages(), 10, 'getTotalPages()');
        ok(pager.hasPrevious(), 'hasPrevious()');
        ok(!pager.hasNext(), 'hasNext()');
    });

    test("total=10, current=1, buffer=9", function () {
        var pager = new S.pager(10, 1, 9);
        equals(pager.getCurrentPage(), 1, 'getCurrentPage()');
        equals(pager.getStartPage(), 1, 'getStartPage()');
        equals(pager.getEndPage(), 9, 'getEndPage()');
        equals(pager.getTotalPages(), 10, 'getTotalPages()');
        ok(!pager.hasPrevious(), 'hasPrevious()');
        ok(pager.hasNext(), 'hasNext()');
    });

    test("total=10, current=4, buffer=9", function () {
        var pager = new S.pager(10, 4, 9);
        equals(pager.getCurrentPage(), 4, 'getCurrentPage()');
        equals(pager.getStartPage(), 1, 'getStartPage()');
        equals(pager.getEndPage(), 9, 'getEndPage()');
        equals(pager.getTotalPages(), 10, 'getTotalPages()');
        ok(pager.hasPrevious(), 'hasPrevious()');
        ok(pager.hasNext(), 'hasNext()');
    });

    test("total=10, current=5, buffer=9", function () {
        var pager = new S.pager(10, 5, 9);
        equals(pager.getCurrentPage(), 5, 'getCurrentPage()');
        equals(pager.getStartPage(), 1, 'getStartPage()');
        equals(pager.getEndPage(), 9, 'getEndPage()');
        equals(pager.getTotalPages(), 10, 'getTotalPages()');
        ok(pager.hasPrevious(), 'hasPrevious()');
        ok(pager.hasNext(), 'hasNext()');
    });

    test("total=10, current=9, buffer=9", function () {
        var pager = new S.pager(10, 9, 9);
        equals(pager.getCurrentPage(), 9, 'getCurrentPage()');
        equals(pager.getStartPage(), 2, 'getStartPage()');
        equals(pager.getEndPage(), 10, 'getEndPage()');
        equals(pager.getTotalPages(), 10, 'getTotalPages()');
        ok(pager.hasPrevious(), 'hasPrevious()');
        ok(pager.hasNext(), 'hasNext()');
    });
})(exports);
