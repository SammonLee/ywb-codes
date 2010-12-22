(function(S, undefined) {
    module("md5");

    test("md5", function() {
        equals(S.md5.hash('message'), '78e731027d8fd50ed642340b7c9a63b3');
    });

    test("hmac_md5", function() {
        equals(S.md5.hmac('key', 'message'), '4e4748e62b463521f6775fbf921234b5');
    });
})(exports);
