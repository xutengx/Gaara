function url(pathInfo, ps) {
    var host = '';
    var urlEncode = function (param, encode) {
        if (param === null)
            return '';
        var paramStr = '';
        var t = typeof (param);
        if (t === 'string' || t === 'number' || t === 'boolean') {
            paramStr += '&' + '=' + ((encode === null || encode) ? encodeURIComponent(param) : param);
        } else {
            for (var i in param) {
                var k = (param instanceof Array ? '[' + i + ']' : '.' + i);
                paramStr += urlEncode(param[i], k, encode);
            }
        }
        return paramStr;
    };
    return host + pathInfo + urlEncode(ps);
}