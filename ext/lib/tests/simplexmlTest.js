(function(S, undefined) {
    // @import simplexml
    // @import(S.extend(S, S.exports);)
    module("simplexml");
    test("load xml", function(){
        var xml = "<results found=\"yes\">\n" +
            "  <warehouse>\n" +
            "    <name>WH3</name>\n" +
            "    <phone>2-1121</phone>\n" +
            "  </warehouse>\n" +
            "  <items count=\"3\">\n" +
            "    <item id=\"E1120\" bin=\"AA21\">Desk</item>\n" +
            "    <item id=\"E1121\" bin=\"FG03\">Chair</item>\n" +
            "  </items>\n" +
            "</results>";
        var results = S.simplexml_load_string(xml);
        same(results.attributes(), { "found" : "yes" }, "attributes");
        equals(results.warehouse.name, "WH3");
        equals(results.warehouse.phone, "2-1121");
        same(results.items.item, [ "Desk", "Chair" ]);
    });

    test("import null", function() {
        equals(S.simplexml_import_dom(null), null, "import null");
    });
})(exports);
