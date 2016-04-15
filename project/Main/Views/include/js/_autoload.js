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
    }
});
$.fn.extend({
    submitData:function(method,callback) {
        var $this = $(this);
        $.getScriptWithCache("Main/Views/include/js/submitData.js", function(){
            $this.submitData_base(method,callback);
        });
    },
    copy: function (obj, callback) {
        var $this = $(this);
        $.getScriptWithCache("Main/Views/include/js/ZeroClipboard.min.js", function(){
            $this.copy_base(obj,callback);
        });
    }
});