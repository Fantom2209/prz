$(window).on('load', function(){
    $('.ajax-form').on('submit', function(e){
        e.preventDefault();

        var data = GetDataFields($(this));

        /*var data = new FormData(), type = '', val = '';
        $(this).find('input, select, textarea').each(function(){
            type = $(this).attr('type');
            switch(type){
                case 'file':
                    val = $(this)[0].files[0];
                    break;
                case 'checkbox':
                    if($(this).attr("checked") == 'checked'){
                        val = 'on';
                    }
                    break;
                default:
                    val = $(this).val();
            }

            if(val){
                data.append($(this).attr('name'), val);
            }

            val = '';
        });*/

        goAjax(
            'POST',
            $(this).attr('action'),
            data
        );
    });

    $('body').on('click', '.ajax-link', function(){
        goAjax(
            'GET',
            $(this).attr('data-href'),
            ''
        );
    });

    $('body').on('click', '.delete-group-fields', function(){
        var factory = $(this).closest('.factory-fields'),
            count = factory.find('.group-items').length,
            group = $(this).closest('.group-items'),
            btn = factory.find('.add-factory-item');

        if(count === 1){
            alert('Нельзя удалить последний элемент!');
            return;
        }

        var data = GetDataFields(group);

        goAjax(
            'POST',
            $(this).attr('data-href'),
            data
        );

        group.remove();
        btn.removeClass('hidden');
    });


    $('body').on('click', '.add-factory-item', function(){
        var factory = $(this).closest('.factory-fields'),
            max = factory.attr('data-c'),
            group = factory.find('.group-items'),
            tmp = group.first().clone(),
            name = '',
            time = new Date().getTime();

        if(group.length >= max-1){
            $(this).addClass('hidden');
        }

        tmp.find('input, select, textarea').each(function(){
            type = $(this).attr('type');
            switch(type){
                case 'checkbox':
                    $(this).removeAttr("checked");
                    break;
                default:
                    $(this).val('');
            }

            name = $(this).attr('name').split('-');
            if(name.length > 1){
                $(this).attr('name',name[0] + ']');
            }
            $(this).attr('name').split(']');
            if(name.length > 1){
                $(this).attr('name',name[0] + '@' + time +']');
            }
        });

        $(this).before(tmp);
    });

    $('body').on('click', '.confirm', function(){
        $('#modal-confirm a.go').attr('data-href',$(this).attr('data-href'));
    });

    $('body').on('click', '.link-line-action', function(){
       $(this).closest('tr').addClass('active-line');
    });

    $('.modal .close, .modal .clear-active').on('click', function(){
        $('.active-line').removeClass('active-line');
    });

    $('.dp').datepicker();

    document.querySelectorAll('.number-range').forEach(function(item){
        new Slider('#' + item.getAttribute('id'), {
            formatter: function(value) {
                return 'Текущее значение: ' + value;
            }
        });
    });

});

function goAjax(method, url, data){
    $.ajax({
        type: method,
        url: url,
        data: data,
        processData: false,
        contentType: false,
        success: function(data) {
            $('.ajax-form .error-box').text('');
            var obj = JSON.parse(data);
            switch(obj.code){
                case 200:
                    if(obj.successFunc){
                        eval( obj.successFunc )(obj.content); // todo
                    }

                    break;
                case 301:
                    $(location).attr('href',obj.url);
                    break;
                case 500:
                    if(obj.errorFunc){
                        eval( obj.errorFunc )(obj.content); // todo
                    }
                    else{
                        if(obj.content.length >= 1){
                            obj.content.forEach(function(item){
                                $('.ajax-form [name$="'+item.context+']"]').closest('div').find('.error-box').text(item.msg);
                            });
                        }
                        else{
                            $('.ajax-form [name$="'+obj.content.context+']"]').closest('div').find('.error-box').text(obj.content.msg);
                        }
                    }
                    break;
            }
        }
    });
}

function GetDataFields(elem){
    var data = new FormData(), type = '', val = '';
    elem.find('input, select, textarea').each(function(){
        type = $(this).attr('type');
        switch(type){
            case 'file':
                val = $(this)[0].files[0];
                break;
            case 'checkbox':
                if($(this).attr("checked") == 'checked'){
                    val = 'on';
                    console.log('on');
                }
                break;
            default:
                val = $(this).val();
        }

        if(val){
            data.append($(this).attr('name'), val);
        }

        val = '';
    });
    return data;
}


function ShowCaptchaError(data){
    $('.recaptcha.error-box').text(data);
}

function prepareForm(selector, data){
    var form = $(selector), d = '';
    $.each(data,function(index,value){
        d = index.split('@');
        if(d.length > 1){
            switch(d[0]){
                case 'checkbox':
                    var f = value == '1';
                    form.find('input#field_'+d[1]).attr('checked', f);
                    break;
            }
        }
        else{
            form.find('input#field_'+index+', select#field_'+index).val(value);
        }
    });
}

function ClearTmpFields(form){

}

function UpdateProperties(data){
    $('#modalUpdateProperties form').html(data);

    document.querySelectorAll('.number-range').forEach(function(item){
        new Slider('#' + item.getAttribute('id'), {
            formatter: function(value) {
                return 'Текущее значение: ' + value;
            }
        });
    });

    //console.log(data);

    /*$.each(data,function(index,d){
        if(prev == d.idP){

        }
        else{
            form.find('input#field_'+ d.idP).val(d.value);
        }
        prev = d.idP;
    });*/
}

function EnableProperties(data){
    console.log(data);
    $('#modalEnableProperty form').html(data);
}

function UpdateUser(data){
    prepareForm('#modalUpdateUser form', data[0]);
}

function UpdateSite(data){
    prepareForm('#modalUpdateSite form', data[0]);
}

function AddSite(data){
    prepareForm('#modalAddSite form', data);
}

function UpdateProperty(data){
    prepareForm('#modalUpdateProperty form', data);
}

function UpdateLine(data){
    $('.table-view .active-line').replaceWith(data);
    $(".modal").modal('hide');
}

function DeleteLine(data){
    $('.table-view .active-line').remove();
    $(".modal").modal('hide');
    if($('.table-view tr').length <= 1){
        $('.table-view').addClass('hidden').before('<div class="panel panel-default empty-table">Пока ничего нет...</div>');
    }
}

function AddLineTop(data){
    console.log(data);
    AddLine(data,true);
}

function AddLineBottom(data){
    AddLine(data,false);
}

function AddLine(data, top){
    $(".modal").modal('hide');
    console.log('add');
    var empty = $('.empty-table'),
        view = $('.table-view');
    if(empty.length > 0){
        empty.remove();
        view.removeClass('hidden');
    }
    if(top){
        view.find('tr:first-child').after(data);
    }
    else{
        view.append(data);
    }
}