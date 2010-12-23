window.addEventListener("DOMContentLoaded", function() {
    unittest.init();
    unittest.fixtures_path = unittest.tests_path + 'fixtures/';
    unittest.lib_path = unittest.tests_path + '../';

    var loader_config = {
        pager: { fullpath: unittest.lib_path + 'pager.js' },
        PageCache: { fullpath: unittest.lib_path + 'page-cache.js' },
        mock: { fullpath: unittest.tests_path + 'mock.js' },
        AllTestCases: { requires: ['mock'] }
    };
    var tests = {
        base64: { requires: [] },
        date: { requires: [] },
        encode: { requires: [] },
        md5: { requires: [] },
        sha256: { requires: [] },
        upgrader: { requires: [] },
        set: { requires: [] },
        util: { requires: [] },
        storage: { requires: [] },
        cache: { requires: [] },
        pager: { requires: ['pager'] },
        pageCache: { requires: ['PageCache'] },
        pageAsyncCache: { requires: ['PageCache'] },
        ajax: { requires: [] },
        factory: { requires: [] },
        mock: { requires: [] },
        sqliteStorage: { requires: [] },
        firefoxBrowser: { requires: [] }
    };
    var suite = new unittest.TestSuite(tests, loader_config);
    suite.run()
}, false);
