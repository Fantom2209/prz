$(window).on('load', function(){
    $('.ajax-form').on('submit', function(e){
        e.preventDefault();

        var data = $(this).serialize();

        /*if($(this).hasClass('recaptcha')){
            var x = grecaptcha.getResponse();
            //data['g-recaptcha-response'] = (x != '' ? 1 : 0);
            console.log((x != '' ? 1 : 0));
        }*/

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
    /*
    $('.table-view').on('click', '.ajax-link', function(){
        goAjax(
            'GET',
            $(this).attr('data-href'),
            ''
        );
    });

    $('#modal-confirm').on('click', '.ajax-link', function(){
        goAjax(
            'GET',
            $(this).attr('data-href'),
            ''
        );
    });*/

    $('.table-view').on('click', '.confirm', function(){
        $('#modal-confirm a.go').attr('data-href',$(this).attr('data-href'));
    });

    $('.table-view').on('click', '.link-line-action', function(){
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
        alert(item.getAttribute('id'));
    });

});

function goAjax(method, url, data){
    $.ajax({
        type: method,
        url: url,
        data: data,
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

function UpdateProperties(data){
    var form = $('#modalUpdateProperties form');
    $.each(data,function(index,d){
        form.find('input#field_'+ d.id).val(d.value);
    });
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