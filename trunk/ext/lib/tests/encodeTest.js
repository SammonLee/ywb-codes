(function(S, undefined){
    // @import encode
    module("encode");
    test("decode", function () {
        var gb = S.encode.gb2312;
        equals(encodeURIComponent(gb.decode("%D6%D0")), "%E4%B8%AD");
        equals(encodeURIComponent(gb.decode("%CA%B2%C3%B4=what")), "%E4%BB%80%E4%B9%88%3Dwhat");
        equals(encodeURIComponent(gb.decode("what=%CA%B2%C3%B4")), "what%3D%E4%BB%80%E4%B9%88");
    });

    test("encode", function () {
        var gb = S.encode.gb2312;
        equals(gb.encode(decodeURIComponent("%E4%B8%AD")), "%D6%D0");
        equals(gb.encode(decodeURIComponent("%E4%BB%80%E4%B9%88%3Dwhat")), "%CA%B2%C3%B4=what");
        equals(gb.encode(decodeURIComponent("what%3D%E4%BB%80%E4%B9%88")), "what=%CA%B2%C3%B4");
    });
})(exports);
