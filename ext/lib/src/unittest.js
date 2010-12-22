if ( !unittest ) var unittest = {};
(function(win, undefined) {
    var getCurrentScript = function() {
        var scripts = win.document.getElementsByTagName('script');
        return scripts[scripts.length - 1].src;
    };

    var validTest = function( name ) {
        var config = QUnit.config;
	    var i = config.filters.length, pos, run = false;

	    if ( !i ) {
		    return true;
	    }

	    while ( i-- ) {
		    var filter = config.filters[i],
			not = filter.charAt(0) == '!';

		    if ( not ) {
			    filter = filter.slice(1);
		    }
            if ( (pos =filter.indexOf(':')) != -1 ) {
                filter = filter.substr(0, pos);
            }

		    if ( name.indexOf(filter) !== -1 ) {
			    run = !not;
                break;
		    }
	    }

	    return run;
    };
    
    unittest.init = function(options) {
        if ( !options ) options = {};
        if ( options.lib_path ) unittest.lib_path = options.lib_path;
        unittest.tests_path = options.tests_path || getCurrentScript().replace(/^(.*\/tests\/).*$/i, '$1');
    };

    unittest.tests_path = '';
    unittest.lib_path = getCurrentScript().replace(/\w+\.js$/i, '');

    unittest.TestSuite = function(tests, loader_config) {
        this.tests = tests;
        this.loader_config = loader_config;
    };

    unittest.TestSuite.prototype.run = function() {
        var loader_config = this.loader_config || {};
        if ( !loader_config.AllTestCases ) loader_config.AllTestCases = { requires: [] };
        var valid_tests = loader_config.AllTestCases.requires;
        for ( var name in this.tests ) {
            if ( this.tests.hasOwnProperty(name) && validTest(name) ) {
                var conf = this.tests[name] || {};
                loader_config[name+'Test'] = {
                    fullpath: conf.fullpath || unittest.tests_path + name + 'Test.js',
                    requires: conf.requires || [name]
                };
                valid_tests.push(name+'Test');
            }
        }
        KISSY.add(loader_config);
        KISSY.use("AllTestCases");
    };
})(window);
