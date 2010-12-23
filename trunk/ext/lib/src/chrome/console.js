if ( !exports ) var exports = {};

(function(S, undefined){
    var LEVEL_NAMES = ["TRACE", "DEBUG", "INFO", "WARN", "ERROR"];
    var LEVELS = {};
    for ( var i=0; i<LEVEL_NAMES.length; i++ ) LEVELS[LEVEL_NAMES[i]] = i;
    var level = LEVELS.WARN;
    S.console = {
        setLevel: function(l) {
            if ( l in LEVELS ) {
                level = LEVELS[l];
            }
        },
        getLevel: function() {
            return LEVEL_NAMES[level];
        },
        log: function(msg) {
            console.log(msg);
        },
        trace: function(msg) {
            log(msg, LEVELS.TRACE);
        },
        debug: function(msg) {
            log(msg, LEVELS.DEBUG);
        },
        info: function(msg) {
            log(msg, LEVELS.INFO);
        },
        warn: function(msg) {
            log(msg, LEVELS.WARN);
        },
        error: function(msg) {
            log(msg, LEVELS.ERROR);
        }
    };
    var log = function(msg, l) {
        if ( l >= level ) {
            var date = new Date();
            var pad = function(aNumber) {
                return ((aNumber < 10) ? "0" : "") + aNumber;
            };
            msg = date.getFullYear() + "-" + pad(date.getMonth() + 1) + "-" + pad(date.getDate()) + " "
                + pad(date.getHours()) + ":" + pad(date.getMinutes()) + ":" + pad(date.getSeconds())
                + " [" + LEVEL_NAMES[l] + "]" + msg;
            switch ( l ) {
            case LEVELS.trace:
                console.trace(msg); break;
            case LEVELS.DEBUG:
                console.debug(msg); break;
            case LEVELS.INFO:
                console.info(msg); break;
            case LEVELS.WARN:
                console.warn(msg); break;
            case LEVELS.ERROR:
                console.error(msg); break;
            }
        }
    };
})(exports);
