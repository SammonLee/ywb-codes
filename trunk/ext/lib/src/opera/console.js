if ( !exports ) var exports = {};
(function(S, undefined){
    var console = S.console = {};
    var LEVEL_NAMES = ["TRACE", "DEBUG", "INFO", "WARN", "ERROR"];
    var LEVELS = {};
    for ( var i=0; i<LEVEL_NAMES.length; i++ ) LEVELS[LEVEL_NAMES[i]] = i;
    var level = LEVELS.WARN;
    console.setLevel = function(l) {
        if ( l in LEVELS ) {
            level = LEVELS[l];
        }
    };
    console.getLevel = function() {
        return LEVEL_NAMES[level];
    };
    console.log = function(msg) {
        log(msg);
    };
    console.trace = function(msg) {
        log(msg, LEVELS.TRACE);
    };
    console.debug = function(msg) {
        log(msg, LEVELS.DEBUG);
    };
    console.info = function(msg) {
        log(msg, LEVELS.INFO);
    };
    console.warn = function(msg) {
        log(msg, LEVELS.WARN);
    };
    console.error = function(msg) {
        log(msg, LEVELS.ERROR);
    };

    var log = function(msg, l) {
        if ( typeof l=="undefined" ) {
            opera.postError(msg);
        } else if ( l >= level ) {
            var date = new Date();
            var pad = function(aNumber) {
                return ((aNumber < 10) ? "0" : "") + aNumber;
            };

            msg = date.getFullYear() + "-" + pad(date.getMonth() + 1) + "-" + pad(date.getDate()) + " "
                + pad(date.getHours()) + ":" + pad(date.getMinutes()) + ":" + pad(date.getSeconds())
                + " [" + LEVEL_NAMES[l] + "]" + msg;
            if ( l == LEVELS.TRACE ) {
                try {
                    i.dont.exist+=0; //doesn't exist- that's the point
                } catch (e) {
                    if (e.stack) { //Firefox
                        var callstack = [];
                        callstack = e.stack.split('\n');
                        callstack.splice(0, 2);
                        msg += "\n" + callstack.join("\n");
                    }
                }
            }
            opera.postError(msg);
        }
    };
})(exports);
