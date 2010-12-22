(function(S, undefined) {
    S.file_contents_get = function(path, callback, type) {
        var xhr = new XMLHttpRequest;
        xhr.open("GET", path, true);
        xhr.onreadystatechange = function() {
            if ( this.readyState == 4 ) {
                var ret;
                if ( type == "xml" ) {
                    ret = this.responseXML;
                } else if ( type == "text" ) {
                    ret = this.responseText;
                } else {
                    try {
                        ret = JSON.parse(this.responseText);
                    } catch ( e ) {
                    }
                }
                callback(ret, this);
            }
        };
        xhr.send(null);
    };

    var responses = [];
    var ajax_mocked = false;
    var mock_ajax = function () {
        S.ajax.mock = function() {
            if ( ajax_mocked ) {
                return;
            }
            var original = S.ajax;
            S.ajax = function(settings) {
                if ( responses.length ) {
                    S.console && S.console.debug("GET: " + settings.url);
                    var response = responses.shift();
                    var error_handler = settings.error || S.ajax.error_handler;
                    if ( typeof response.status == "undefined" ) {
                        response.status = 200;
                    }
                    var callback = function(body) {
                        var xhr = { status: response.status, readyState: 4 };
                        xhr.responseText = body;
                        if ( settings.dataType == "xml" ) {
                            xhr.responseXML = (new DOMParser()).parseFromString(body, "text/xml");
                        }
                        if ( response.status == 200 ) {
                            if ( settings.dataType  && settings.dataType == "xml" ) {
                                settings.success( xhr.responseXML, xhr.status, xhr );
                            } else if ( settings.dataType && settings.dataType == "text" ) {
                                settings.success( xhr.responseText, xhr.status, xhr );
                            } else {
                                try {
                                    settings.success( JSON.parse(xhr.responseText), xhr.status, xhr );
                                } catch (e) {
                                    if ( error_handler ) error_handler( xhr, xhr.status, e );
                                }
                            }
                        } else {
                            if ( error_handler ) { error_handler(xhr, response.status, response.error); }
                        }
                    };
                    if ( response.file ) {
                        S.file_contents_get(response.file, callback, 'text');
                    } else {
                        setTimeout(function() {
                            callback(response.body)
                        }, 10);
                    }
                    if ( response.callback ) {
                        response.callback(settings);
                    }
                } else {
                    return original(settings);
                }
            };
            S.extend(S.ajax, original);
            ajax_mocked = true;
        };

        S.ajax.setResponse = function(response) {
            S.ajax.mock();
            responses = response instanceof Array ? response : [ response ];
        }
        
        S.ajax.addResponse = function(response) {
            responses = [];
            S.ajax.setResponse(response);
        };
    };
    if ( typeof S.ajax == "undefined" ) {
        KISSY.use("util", mock_ajax);
    } else {
        mock_ajax();
    }

    var storage = {};
    S.mockStorage = {
        data: storage,
        getItem: function (key) {
            return storage[key];
        },

        setItem: function (key, val) {
            return storage[key] = val;
        },

        hasItem: function (key) {
            return key in storage;
        },

        removeItem: function (key) {
            delete storage[key];
        },

        each: function (callback) {
            for ( var key in storage ) {
                callback(key, storage[key]);
            }
        },

        clear: function() {
            storage = {};
        },

        get length () {
            var count = 0;
            this.each(function () {count++;});
            return count;
        }
    };
})(exports);
