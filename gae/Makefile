PACKAGE = ookong001
PERLRUN = perl
PROJECTDIR = $(CURDIR)
build.dir = ${PROJECTDIR}/build

build: manifest
	$(PERLRUN) "-MExtUtils::Manifest=manicopy,maniread" -e "manicopy(maniread(),'${build.dir}', 'best');"

clean:
	rm -f MANIFEST
	rm -rf build/

manifest:
	rm -f MANIFEST
	$(PERLRUN) "-MExtUtils::Manifest=mkmanifest" -e mkmanifest

upload:
	perl -i -pne 's/^version: (\d+)/"version: ".($$1+1)/e' app.yaml
	cp -f app.yaml build/
	appcfg.py update build/

.PHONY: clean manifest build
