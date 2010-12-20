upload:
	perl -i -pne 's/^version: (\d+)/"version: ".($$1+1)/e' app.yaml
	appcfg.py update . -e wenbinye@ookong.com

.PHONY: upload

