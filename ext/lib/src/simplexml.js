/**
 * simplexml.js - Convert xml to javascript object
 * Copyright (c) 2010 Ookong Ltd. Co. (ookong.com).
 *
 * SYNOPSIS
 *   var xml = "<results found=\"yes\">\n" +
 *   "  <warehouse>\n" +
 *   "    <name>WH3</name>\n" +
 *   "    <phone>2-1121</phone>\n" +
 *   "  </warehouse>\n" +
 *   "  <items count=\"3\">\n" +
 *   "    <item id=\"E1120\" bin=\"AA21\">Desk</item>\n" +
 *   "    <item id=\"E1121\" bin=\"FG03\">Chair</item>\n" +
 *   "    <item id=\"E1122\" bin=\"CD00\">Table</item>\n" +
 *   "  </items>\n" +
 *   "</results>";
 *   var results = exports.simplexml_load_string(xml);
 *   alert(results.warehouse.name);  // "WH3"
 */

if ( !exports ) var exports = {};
(function(S, undefined){
    var newDOMParser = function() {
        if ( typeof DOMParser == "undefined" ) {
            return Components.classes["@mozilla.org/xmlextras/domparser;1"].createInstance(Components.interfaces.nsIDOMParser);
        } else {
            return new DOMParser();
        }
    };

    var DOMNode = typeof Node != "undefined"
        ? Node
        : Components.classes["@mozilla.org/xml/xml-document;1"].createInstance(Components.interfaces.nsIDOM3Node);
/**
 * create simplexml object
 *
 * @param Array attrs
 * @param Array children
 */
S.simplexml = function(attrs, children) {
    this["@attributes"] = attrs;
    this["@children"] = children;
    for ( var prop in children ) {
        this[prop] = children[prop];
    }
};

S.simplexml.prototype = {
    children: function () {
        return this["@children"];
    },

    attributes: function () {
        return this["@attributes"];
    }
};

/**
 * create simplexml object from xml string
 *
 * @param String xml
 * @return object S.simplexml object
 */
S.simplexml_load_string = function(xml){
    var parser = newDOMParser();
    var xmldoc = parser.parseFromString(xml, "text/xml");
    return S.simplexml_import_dom(xmldoc);
};

/**
 * create simplexml object from xml DOM object
 *
 * @param Document xml DOM object
 * @return object S.simplexml object
 */
S.simplexml_import_dom = function(xmldoc) {
    var parseElements = function ( node ) {
        if ( node.nodeType != DOMNode.ELEMENT_NODE ) {
            return null;
        }
        var attrs = {};
        var texts = [];
        var children = {};
        if ( node.attributes.length != 0 ) {
            var att = node.attributes;
            for ( var i=0; i < att.length; i++ ) {
                attrs[att[i].nodeName] = att[i].nodeValue;
            }
        }
        var elems = node.childNodes;
        var has_children = false;
        var name;
        var val;
        for ( var i=0; i < elems.length; i++ ) {
            if ( elems[i].nodeType == DOMNode.TEXT_NODE ) {
                texts.push(elems[i].nodeValue);
            } else if ( elems[i].nodeType == DOMNode.ELEMENT_NODE ) {
                name = elems[i].nodeName;
                val = parseElements(elems[i]);
                has_children = true;
                if ( children.hasOwnProperty(name) ) {
                    if ( children[name] instanceof Array ) {
                        children[name].push(val);
                    } else {
                        children[name] = [children[name], val];
                    }
                } else {
                    children[name] = val;
                }
            }
        }
        if ( has_children ) {
            return new S.simplexml(attrs, children);
        } else {
            return texts[0];
        }
    };
    if ( !xmldoc ) {
        return null;
    }
    if ( xmldoc.element ) {
        return parseElements( xmldoc.element );
    } else if ( xmldoc.documentElement ) {
        return parseElements( xmldoc.documentElement );
    }
    return null;
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
