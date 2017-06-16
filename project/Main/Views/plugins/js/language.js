;jQuery.extend({
    language_base:function(key){
        language = (typeof($.language) === "undefined") ? 0 : $.language;       // 默认键一 (中文)
        language_json = (typeof($.language_json) === "undefined") ? {} : $.language_json;
        if(typeof(key) === "object"){
            try{
                return key[language];
            }catch(e){
                try{
                    return key[0];
                }catch(ee){
                    console.log(ee.message);
                }
            }
        }
        // 检查 language 是否存在
        try{
            return this.language_json[key][language];
        }catch(e){
            try{
                return this.language_json[key][0];
            }catch(ee){
                return key;
                console.log(ee.message);
            }
        }
    }
});