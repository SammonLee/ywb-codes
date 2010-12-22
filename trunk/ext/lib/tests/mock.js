(function(S, undefined) {
    var console = S.console || { debug: function () {} };
    
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
    var original_ajax = S.ajax;
    S.mockAjax = {
        setResponse: function(response) {
            this.setup();
            responses = response instanceof Array ? response : [ response ];
        },
        
        addResponse: function(response) {
            responses = [];
            S.ajax.setResponse(response);
        },

        setup: function() {
            S.ajax = function(settings) {
                if ( responses.length ) {
                    console.debug("GET: " + settings.url);
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
                    return original_ajax.call(S, settings);
                }
            };
        },

        teardown: function() {
            S.ajax = original_ajax;
        }
    };

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
