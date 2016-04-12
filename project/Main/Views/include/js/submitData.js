$.fn.extend({
    submitData:function(method,callback) {
        $(this).click(function(){
            var form = $(this).parents('form');
            var fd = new FormData();
            var checkBox = {};
            var input = form.find('input');
            input.each(function(i){
                switch( this.type ){
                    case 'text':
                    case 'password':
                        fd.append(this.name, this.value);
                        break;
                    case 'radio':
                        if( $(this).prop('checked') )
                            fd.append(this.name, $(this).filter(':checked').val());
                        break;
                    case 'file':
                        if(typeof($(this)[0].files[0]) != 'undefined')
                            fd.append(this.name, $(this)[0].files[0]);
                        break;
                    case 'checkbox':
                        if(!checkBox.hasOwnProperty(this.name)) checkBox[this.name] = [];
                        if($(this).prop('checked'))
                            checkBox[this.name].push($(this).filter(':checked').val());
                        break;
                    default : break;
                }
            });
            for (var i in checkBox){
                fd.append(i, checkBox[i]);
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
                url:__url__(method),
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