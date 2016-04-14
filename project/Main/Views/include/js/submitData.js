;$.fn.extend({
    submitData:function(method,callback) {
        method = arguments[0] || 'submitData';
        var url = ( method.indexOf('/') == -1) ? __url__(method) :  method ;
        callback = arguments[1] || function (re) {
                if(re.state == 0 ) alert(re.msg);
                else if(re.state) alert('ok!');
                else console.log(re);
            };
        $(this).click(function(){
            var form = $(this).parents('form');
            var fd = new FormData();
            var inputTextVal = {};
            var inputRadioVal = {};
            var inputCheckboxVal = {};
            var inputObj = form.find('input');
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
                        if(typeof($(this)[0].files[0]) != 'undefined')
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
                if(inputTextVal.hasOwnProperty(i) ){
                    if(inputCheckboxVal[i].indexOf('other') != -1 )
                        inputCheckboxVal[i][inputCheckboxVal[i].indexOf('other')] = inputTextVal[i];
                    delete inputTextVal[i];
                }
                fd.append(i, inputCheckboxVal[i]);
            }
            for (var i in inputRadioVal){
                if (inputTextVal.hasOwnProperty(i)) {
                    if (inputRadioVal[i] == 'other')
                        inputRadioVal[i] = inputTextVal[i];
                    delete inputTextVal[i];
                }
                fd.append(i, inputRadioVal[i]);
            }
            for (var i in inputTextVal){
                fd.append(i, inputTextVal[i]);
            }
            var textarea = form.find('textarea');
            textarea.each(function(i){
                fd.append(this.name, this.value);
            });
            var select = form.find('select');
            select.each(function(i){
                fd.append(this.name, this.value);
            });
            $.ajax({
                url:url,
                data:fd,
                processData: false,
                contentType: false,
                type:'post',
                dataType:'json',
                success:function(re){
                    callback(re);
                }
            })
        });
    }
});