(function(S, undefined) {
    module("browser");

    test("name", function() {
        equal(S.browser.name, 'chrome');
    });

    test("extension.getURL", function() {
        ok(S.browser.extension.getURL('foo').match(/^chrome-extension:\/\/\w+\/foo$/));
    });

    asyncTest("tabs.getCurrent", function() {
        S.browser.tabs.getCurrent(function(tab) {
            ok(tab.url.indexOf('chrome-extension://') == 0);
            start();
        });
    });
})(exports);