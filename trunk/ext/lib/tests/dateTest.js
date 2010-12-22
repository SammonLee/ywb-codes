(function(S, undefined) {
    // @import date
    module("date");
    test("date", function () {
        var date = new Date(Date.UTC(2010, 0, 1, 0, 0, 0));
        var d = new S.date(date);
        equals(d.toString(), "Fri Jan 01 2010 08:00:00", "local time");
        d.setUTC(true);
        equals(d.toString(), "Fri Jan 01 2010 00:00:00", "utc time");
        d.setMask("shortDate");
        equals(d.toString(), "1/1/10", "shortDate");
        d.setUTC(false);
        d.setMask("yyyy-mm-dd HH:MM:ss");
        equals(d.toString(), "2010-01-01 08:00:00", "mask string");
    });

    test("constructor", function () {
        var date;
        date = new S.date(null, "UTC:yyyy-mm-dd'T'HH:MM:ss.000'Z'");
        ok(date.toString().match(/\d{4}-\d{2}-\d{2}T/), "first is null");
    });
})(exports);
