;String.prototype.temp = function(obj){return this.replace(/\$\w+\$/gi,function(matchs){var returns = obj[matchs.replace(/\$/g, "")];return (returns + "") === "undefined"? "": returns;});};
jQuery.extend({
    urls : [],
    getScriptWithCache:function(url,callback){
        url = $.inpath + url;
        if($.urls.indexOf(url) === -1){
            $.urls.push(url);
            $.ajax({
                url: url,
                type: "GET",
                cache:true,
                async:false,
                success: function(){
                    callback();
                },
                dataType: "script"
            });
        }else callback();
    },
    getCssWithCache: function (css) {
        $("head").append("<link>");
        var dom = $("head").children(":last");
        dom.attr({
            rel: "stylesheet",
            type: "text/css",
            href: $.inpath + css
        });
    },
    getinfo: function () {
        $.getScriptWithCache("submitData.js", function(){
            $.getinfo_base();
        });
    },
    set_language:function(key){
        $.getScriptWithCache("language.js", function(){
            $.language = key;
        });
    },
    set_language_json:function(obj){
        $.language_json = Object.assign(obj, $.language_json);
        return true;
    },
    lw:function(key){
        $.getScriptWithCache("language.js", function(){
            document.write($.language_base(key));
        });
    },
    lr:function(key){
        var temp;
        $.getScriptWithCache("language.js", function(){
            temp = $.language_base(key);
        });
        return temp;
    },
    url:function(pathinfo, param){
        var temp;
        $.getScriptWithCache("url.js", function(){
            temp = $.url_base(pathinfo, param);
        });
        return temp;
    },
});
$.fn.extend({
    submitData:function(method, callback, httpmethod) {
        var $this = $(this);
        $.getScriptWithCache("submitData.js", function(){
            $this.submitData_base(method, callback, httpmethod);
        });
    },
    copy: function (obj, callback) {
        var $this = $(this);
        $.getScriptWithCache("ZeroClipboard.min.js", function(){
            $this.copy_base(obj, callback);
        });
    },
});