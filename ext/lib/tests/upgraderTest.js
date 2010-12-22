(function (S, undefined) {
    // @import upgrader
    module("upgrader");
    
    test("parse_version_string", function () {
        same(S.upgrader.parse_version_string("1"), {major:1, minor:0, revision: 0}, "version=1");
        same(S.upgrader.parse_version_string("1.2"), {major:1, minor:2, revision: 0}, "version=1.2");
        same(S.upgrader.parse_version_string("1.2.3"), {major:1, minor:2, revision: 3}, "version=1.2.3");
        same(S.upgrader.parse_version_string("1.2a"), {major:1, minor:2, revision: 0}, "version=1.2a");
        same(S.upgrader.parse_version_string("1.2.3b"), {major:1, minor:2, revision: 3}, "version=1.2.3b");

        same(S.upgrader.parse_version_string("1.2_3"), {major:1, minor:2, revision: 3}, "version=1.2_3");
        same(S.upgrader.parse_version_string("1.2-3"), {major:1, minor:2, revision: 3}, "version=1.2-3");
        same(S.upgrader.parse_version_string("1.2+3"), {major:1, minor:2, revision: 3}, "version=1.2+3");

        same(S.upgrader.parse_version_string(1.1), {major:1, minor:1, revision: 0}, "version=1.1");
        same(S.upgrader.parse_version_string("a"), {major:0, minor:0, revision: 0}, "version=a");
        same(S.upgrader.parse_version_string(undefined), {major:0, minor:0, revision: 0}, "version=undefined");
        same(S.upgrader.parse_version_string(null), {major:0, minor:0, revision: 0}, "version=null");
        same(S.upgrader.parse_version_string({}), {major:0, minor:0, revision: 0}, "version is object");
    });

    test("version_compare", function(){
        equals(S.upgrader.version_compare("1.2.3", "3.2.1"), -1, "1.2.3 vs 3.2.1", "major version big");
        equals(S.upgrader.version_compare("1.0", "1.1"), -1, "1.0 vs 1.1", "minor version big");
        equals(S.upgrader.version_compare("1.0.3", "1.0.4"), -1, "1.0.3 vs 1.2.0", "revision big");

        equals(S.upgrader.version_compare("3.2.1", "1.2.3"), 1, "1.2.3 vs 3.2.1", "major version big");
        equals(S.upgrader.version_compare("1.1", "1.0"), 1, "1.0 vs 1.1", "minor version big");
        equals(S.upgrader.version_compare("1.0.4", "1.0.3"), 1, "1.0.3 vs 1.2.0", "revision big");

        equals(S.upgrader.version_compare("1.2.3", "1.2.3"), 0, "1.2.3 vs 1.2.3", "equal version");

        equals(S.upgrader.version_compare(S.upgrader.parse_version_string("1.2.3"), "1.2.3"), 0, "v1 is parsed version");
        equals(S.upgrader.version_compare("1.2.3", S.upgrader.parse_version_string("1.2.3")), 0, "v2 is parsed version");

        equals(S.upgrader.version_compare(undefined, "0"),0, "undefined = 0");
        equals(S.upgrader.version_compare(null, "0"),0, "undefined = 0");
        equals(S.upgrader.version_compare({}, "0"),0, "undefined = 0");
    });

    test("upgrade install", function() {
        var settings = {
            after: function() {
                ok(true, "upgrade");
            },
            install: function() {
                ok(true, "install");
            },
            upgrade: {
                "0.9": function() {
                    ok(false, "0.9");
                }
            }
        };
        S.upgrader.run(undefined, "1.0", settings);
        S.upgrader.run(null, "1.0", settings);
    });

    test("upgrade upgrade", function() {
        S.upgrader.run("1.1.0", "1.3.0", {
            after: function() {
                ok(true, "upgrade");
            },
            install: function() {
                ok(false, "no install");
            },
            upgrade: {
                "1.1.0": function() {
                    ok(false, "1.1.0");
                },
                "1.1.1": function() {
                    ok(true, "1.1.1");
                },
                "1.2.0": function(){
                    ok(true, "1.2.0");
                }
            }
        });

        S.upgrader.run("1.2.0", "2.3.0", {
            upgrade: {
                "2.0": function() {
                    ok(true, "2.0")
                }
            }
        });
    });
})(exports);
