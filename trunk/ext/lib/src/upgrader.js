if ( !exports ) var exports = {};
(function(S, undefined) {
// @import console
S.upgrader = {
    /**
     * Parses version string to object.
     * The function first replaces _,- and + with dot . in the version
     * string, then it splits the results with dot, and treats the
     * first number as major version, the sencond number as minor
     * version, and the third number as revision.
     *
     * @param String version The version string to parse
     * @return object A object contains major version, minor version and revision.
     */
    parse_version_string: function (version) {
        if ( typeof(version) != 'string' ) {
            version = String(version);
        }
        version = version.replace(/[-_+]/g, '.');
        var parts = version.split('.');
        return {
            major: parseInt(parts[0]) || 0,
            minor: parseInt(parts[1]) || 0,
            revision: parseInt(parts[2]) || 0
        };
    },

    /**
     * Compares two version number string
     *
     * @param String v1 the first version number
     * @param String v2 the second version number
     * @return returns -1 if the first version is lower than the second,
     *         0 if they are equal, and 1 if the second is lower.
     */
    version_compare: function (v1, v2) {
        if ( v1 && typeof v1 == "object" && ("major" in v1) ) {
        } else {
            v1 = this.parse_version_string(v1);
        }
        if ( v2 && typeof v2 == "object" && ("major" in v2) ) {
        } else {
            v2 = this.parse_version_string(v2);
        }
        var part = ["major", "minor", "revision"];
        for ( var i=0; i<3; i++ ) {
            if ( v1[part[i]] < v2[part[i]] ) {
                return -1;
            } else if ( v1[part[i]] > v2[part[i]] ) {
                return 1;
            }
        }
        return 0;
    },

    /**
     * @param string old_version
     * @param string new_version
     * @param object settings
     *     - after: callback function when after installing or upgrading
     *     - install: callback function when installing(old_version is undefined or 0)
     *     - upgrade: an associates array which key is a
     *       version string, and value is a callback function
     *       which will be called if it large than old_version.
     *       for example, if old_version = "1.1.0", and settings is
     *           {
     *             "1.0.0": function () {},
     *             "1.0.9": function () {},
     *             "1.1.0": function () {},
     *             "1.2.0": function () {},
     *             "1.3.1": function () {}
     *           }
     *       the function with version "1.2.0" and "1.3.1" will be called.
     */
    run: function (old_version, new_version, settings) {
        var upgrades = settings.upgrade || {};
        var console = S.console || { debug: function () {} };
        old_version = this.parse_version_string(old_version);
        new_version = this.parse_version_string(new_version);
        if ( this.version_compare(old_version, 0) == 0 && typeof settings.install == "function") {
            // install
            settings.install(old_version, new_version);
        } else {
            for ( var ver in upgrades ) {
                if ( upgrades.hasOwnProperty(ver) ) {
                    var version = this.parse_version_string(ver);
                    if ( this.version_compare(version, old_version) == 1
                         && this.version_compare(version, new_version) <= 0 ) {
                        // old_version < ver <= new_version
                        upgrades[ver](old_version, new_version);
                    }
                }
            }
        }
        if ( this.version_compare(old_version, new_version) != 0 && typeof settings.after == "function" ) {
            settings.after(old_version, new_version);
        }
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
