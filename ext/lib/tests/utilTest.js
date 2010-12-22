(function(S, undefined){
    // @import util
    module("util");

    test("extend", function() {
        var o = {a:1};
        var r = S.extend(o, {b: 2});
        same(o, {a:1, b:2});
        equal(o, r);

        S.extend(o, {a: 2});
        same(o, {a:2, b:2}, 'overwrite origal value');

        S.extend(o, {b: 3}, false);
        same(o, {a:2, b:2}, 'not overwrite origal value');

        S.extend(o, {c: 3, d: 4}, true, ["d"]);
        same(o, {a:2, b:2, d: 4}, 'extend with selected keys');
    });

    test("filter_int", function() {
        equal(S.filter_int(undefined, 1), 1, "filter undefined");
        equal(S.filter_int('', 1), 1, "filter \'\'");
        equal(S.filter_int(10.2, 1), 10, "filter 10.2");
        equal(S.filter_int(10, 1), 10, "filter 10");
        equal(S.filter_int('10', 1), 10, "filter \'10\'");

        equal(S.filter_int(10, 20, 20), 20, "filter 10 in [20,)");
        equal(S.filter_int(10, 1, 0, 5), 1, "filter 10 in [0,5])");
    });

    test("get_random_string", function() {
        var r1 = S.get_random_string();
        var r2 = S.get_random_string();
        ok(typeof r1 == 'string' && r1 != r2);
    });

    test("is_empty", function () {
        ok(S.is_empty(0), '0 is empty');
        ok(S.is_empty({}), '{} is empty');
        ok(S.is_empty(null), 'null is empty');
        ok(S.is_empty(undefined), 'undefined is empty');
        ok(S.is_empty(''), '\'\' is empty');

        ok(!S.is_empty(0.01), '0.01 not empty');
        ok(!S.is_empty(1), '1 not empty');
        ok(!S.is_empty('a'), '\'a\' not empty');
        ok(!S.is_empty({a:true}), '{a:true} not empty');
    });

    test("is_array", function() {
        ok(S.is_array([]), 'is_array([])');
        ok(!S.is_array(1), 'is_array(1)');
        ok(!S.is_array(null), 'is_array(null)');
        ok(!S.is_array(undefined), 'is_array(undefined)');
        ok(!S.is_array({}), 'is_array({})');
        ok(!S.is_array(""), 'is_array("")');
        ok(!S.is_array(new Date()), 'is_array(new Date())');
    });

    test("trim", function () {
        equals(S.trim(" \tabc"), "abc", "begin");
        equals(S.trim("abc\n "), "abc", "end");
        equals(S.trim(" \t\nabc\n\t "), "abc", "both");
    });

    test("cut_string", function(){
        equals(S.cut_string("a long long string", 10), "a long ...");
    });
    
    test("sprintf", function() {
        equals( S.sprintf('%.2f', 1), "1.00");
    });

    test("htmlspecialchars", function () {
        equals(S.htmlspecialchars("<a href=\"#\">''</a>"), "&lt;a href=&quot;#&quot;&gt;&#039;&#039;&lt;/a&gt;");
    });

    test("parse_url", function () {
        same(S.parse_url('http://username:password@hostname/path?arg=value#anchor'),
             {scheme: 'http', host: 'hostname', user: 'username', pass: 'password', path: '/path', query: 'arg=value', fragment: 'anchor'});
    });

    test("parse_query", function () {
        same(S.parse_query('first=foo&second=bar'),
             { first: 'foo', second: 'bar' });
        same(S.parse_query('str_a=Jack+and+Jill+didn%27t+see+the+well.'),
             { str_a: "Jack and Jill didn't see the well." });
    });

    test("http_build_query", function () {
        equals(S.http_build_query({foo: 'bar', php: 'hypertext processor', baz: 'boom', cow: 'milk'}, '', '&amp;'),
               'foo=bar&amp;php=hypertext%20processor&amp;baz=boom&amp;cow=milk', "seperator");
        equals(S.http_build_query({0: 'foo', 1: 'bar', 2: 'baz', 3: 'boom', 'php': 'hypertext processor', 'cow': 'milk'}, 'myvar_'),
               "myvar_0=foo&myvar_1=bar&myvar_2=baz&myvar_3=boom&php=hypertext%20processor&cow=milk", "numeric prefix");
        equals(S.http_build_query({foo: 'bar', baz: ''}),
               "foo=bar&baz=", "empty value");
        equals(S.http_build_query({}), '', 'empty');
    });

    test("strip_tags", function() {
        equals(S.strip_tags('<a>text</a>'), 'text');
        equals(S.strip_tags('<a>text</a><span>text2</span>', 'span'), '<a>text</a>text2');

        equals(S.strip_tags("<a\nhref='http://'>\ntext\n</a>\n<span\n>\ntext2</span>", "span"), "<a\nhref='http://'>\ntext\n</a>\n\ntext2");
        equals(S.strip_tags("<a\nhref='http://'>\ntext\n</a>\n<!--xx\nx--><span\n>\ntext2</span>", "span"), "<a\nhref='http://'>\ntext\n</a>\n\ntext2");
    });
})(exports);
