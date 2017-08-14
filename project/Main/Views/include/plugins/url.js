jQuery.extend({
    url_base: function (pathInfo, param) {
        var lock = false;
        var serialize = function (obj, prefix, key) {
            var str = [], p;
            for (p in obj) {
                lock = true;
                if (obj.hasOwnProperty(p)) {
                    var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
                    str.push((v !== null && typeof v === "object") ?
                            serialize(v, k, true) :
                            encodeURIComponent(k) + "=" + encodeURIComponent(v));
                }
            }
            return str.join("&");
        };
        var p = serialize(param);
        return HOST + pathInfo + (lock ? "?" : "") + p;
    }
});