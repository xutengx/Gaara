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

$.ajaxSetup({
    beforeSend:function(request){
        var match = window.document.cookie.match(/(?:^|\s|;)X-XSRF-TOKEN\s*=\s*([^;]+)(?:;|$)/);
        request.setRequestHeader("X-XSRF-TOKEN", match && match[1]);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
        var data = JSON.parse(XMLHttpRequest.responseText);
        console.log(data);
    }
});
(function ($) {
    var _ajax = $.ajax;
    $.ajax = function (opt) {
        var fn = {beforeSend: function (request) {}};
        if (opt.beforeSend)
            fn.beforeSend = opt.beforeSend;
        var _opt = $.extend(opt, {
            beforeSend: function (request) {
                var match = window.document.cookie.match(/(?:^|\s|;)X-XSRF-TOKEN\s*=\s*([^;]+)(?:;|$)/);
                request.setRequestHeader("X-XSRF-TOKEN", match && match[1]);
                fn.beforeSend(request);
            },
            beforeSend: function (request) {
                var match = window.document.cookie.match(/(?:^|\s|;)X-XSRF-TOKEN\s*=\s*([^;]+)(?:;|$)/);
                request.setRequestHeader("X-XSRF-TOKEN", match && match[1]);
                fn.beforeSend(request);
            }
        });
        _ajax(_opt);
    };
})(jQuery);
