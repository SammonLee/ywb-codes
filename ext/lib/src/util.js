if ( !exports ) var exports = {};

(function(S, undefined){
    /**
     * copy properties from extended to original
     *
     * @param object original
     * @param object extended
     * @return object original
     */
    S.extend = function(original, extended, overwrite, keys){
        if ( !original || !extended ) return original;
        if ( overwrite === undefined ) overwrite = true;
        var i,p,l;
        if ( keys && (l=keys.length) ) {
            for ( i=0; i<l; i++ ) {
                p = keys[i];
                if ( (p in extended) && (overwrite || !(p in original)) ) {
                    original[p] = extended[p];
                }
            }
        } else {
            for (p in extended) {
                if ( overwrite || !(p in original) ) {
                    original[p] = extended[p];
                }
            }
        }
        return original;
    };

    /**
     * filter int input
     * @param mixed i input
     * @param int def default value
     * @param int min minimum value
     * @param int max maximum value
     * @return int value between min and max
     */
    S.filter_int = function(i, def, min, max) {
        i = parseInt(i);
        if ( isNaN(i)
             || (typeof min != "undefined" && i < min)
             || (typeof max != "undefined" && i > max) ) {
            return def;
        } else {
            return i;
        }
    };

    /**
     * checks whether the object is empty
     *
     * @param object obj The object to check
     * @return bool True if the object has no properties
     */
    S.is_empty = function (obj) {
        if ( typeof obj != "object" ) return !obj;
        for(var prop in obj) {
            if(obj.hasOwnProperty(prop)) {
                return false;
            }
        }
        return true;
    };

    /**
     * checks whether the variable is an array
     * instanceof Array fail in firefox javascript code module
     */
    S.is_array = function ( obj ) {
        return obj && typeof obj == "object" && typeof obj.unshift == "function";
    };

    /**
     * Strip whitespace (or other characters) from the beginning and end of a string
     *
     * @param String str The string that will be trimmed.
     * @return String The trimmed string.
     */
    S.trim = function( str){
	    return ( str || '' ).replace( /^(\s|\u00A0)+|(\s|\u00A0)+$/g, "");
    };

    S.cut_string = function(str, len, tail) {
        if ( typeof tail == "undefined" ) tail = '...';
        if ( str.length > len ) {
            str = str.substr(0, len-tail.length) + tail;
        }
        return str;
    };

    /**
     * generate random string
     * Borrowed from goog/string.js
     */
    S.get_random_string = function() {
        return Math.floor(Math.random() * 2147483648).toString(36) +
            (Math.floor(Math.random() * 2147483648) ^
             (new Date).getTime()).toString(36);
    };

    /**
     * Convert special characters to HTML entities
     *
     * @param String str The string being converted.
     * @return String The converted string.
     */
    S.htmlspecialchars = function(str){
        return (str||'').replace(/&/g, "&amp;") /* must do &amp; first */
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    };

    /**
     * Convert special characters to HTML entities
     *
     * @param String str The string being converted.
     * @return String The converted string.
     */
    S.htmlspecialchars_decode = function(str){
        return (str||'').replace(/&quot;/g, '"')
            .replace(/&#039;/g, "'")
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&amp;/g, '&'); /* must do &amp; last */
    };

    /**
     * remove html tags from string
     * @param String str The string being processed
     * @param Array tags 
     */
    S.strip_tags = function(str, tags) {
        var re;
        if ( typeof tags == "undefined" ) {
            re = '<\\/?\\w+[^>]*>';
        } else {
            if ( typeof tags == "string" ) tags = [tags];
            re = '<\\/?(' + tags.join('|') + ')[^>]*>';
        }
        return str.replace(new RegExp(re, "g"), ' ').replace(/\s+/g, ' ').replace(/<!--[\s\S]+?-->/g, '');
    };

    /**
     * Repeats a string
     *
     * @param String input The string to be repeated.
     * @param int multiplier Number of time the input string should be repeated.
     */
    S.str_repeat = function(i, m) {
	    for (var o = []; m > 0; o[--m] = i) {}
        return o.join('');
    };
	
    /**
     * Return a formatted string
     * Borrowed from http://code.google.com/p/sprintf
     *
     * @param String format The format string is composed of zero or more directives.
     * @param args ...
     * @return String Returns a string produced according to the formatting string format.
     */
    S.sprintf = function() {
	    var i = 0, a, f = arguments[i++], o = [], m, p, c, x, s = '';
	    while (f) {
		    if (m = /^[^\x25]+/.exec(f)) {
			    o.push(m[0]);
		    }
		    else if (m = /^\x25{2}/.exec(f)) {
			    o.push('%');
		    }
		    else if (m = /^\x25(?:(\d+)\$)?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-fosuxX])/.exec(f)) { // '
			    if (((a = arguments[m[1] || i++]) == null) || (a == undefined)) {
				    throw('Too few arguments.');
			    }
			    if (/[^s]/.test(m[7]) && (typeof(a) != 'number')) {
				    throw('Expecting number but found ' + typeof(a));
			    }
			    switch (m[7]) {
			    case 'b': a = a.toString(2); break;
			    case 'c': a = String.fromCharCode(a); break;
			    case 'd': a = parseInt(a); break;
			    case 'e': a = m[6] ? a.toExponential(m[6]) : a.toExponential(); break;
			    case 'f': a = m[6] ? parseFloat(a).toFixed(m[6]) : parseFloat(a); break;
			    case 'o': a = a.toString(8); break;
			    case 's': a = ((a = String(a)) && m[6] ? a.substring(0, m[6]) : a); break;
			    case 'u': a = Math.abs(a); break;
			    case 'x': a = a.toString(16); break;
			    case 'X': a = a.toString(16).toUpperCase(); break;
			    }
			    a = (/[def]/.test(m[7]) && m[2] && a >= 0 ? '+'+ a : a);
			    c = m[3] ? m[3] == '0' ? '0' : m[3].charAt(1) : ' ';
			    x = m[5] - String(a).length - s.length;
			    p = m[5] ? S.str_repeat(c, x) : '';
			    o.push(s + (m[4] ? a + p : p + a));
		    }
		    else {
			    throw('Huh ?!');
		    }
		    f = f.substring(m[0].length);
	    }
	    return o.join('');
    };

    S.http_build_query = function (formdata, numeric_prefix, arg_separator) {
        // Generates a form-encoded query string from an associative array or object.
        //
        // version: 1008.1718
        // discuss at: http://phpjs.org/functions/http_build_query
        // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   improved by: Legaev Andrey
        // +   improved by: Michael White (http://getsprink.com)
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   improved by: Brett Zamir (http://brett-zamir.me)
        // +    revised by: stag019
        // -    depends on: urlencode
        // *     example 1: http_build_query({foo: 'bar', php: 'hypertext processor', baz: 'boom', cow: 'milk'}, '', '&amp;');
        // *     returns 1: 'foo=bar&amp;php=hypertext+processor&amp;baz=boom&amp;cow=milk'
        // *     example 2: http_build_query({'php': 'hypertext processor', 0: 'foo', 1: 'bar', 2: 'baz', 3: 'boom', 'cow': 'milk'}, 'myvar_');
        // *     returns 2: 'php=hypertext+processor&myvar_0=foo&myvar_1=bar&myvar_2=baz&myvar_3=boom&cow=milk'
        var value, key, tmp = [];

        var _http_build_query_helper = function (key, val, arg_separator) {
            var k, tmp = [];
            if (val === true) {
                val = "1";
            } else if (val === false) {
                val = "0";
            }
            if (val !== null && typeof(val) === "object") {
                for (k in val) {
                    if (val[k] !== null) {
                        tmp.push(_http_build_query_helper(key + "[" + k + "]", val[k], arg_separator));
                    }
                }
                return tmp.join(arg_separator);
            } else if (typeof(val) !== "function") {
                return encodeURIComponent(key) + "=" + encodeURIComponent(val);
            } else {
                throw new Error('There was an error processing for http_build_query().');
            }
        };

        if (!arg_separator) {
            arg_separator = "&";
        }
        for (key in formdata) {
            value = formdata[key];
            if (numeric_prefix && !isNaN(key)) {
                key = String(numeric_prefix) + key;
            }
            tmp.push(_http_build_query_helper(key, value, arg_separator));
        }

        return tmp.join(arg_separator);
    };

    S.parse_url = function(str, component) {
        // Parse a URL and return its components
        //
        // version: 1008.1718
        // discuss at: http://phpjs.org/functions/parse_url
        // +      original by: Steven Levithan (http://blog.stevenlevithan.com)
        // + reimplemented by: Brett Zamir (http://brett-zamir.me)
        // %          note: Based on http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
        // %          note: blog post at http://blog.stevenlevithan.com/archives/parseuri
        // %          note: demo at http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
        // %          note: Does not replace invaild characters with '_' as in PHP, nor does it return false with
        // %          note: a seriously malformed URL.
        // %          note: Besides function name, is the same as parseUri besides the commented out portion
        // %          note: and the additional section following, as well as our allowing an extra slash after
        // %          note: the scheme/protocol (to allow file:/// as in PHP)
        // *     example 1: parse_url('http://username:password@hostname/path?arg=value#anchor');
        // *     returns 1: {scheme: 'http', host: 'hostname', user: 'username', pass: 'password', path: '/path', query: 'arg=value', fragment: 'anchor'}
        var  o   = {
            strictMode: false,
            key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
            q:   {
                name:   "queryKey",
                parser: /(?:^|&)([^&=]*)=?([^&]*)/g
            },
            parser: {
                strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
                loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // Added one optional slash to post-protocol to catch file:/// (should restrict this)
            }
        };

        var m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
        uri = {},
        i   = 14;
        while (i--) {uri[o.key[i]] = m[i] || "";}
        // Uncomment the following to use the original more detailed (non-PHP) script
        /*
          uri[o.q.name] = {};
          uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
          if ($1) uri[o.q.name][$1] = $2;
          });
          return uri;
        */

        switch (component) {
        case 'PHP_URL_SCHEME':
            return uri.protocol;
        case 'PHP_URL_HOST':
            return uri.host;
        case 'PHP_URL_PORT':
            return uri.port;
        case 'PHP_URL_USER':
            return uri.user;
        case 'PHP_URL_PASS':
            return uri.password;
        case 'PHP_URL_PATH':
            return uri.path;
        case 'PHP_URL_QUERY':
            return uri.query;
        case 'PHP_URL_FRAGMENT':
            return uri.anchor;
        default:
            var retArr = {};
            if (uri.protocol !== '') {retArr.scheme=uri.protocol;}
            if (uri.host !== '') {retArr.host=uri.host;}
            if (uri.port !== '') {retArr.port=uri.port;}
            if (uri.user !== '') {retArr.user=uri.user;}
            if (uri.password !== '') {retArr.pass=uri.password;}
            if (uri.path !== '') {retArr.path=uri.path;}
            if (uri.query !== '') {retArr.query=uri.query;}
            if (uri.anchor !== '') {retArr.fragment=uri.anchor;}
            return retArr;
        }
    };
    
    /**
     * Parses the query string into variables
     *
     * @param String str The input string
     * @return Array variables in the query string
     */
    S.parse_query = function (str) {
        var glue1 = '=';
        var glue2 = '&';
        var array = {};
        var array2 = (str+'').split(glue2);
        var array2l = 0, tmp = '', x = 0;

        array2l = array2.length;
        for (x = 0; x<array2l; x++) {
            tmp = array2[x].split(glue1);
            array[unescape(tmp[0])] = unescape(tmp[1]).replace(/[+]/g, ' ');
        }
        return array;
    };

    S.newXMLHttpRequest = function () {
        if ( typeof XMLHttpRequest == "undefined" ) {
            return Components.classes["@mozilla.org/xmlextras/xmlhttprequest;1"].createInstance(Components.interfaces.nsIXMLHttpRequest);
        } else {
            return new XMLHttpRequest();
        }
    };

    /**
     * Perform an asynchronous HTTP (Ajax) request.
     *
     * @param object settings A set of key/value pairs that configure the Ajax request.
     *   - contentType When sending data to the server, use this content-type.
     *   - data Data to be sent to the server. It is converted to a query string, if not already a string.
     *   - dataType The type of data that you're expecting back from the server (xml, json, text). Default "json".
     *   - error(XMLHttpRequest, textStatus, errorThrown) A function to be called if the request fails.
     *   - success(data, textStatus, XMLHttpRequest) A function to be called if the request succeeds.
     *   - type The type of request to make ("POST" or "GET"). Default "GET".
     *   - url A string containing the URL to which the request is sent.
     */
    S.ajax = function(settings) {
        // @import console
        var console = S.console || {debug: function() {}};
        settings = S.extend((settings||{}), {type:"GET"}, false);
        if ( typeof settings.url == "undefined" ) {
            return undefined;
        }
        var type = settings.type.toUpperCase();
        if ( settings.data && typeof settings.data != "string") {
            settings.data = S.http_build_query(settings.data);
            if ( type == "GET" ) {
                settings.url += (settings.url.match(/\?/) ? "&" : "?") + settings.data;
                settings.data = null;
            } else {
                settings.contentType = "application/x-www-form-urlencoded";
            }
        }
        var error_handler = settings.error || S.ajax.error_handler;
        var xhr = S.newXMLHttpRequest();
        console.debug(type + ": " + settings.url);
        xhr.open(type, settings.url, true);
        xhr.onreadystatechange = function() {
            if ( this.readyState == 4 ) {
                if ( this.status == 200 ) {
                    if ( settings.dataType  && settings.dataType == "xml" ) {
                        settings.success( this.responseXML, this.status, this );
                    } else if ( settings.dataType && settings.dataType == "text" ) {
                        settings.success( this.responseText, this.status, this );
                    } else {
                        try {
                            settings.success( JSON.parse(this.responseText), this.status, this );
                        } catch (e) {
                            if ( error_handler ) error_handler( this, this.status, e );
                        }
                    }
                } else if ( error_handler ) {
                    error_handler(this, this.status);
                }
            }
        };
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        if ( settings.contentType ) {
            xhr.setRequestHeader("Content-Type", settings.contentType);
        }
        try {
            if ( settings.data ) {
                console.debug("form data: " + settings.data);
            }
            xhr.send(settings.data);
        } catch (e) {
            if ( error_handler ) {
                error_handler(xhr, null, e);
            }
        }
        return xhr;
    };
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
