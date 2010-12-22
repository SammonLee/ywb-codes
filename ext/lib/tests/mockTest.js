(function(S, undefined) {
    module("mock");
    asyncTest("file_contents_get", function() {
        S.file_contents_get(unittest.fixtures_path+"simple.xml", function(data) {
            ok( data.match(/^<\?xml/), "get text" );
            start();
        }, "text");
    });

    asyncTest("ajax mock json", function() {
        S.ajax.setResponse({ body: '{"success": true}' });
        S.ajax({
            url: "http://www.google.com?json",
            success: function(data) {
                same(data, {success:true}, "mocked");
                start();
            },
            error: function(xhr, status, error) {
                ok(false, "something wrong");
                start();
            }
        });
    });

    asyncTest("ajax mock text", function() {
        S.ajax.setResponse({
            body: 'hello',
            callback: function(o) {
                equal(o.url, "http://www.google.com?text", "url matches");
            }
        });
        S.ajax({
            url: "http://www.google.com?text",
            success: function(data, status, xhr) {
                equal(data, 'hello', "mocked");
                start();
            },
            error: function(xhr, status, error) {
                ok(false, "something wrong");
                start();
            },
            dataType: "text"
        });
    });

    asyncTest("ajax mock", function() {
        S.ajax.setResponse({ file: unittest.fixtures_path + 'simple.xml' });
        S.ajax({
            url: "http://www.google.com",
            success: function(data) {
                ok(data instanceof Document, "mocked");
                start();
            },
            error: function(xhr, status, error) {
                ok(false, "something wrong");
                start();
            },
            dataType: "xml"
        });
    });

    asyncTest("ajax mock error", function() {
        S.ajax.setResponse({ status: 404 });
        S.ajax({
            url: "http://www.google.com",
            success: function(data) {
                ok(false, "something wrong");
                start();
            },
            error: function(xhr, status, error) {
                equal(status, 404, 'mock error');
                start();
            }
        });
    });
})(exports);
