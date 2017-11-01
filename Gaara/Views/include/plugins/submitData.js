;jQuery.extend({
    //正则表达式
    regexp_base : {
        "tel": /^((1[3,5,8][0-9])|(14[5,7])|(17[0,6,7,8]))\d{8}$/,
        "email": /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/,
        "phone": /^(0\d{2,3})?-?([2-9]\d{6,7})(-\d{1,5})?$/,
        "hot_line": /^(400|800)-(\d{3})-(\d{4})?$/,
        "qq": /^[1-9]\d{4,9}$/,
        "account": /^[a-zA-Z][a-zA-Z0-9_]{4,17}$/,
        "alpha": /^[a-zA-Z][a-zA-Z0-9_]+$/,
        "md5": /^[a-z0-9]{32}$/,
        "password": /^(.){6,18}$/,
        "money": /^[0-9]+([.][0-9]{1,2})?$/,
        "number": /^\-?[0-9]*\.?[0-9]*$/,
        "numeric": /^\d+$/,
        "url": /^http(s?):\/\/([\w-]+\.)+[\w-]+(\/[\w\- \.\/?%&=]*)?/,
        "cid": /^\d{15}$|^\d{17}(\d|X|x)$/,
        "zip": /^\d{6}$/,
        "address": /^(.){0,64}$/,
        "int": /^[-\+]?\d+$/,
        "float": /^[-\+]?\d+(\.\d+)?$/,
        "letter": /^[A-Za-z]+$/,
        "chinese": /^[\u4E00-\u9FA5]+$/,
        "chinese_name": /^[\u4E00-\u9FA5]{2,5}$/,
        "name": /^[\u4E00-\u9FA5\uf900-\ufa2d\w]+$/,
        "file_name": /^[^\/:*?"<>|,\\]+$/,
        "uuid": /^[a-f0-9]{8}(-[a-f0-9]{4}){3}-[a-f0-9]{12}$/,
        "business_license": /^\d{13}$|^\d{14}([0-9]|X|x)$|^\d{6}(N|n)(A|a|B|b)\d{6}(X|x)$/
    },
    getinfo_base:function(){
        console.log($.regexp_base);
    }
});
$.fn.extend({
    submitData_base:function(method,callback,httpmethod) {
//        method = arguments[0] || 'submitData';
        var url = $.url(method);
        callback = arguments[1] || function (re) {
                if(re.state === 0 ) alert(re.msg);
                else if(re.state) alert('ok!');
                else console.log(re);
            };
        httpmethod = arguments[2] || 'post';
        // 正则校验
        var check = function(obj){
            var rule = obj.attr('rule');
            if(rule) var arr = rule.split('|msg:');
            else return true;
            // 是否存在预定义
            if($.regexp_base[arr[0]]) {
                if (!$.regexp_base[arr[0]].test(obj.val())){
                    arr[1] ? alert(arr[1]) : alert(obj.attr('name')+'不合法!');
                    return false;
                }return true;
            }else{
                var str = arr[0].replace(/\/\//g,"\/");
                try{
                    var re = eval(str);//转成正则
                }catch(e){
                    console.log(e+' 自定义校验规则: '+str+' 无效!');
                    return true;
                }
                if(!re.test(obj.val())){
                    arr[1] ? alert(arr[1]) : alert(obj.attr('name')+'不合法!');
                    return false;
                }return true;
            }
        };
        $(this).click(function(){
            var form = $(this).parents('form');
            var fd = new FormData();
            var inputTextVal = {};
            var inputRadioVal = {};
            var inputCheckboxVal = {};
            var inputObj = form.find('input');
            var state = true;
            inputObj.each(function(i){
                switch( this.type ){
                    case 'text':
                    case 'password':
                        inputTextVal[this.name] = this.value;
                        break;
                    case 'radio':
                        if( $(this).prop('checked'))
                            inputRadioVal[this.name] = $(this).filter(':checked').val();
                        else if( !inputRadioVal.hasOwnProperty(this.name) ) inputRadioVal[this.name]='';
                        break;
                    case 'file':
                        if(typeof($(this)[0].files[0]) !== 'undefined')
                            fd.append(this.name, $(this)[0].files[0]);
                        break;
                    case 'checkbox':
                        if(!inputCheckboxVal.hasOwnProperty(this.name)) inputCheckboxVal[this.name] = [];
                        if($(this).prop('checked'))
                            inputCheckboxVal[this.name].push($(this).filter(':checked').val());
                        break;
                    default : break;
                }
            });
            for (var i in inputCheckboxVal){
                // text与checkbox有相同的name
                if(inputTextVal.hasOwnProperty(i) ){
                    // 此checkbox的val == 'other'
                    if(inputCheckboxVal[i].indexOf('other') !== -1 ) {
                        // 校验
                        if(!check(inputObj.filter('[type=text][name='+i+']'))) return false;
                        // 将text中的val用来替换 checkbox原值other
                        inputCheckboxVal[i][inputCheckboxVal[i].indexOf('other')] = 'other:'+inputTextVal[i];
                    }
                    // 删除 text中的val
                    delete inputTextVal[i];
                }
                if(inputCheckboxVal[i] === ''){
                    alert('多选项'+i+'不能留空哦!');
                    return false;
                }
                fd.append(i, inputCheckboxVal[i]);
            }
            for (var i in inputRadioVal){
                if (inputTextVal.hasOwnProperty(i)) {
                    if (inputRadioVal[i] === 'other'){
                        // 校验
                        if(!check(inputObj.filter('[type=text][name='+i+']'))) return false;
                        inputRadioVal[i] = 'other:'+inputTextVal[i];
                    }
                    delete inputTextVal[i];
                }
                if (inputRadioVal[i] === '') {
                    alert('单选项' + i + '不能留空哦!');
                    return false;
                }
                fd.append(i, inputRadioVal[i]);
            }
            for (var i in inputTextVal){
                if(!check(inputObj.filter('[type=text][name='+i+']'))) return false;
                fd.append(i, inputTextVal[i]);
            }
            var textarea = form.find('textarea');
            textarea.each(function(i){
                if(!check($(this))) state = false;

                fd.append(this.name, this.value);
            });
            if(state === false) return false;
            var select = form.find('select');
            select.each(function(i){
                fd.append(this.name, this.value);
            });
            // 兼容ajax get
            if('get' === httpmethod){
                var gdata = {};             // 传参数组
                var ij = fd.entries();
                var s = true;
                if(s){
                    var y = ij.next();      // {done:false, value:["k1", "v1"]}
                    s = y.done;             // 是否继续遍历
                    gdata[y.value[0]] = y.value[1];
                }
                $.ajax({
                    url:url,
                    data:gdata,
                    type:httpmethod,
                    dataType:'json',
                    success:function(re){
                        callback(re);
                    }
                });
            }else{
                $.ajax({
                    url:url,
                    data:fd,
                    processData: false,
                    contentType: false,
                    type:httpmethod,
                    dataType:'json',
                    success:function(re){
                        callback(re);
                    }
                });
            }
        });
    }
});