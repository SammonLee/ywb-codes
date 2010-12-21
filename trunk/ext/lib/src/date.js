/**
 * date.js - Format date using template string
 * Copyright (c) 2010 Ookong Ltd. Co. (ookong.com).
 *
 * SYNOPSIS
 *  var today = new exports.date();
 *  alert(today);   // display "Thu Jul 29 2010 16:06:19"
 *
 * DESCRIPTION
 * Borrow from Date Format 1.2.2 (http://blog.stevenlevithan.com/archives/date-time-format)
 */

if ( !exports ) var exports = {};

(function(S, undefined){
/**
 * Create date object
 *
 * @param Date date the date to format
 * @param string mask the format string
 * @param bool utc whether using getUTCXXX function in stead of getXXX
 */
S.date = function (date, mask, utc) {
    this.setDate(date);
    this.setUTC(utc);
    this.setMask(mask);
};

S.date.prototype = {
    // Some common format strings
    masks: {
	    "default":      "ddd mmm dd yyyy HH:MM:ss",
	    shortDate:      "m/d/yy",
	    mediumDate:     "mmm d, yyyy",
	    longDate:       "mmmm d, yyyy",
	    fullDate:       "dddd, mmmm d, yyyy",
	    shortTime:      "h:MM TT",
	    mediumTime:     "h:MM:ss TT",
	    longTime:       "h:MM:ss TT Z",
	    isoDate:        "yyyy-mm-dd",
	    isoTime:        "HH:MM:ss",
	    isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	    isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
    },

    // Internationalization strings
    i18n: {
	    dayNames: [
	        "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
	        "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
	    ],
	    monthNames: [
	        "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
	        "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	    ]
    },

    setDate: function (date) {
        // Passing date through Date applies Date.parse, if necessary
        date = date ? new Date(date) : new Date();
        if (isNaN(date)) {
            throw new SyntaxError("invalid date");
        }
        this.date = date;
    },

    getDate: function () {
        return this.date;
    },

    setMask: function (mask) {
        mask = String(this.masks[mask] || mask || this.masks["default"]);
        // Allow setting the utc argument via the mask
        if (mask.slice(0, 4) == "UTC:") {
	        mask = mask.slice(4);
	        this.setUTC(true);
        }
        this.mask = mask;
    },

    getMask: function () {
        return this.mask;
    },

    setUTC: function (utc) {
        this.utc = !!utc;
    },

    getUTC: function () {
        return this.utc;
    },

    /**
     * Format date to string
     *
     * mask format rule:
     *  - ''    or "" to quote literal string
     *  - d     day of month without padding
     *  - dd    day of month with padding
     *  - ddd   abbreviated weekday name
     *  - dddd  full weekday name
     *  - m     month
     *  - mm    month with pading
     *  - mmm   abbreviated month name
     *  - mmmm  full month name
     *  - yy    year with 2 digit
     *  - yyyy  year
     *  - h     hour in 12 hours without padding
     *  - hh    hours in 12 hours with padding
     *  - H     hour in 24 hours without padding
     *  - HH    hour in 24 hours with padding
     *  - M     minute without padding
     *  - MM    minute with padding
     *  - s     second without padding
     *  - ss    second with padding
     *  - Z     timezone
     *  - o     timezone hour
     */
    toString : function () {
	    // Regexes and supporting functions are cached through closure
        var token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
        timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
        timezoneClip = /[^-+\dA-Z]/g;

        var pad = function (val, len) {
            val = String(val);
            len = len || 2;
            while (val.length < len) val = "0" + val;
            return val;
        };


	    var	_ = this.utc ? "getUTC" : "get",
	    d = this.date[_ + "Date"](),
	    D = this.date[_ + "Day"](),
	    m = this.date[_ + "Month"](),
	    y = this.date[_ + "FullYear"](),
	    H = this.date[_ + "Hours"](),
	    M = this.date[_ + "Minutes"](),
	    s = this.date[_ + "Seconds"](),
	    L = this.date[_ + "Milliseconds"](),
	    o = this.utc ? 0 : this.date.getTimezoneOffset(),
	    flags = {
	        d:    d,
	        dd:   pad(d),
	        ddd:  this.i18n.dayNames[D],
	        dddd: this.i18n.dayNames[D + 7],
	        m:    m + 1,
	        mm:   pad(m + 1),
	        mmm:  this.i18n.monthNames[m],
	        mmmm: this.i18n.monthNames[m + 12],
	        yy:   String(y).slice(2),
	        yyyy: y,
	        h:    H % 12 || 12,
	        hh:   pad(H % 12 || 12),
	        H:    H,
	        HH:   pad(H),
	        M:    M,
	        MM:   pad(M),
	        s:    s,
	        ss:   pad(s),
	        l:    pad(L, 3),
	        L:    pad(L > 99 ? Math.round(L / 10) : L),
	        t:    H < 12 ? "a"  : "p",
	        tt:   H < 12 ? "am" : "pm",
	        T:    H < 12 ? "A"  : "P",
	        TT:   H < 12 ? "AM" : "PM",
	        Z:    this.utc ? "UTC" : (String(this.date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
	        o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
	        S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
	    };

	    return this.mask.replace(token, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
