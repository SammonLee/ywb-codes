(function(S, undefined) {
    // @import sha256
    module("sha256");

    test("hash", function() {
        equals(S.sha256.hash("abc"), "ba7816bf8f01cfea414140de5dae2223b00361a396177a9cb410ff61f20015ad", "hash");
        equals(S.sha256.hmac("secret key", "abc"), "549b5df5db986338ab0e122cdba905d12b50aa3a274403c795b6a6c48b7bcb35", "hmac");

        equals(S.sha256.hash("abc", true), "\xba\x78\x16\xbf\x8f\x01\xcf\xea\x41\x41\x40\xde\x5d\xae\x22\x23\xb0\x03\x61\xa3\x96\x17\x7a\x9c\xb4\x10\xff\x61\xf2\x00\x15\xad", "hash with raw output");
        equals(S.sha256.hmac("secret key", "abc", true), "\x54\x9b\x5d\xf5\xdb\x98\x63\x38\xab\x0e\x12\x2c\xdb\xa9\x05\xd1\x2b\x50\xaa\x3a\x27\x44\x03\xc7\x95\xb6\xa6\xc4\x8b\x7b\xcb\x35", "hmac with raw output");

        same(S.sha256.string_to_array("abc"), [97, 98, 99], "string_to_array");
        equals(S.sha256.array_to_hex_string([97, 98, 99]), "616263", "array_to_hex_string");
        equals(S.sha256.array_to_hex_string([14, 15, 16]), "0e0f10", "array_to_hex_string");
    });
})(exports);
