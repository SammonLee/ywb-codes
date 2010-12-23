VERSION	   = 1.0.0
PROJECT	   = extlib
PROJECTDIR = $(CURDIR)

build.dir  = $(PROJECTDIR)/build
dist.dir = $(PROJECTDIR)/dist
src.dir = $(PROJECTDIR)/src
tests.dir = $(PROJECTDIR)/tests
linker = $(PROJECTDIR)/tools/link.pl
linker.options = -r $(PROJECT)
test.linker.options = -r $(PROJECT)_test -b firefox
test.modules.dir := /tmp/modules-$(PROJECT).$(shell date | md5sum | cut -c 1-5)

dist.firefox: clean.firefox firefox.reload
	mkdir -p ${dist.dir}
	install -m 0644 install.rdf chrome.manifest ${build.dir}/firefox
	perl -i -pne 's/\$$\{extlib\}\///g' ${build.dir}/firefox/chrome.manifest
	perl -i -pne 's|resource://([^/]+)_test/|resource://$$1/|' ${build.dir}/firefox/chrome/tests/*.js
	cd ${build.dir}/firefox && zip -r extlib.xpi . && mv extlib.xpi ${dist.dir}

build.firefox:
	mkdir -p ${build.dir}/firefox/modules ${build.dir}/firefox/chrome
	cd ${src.dir} && perl $(linker) -b firefox $(linker.options) -d ${build.dir}/firefox/modules *.js firefox/*.js
	cd ${src.dir} && install -m 0644 pager.js page-cache.js ${build.dir}/firefox/chrome 

firefox.reload: build.firefox firefox.test
	rm -rf /tmp/modules-${PROJECT}\.*
	mkdir -p ${test.modules.dir}
	cd ${build.dir}/firefox/modules && install -m 0644 *.js ${test.modules.dir}
	perl -i -pne 's|resource://([^/]+)/|resource://$$1_test/|' ${test.modules.dir}/*.js
	cd ${tests.dir} && install -m 0644 runFirefoxTests.html ${build.dir}/firefox/chrome/tests/runTests.html
	perl -i -pne 's|modules_directory\s*=\s*".*"|modules_directory="${test.modules.dir}"|;s|resource_name\s*=\s*".*"|resource_name="$(PROJECT)_test"|' ${build.dir}/firefox/chrome/tests/runTests.html

firefox.test:
	mkdir -p ${build.dir}/firefox/chrome/tests/assets
	rsync -azC ${tests.dir}/fixtures ${build.dir}/firefox/chrome/tests
	cd ${tests.dir}/assets && install -m 0644 * ${build.dir}/firefox/chrome/tests/assets
	cd ${src.dir} && install -m 0644 unittest.js ${build.dir}/firefox/chrome/tests
	cd ${tests.dir} && perl $(linker) -b firefox $(test.linker.options) -d ${build.dir}/firefox/chrome/tests *.js firefox/*.js
	cd ${build.dir}/firefox/chrome/tests/ && mv firefox-testsuite.js testsuite.js

clean.firefox:
	rm -rf ${build.dir}/firefox
