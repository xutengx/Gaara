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
        // 去除左侧指定字符
        String.prototype.ltrim = function (char) {
            if (char) {
                return this.replace(new RegExp('^\\' + char + '+', 'g'), '');
            } 
            return this;
        };
        var p = serialize(param);
        // HOST 会在 控制器display中赋值
        return HOST + pathInfo.ltrim('/') + (lock ? "?" : "") + p;
    }
});