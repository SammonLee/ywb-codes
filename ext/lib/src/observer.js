if ( !exports ) var exports = {};
(function(S, undefined){
S.observer = {
    listeners: {},

    addListener: function(topic, listener) {
        if ( !this.listeners.hasOwnProperty(topic) ) {
            this.listeners[topic] = [];
        }
        this.listeners[topic].push(listener);
    },

    removeListener: function(topic, listener) {
        if ( this.listeners.hasOwnProperty(topic) ) {
            var listeners = this.listeners[topic];
            for (var i=listeners.length; i--; ) {
                if ( listeners[i] === listener ) {
                    listeners.splice(i, 1);
                }
            }
        }
    },

    dispatch: function(topic, event) {
        var listeners = this.listeners[topic];
        if ( typeof listeners == "undefined" ) {
            return;
        }
        for (var i=0; i<listeners.length; i++) {
            if ( listeners[i](event) === false  ) {
                break;
            }
        }
    }
};
})(exports);

var EXPORTED_SYMBOLS = ["exports"];
