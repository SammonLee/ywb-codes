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

    asyncTest("notfound", 2, function() {
        S.ajax.setResponse({
            body: 'not found',
            status: '404',
            callback: function(settings) {
                equal(settings.url, 'http://www.example.com');
            }
        });
        S.ajax({
            url: 'http://www.example.com',
            error: function(xhr, status, error) {
                equal(status, '404', 'status match');
                start();
            }
        });
    });

    asyncTest("multiple_ajax", 2, function() {
        S.ajax.setResponse([{
            body: 'hello'
        }, {
            file: 'fixtures/simple.xml'
        }]);
        S.ajax({
            url: 'http://www.example.com',
            dataType: 'text',
            success: function(responseText) {
                equal(responseText, 'hello');
                S.ajax({
                    url: 'http://www.example.com',
                    dataType: 'xml',
                    success: function(responseXML) {
                        ok(responseXML instanceof Document);
                        start();
                    }
                });
            }
        });
    });
})(exports);
