(function(S, undefined) {
    // @import base64
    module("base64");
    test("encode", function () {
        equals(S.base64.encode('eq'), 'ZXE=', "base64 encode");
    });
    test("decode", function () {
        equals(S.base64.decode('ZXE='), 'eq', "base64 decode");
    });
})(exports);
