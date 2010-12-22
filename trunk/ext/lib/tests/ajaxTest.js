(function(S, undefined){
    var ajax_test_api = 'http://api.ookong.com/test';

    module("ajax");
    asyncTest("success json", function(){
        S.ajax({
            url: ajax_test_api,
            data: {
                body: '{"success": true}'
            },
            success: function(data, status, xhr){
                ok(data.success, "success");
                start();
            },
            error: function(xhr, status, error){
                ok(false, 'something wrong');
                start();
            }
        });
    });
    
    asyncTest("success xml", function(){
        S.file_contents_get(unittest.fixtures_path+'simple.xml', function(xml) {
            S.ajax({
                url: ajax_test_api,
                data: {
                    body: xml,
                    headers: {
                        "content-type": "text/xml"
                    }
                },
                success: function(res){
                    ok(res instanceof Document, "success");
                    start();
                },
                error: function(xhr, status, error){
                    ok(false, 'something wrong');
                    start();
                },
                dataType: 'xml'
            });
        }, 'text');
    });

    asyncTest("success text", function(){
        S.ajax({
            url: ajax_test_api,
            data: {
                body: 'hello',
            },
            success: function(res){
                equal(res, 'hello', 'success');
                start();
            },
            error: function(xhr, status, error){
                ok(false, 'something wrong');
                start();
            },
            dataType: 'text'
        });
    });

    asyncTest("fail json invalid", function() {
        S.ajax({
            url: ajax_test_api,
            data: {
                body: 'hello',
            },
            success: function(res){
                ok(false, "something wrong");
                start();
            },
            error: function(xhr, status, error){
                ok(error.toString().match('SyntaxError'), 'json invalid');
                start();
            }
        });
    });

    asyncTest("fail status", function() {
        S.ajax({
            url: ajax_test_api,
            data: {
                status: 404,
                body: 'hello',
            },
            success: function(res){
                ok(false, "something wrong");
                start();
            },
            error: function(xhr, status, error){
                equal(status, 404, 'status');
                start();
            }
        });
    });

    asyncTest("redirect", function() {
        S.ajax({
            url: ajax_test_api,
            data: {
                status: 302,
                headers: {
                    location: ajax_test_api + '?' + S.http_build_query({body: "redirect"})
                },
                body: 'hello',
            },
            success: function(res){
                equal(res, "redirect", "follow redirect");
                start();
            },
            error: function(xhr, status, error){
                ok(false, "something wrong");
                start();
            },
            dataType: 'text'
        });
    });
})(exports);
