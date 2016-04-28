;String.prototype.temp = function(obj){return this.replace(/\$\w+\$/gi,function(matchs){var returns = obj[matchs.replace(/\$/g, "")];return (returns + "") == "undefined"? "": returns;});};
jQuery.extend({
    urls : [],
    getScriptWithCache:function(url,callback){
        if($.urls.indexOf(url) == -1){
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
    getinfo: function () {
        $.getScriptWithCache($.jsorminjs+"submitData.js", function(){
            $.getinfo_base();
        });
    }
});
$.fn.extend({
    submitData:function(method,callback) {
        var $this = $(this);
        $.getScriptWithCache($.jsorminjs+"submitData.js", function(){
            $this.submitData_base(method,callback);
        });
    },
    copy: function (obj, callback) {
        var $this = $(this);
        $.getScriptWithCache($.jsorminjs+"ZeroClipboard.min.js", function(){
            $this.copy_base(obj,callback);
        });
    }
});