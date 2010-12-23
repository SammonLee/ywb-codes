window.addEventListener("DOMContentLoaded", function() {
    unittest.init();
    unittest.fixtures_path = unittest.tests_path + 'fixtures/';
    
    var loader_config = {
        base64: { fullpath: unittest.lib_path + 'base64.js' },
        date: { fullpath: unittest.lib_path + 'date.js' },
        encode: { fullpath: unittest.lib_path + 'encode.js' },
        md5: { fullpath: unittest.lib_path + 'md5.js' },
        sha256: { fullpath: unittest.lib_path + 'sha256.js' },
        upgrader: { fullpath: unittest.lib_path + 'upgrader.js' },
        util: { fullpath: unittest.lib_path + 'util.js' },
        set: { fullpath: unittest.lib_path + 'set.js' },
        pager: { fullpath: unittest.lib_path + 'pager.js' },
        PageCache: { fullpath: unittest.lib_path + 'page-cache.js' },
        storage: { fullpath: unittest.lib_path + 'storage.js' },
        cache: { fullpath: unittest.lib_path + 'cache.js', requires: ['storage'] },
        mock: { fullpath: unittest.tests_path + 'mock.js', requires: ['util'] },
        browser: { fullpath: unittest.lib_path + 'chrome/browser.js' },
        AllTests: { requires: ['mock'] }
    };
    var tests = {
        base64: {},
        date: {},
        encode: {},
        md5: {},
        sha256: {},
        upgrader: {},
        set: {},
        util: {},
        storage: {},
        cache: {},
        pager: {},
        pageCache: { requires: ['PageCache'] },
        pageAsyncCache: { requires: ['PageCache'] },
        ajax: { requires: ['util'] },
        browser: {},
        mock: {}
    };
    var suite = new unittest.TestSuite(tests, loader_config);
    suite.run()
}, false);
