$(window).on('load', function(){
    $('.ajax-form').on('submit', function(e){
        e.preventDefault();
        if(!validator.isValid($(this))){
            alert('Исправте ошибки!');
            return;
        }

        var data = '';

        if($(this).hasClass('file-form')){
            data = GetDataFields($(this));
            file = true;
        }
        else{
            data = $(this).serialize();
            file = false;
        }

        goAjax(
            'POST',
            $(this).attr('action'),
            data,
            file
        );
    });

    $('body').on('change', '.with-input', function(){
        var root = $(this).closest('.form-group'),
            input = root.find('input');

        if($(this).val() == input.attr('data-active')){
            input.removeClass('hidden');
            input.focus();
        }
        else{
            input.addClass('hidden');
        }

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

        if(!confirm('Уверены?')){
            return;
        }

        var data = GetDataFields(group);

        goAjax(
            'POST',
            $(this).attr('data-href'),
            data,
            true
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
            name = $(this).attr('name').split(']');
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

    $('body').on('click' , '.add-preloader', function(){
        $($(this).attr('href')).find('form').html('<div class="text-center"><img src="/assets/img/loader.gif"></div>');
    });

    $('.modal .close, .modal .clear-active').on('click', function(){
        $('.active-line').removeClass('active-line');
    });

    $('#modalUpdateProperty, #modalAddProperty').on('change', '.param-add-select', function() {
        var input = $(this).closest('.add-param-wrap').find('.param-add-input');
        if($(this).val() == 'new'){
            input.removeClass('hidden').focus();
        }
        else{
            input.addClass('hidden');
        }
    });

    $('#modalUpdateProperty, #modalAddProperty').on('click', '.param-delete-btn', function(){
        var root = $(this).closest('.input-group'),
            paramName = root.find('.param-name').text().split(':')[0],
            type = root.find('input').attr('data-type'),
            selectLastOption = root.closest('.panel-body').find('.add-param-wrap .param-add-select option[value="new"]');

        if(!confirm('Уверены?')){
            return;
        }

        root.remove();

        selectLastOption.before('<option'+(type ? ' class="' + type + '"' : '')+' value="'+paramName+'">'+paramName+'</option>');
    });

    $('#modalUpdateProperty, #modalAddProperty').on('click', '.param-add-btn', function(){
        var root = $(this).closest('.panel-body'),
            tmp = root.find('.param-tmp').clone(),
            select = root.find('.param-add-select'),
            input = root.find('.param-add-input'),
            param = select.val(),
            name = 'Params',
            type = '',
            newItemType = 'new';

        if(param == newItemType || select.find('option[value="'+param+'"]').hasClass(newItemType)){
            type = newItemType;
            name = type + name;
        }

        if(param == newItemType){
            param = input.val();
            if(param == ''){
                input.focus();
                return;
            }
        }
        else{
            select.find('option[value="'+param+'"]').remove();
            if(select.find('option').length <= 1){
                input.removeClass('hidden');
            }
        }

        tmp.removeClass('hidden').removeClass('param-tmp');
        tmp.find('.param-name').text(param + ':');
        tmp.find('input').attr('name', name + '[String:' + param + ']').attr('data-type', type);
        root.find('.add-param-wrap').before(tmp);
        input.val('');
    });

    $('body').on('change', '.select-type-popup select', function(){
        ShowProperties($(this));
    });

    var validator = {
        container: '',
        element: '',
        value: '',
        rule: '',
        errorBox: '',
        errorList: [],
        empty: true,
        validate: function(elem){
            if(!this.init(elem)){
                return;
            }

            if(!this.empty && this.value.length == 0){
                this.errorList.push('Это поле не может быть пустым');
            }
            else if(this.empty && this.value.length == 0) {
            }
            else{
                this[this.rule]();
            }

            this.report();
        },
        init: function(elem){
            var name = elem.attr('name'),
                validName = name.match(/\[([^\]]*)/);
            this.empty = true;
            if(validName){
                validName = validName[1];
            }
            else{
                validName = name;
            }
            validName = validName.split(':');
            if(validName.length < 2){
                return false;
            }
            this.rule = validName[0].split('_');

            if(this.rule.length > 1){
                switch(this.rule[0]){
                    case 'nn':
                        this.empty = false;
                        break;
                }
                this.rule = this.rule[1];
            }
            else{
                this.rule = this.rule[0];
            }

            if(!this[this.rule]){
                console.log('Некорректное правило валидации!');
                return false;
            }

            this.container = elem.closest('.form-group');
            this.errorBox = this.container.find('.error-box');
            this.element = elem;
            this.value = elem.val();
            this.errorList = [];

            return true;
        },
        report: function(){
            if(this.errorList.length > 0){
                var str = '';
                this.errorList.forEach(function(item){
                    str += item + '<br>';
                });
                this.setError(str);
            }
            else{
                this.setSuccess();
            }
            var form = this.container.closest('form');
            form.find('button.submit').prop('disabled', !this.isValid(form))
        },
        isValid: function(elem){
            return elem.find('.has-error').length == 0;
        },
        setError: function(msg){
            if(!this.container.hasClass('has-error')){
                this.container.addClass('has-error').addClass('has-feedback');
                this.element.after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span><span class="sr-only">(error)</span>');
            }
            this.errorBox.html(msg);
        },
        setSuccess: function(){
            this.container.removeClass('has-error').removeClass('has-feedback');
            this.container.find('.form-control-feedback').remove();
            this.container.find('.sr-only').remove();
            this.errorBox.html('');
        },
        Phone: function(){
            var regex = new RegExp(/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/);
            if(!regex.test(this.value)){
                this.errorList.push('Номер телефона имеет некорректный формат');
            }
        },
        Link: function(){
            var regex = new RegExp(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/, 'i');
            if(!regex.test(this.value)){
                this.errorList.push('Ссылка имеет некорректный формат');
            }
        },
        Email: function(){
            var regex = new RegExp(/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/);
            if(!regex.test(this.value)){
                this.errorList.push('Email имеет некорректный формат');
            }
        },
        Date: function(){
            var regex = new RegExp(/^\d{2}\/\d{2}\/\d{4}$/);
            if(!regex.test(this.value)){
                this.errorList.push('Дата в неверном формате');
            }
        },
        String: function(){

        },
        Number: function(){
            var regex = new RegExp(/^\d*(\.\d{1,})?$/);
            if(!regex.test(this.value)){
                this.errorList.push('Ожидается число');
            }
        },
        Select: function(){

        },
        Color: function(){
            if(!this.value.match(/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/)){
                this.errorList.push('Цвет в неверном формате');
            }
        },
        Multilene: function(){

        },
        Checkbox: function(){

        },
        Timezone: function(){
            var regex = new RegExp(/^[+-]\d{1,2}$/);
            if(!regex.test(this.value)){
                this.errorList.push('Временная зона в неверном формате');
            }
        },
        Image: function() {
        }
    };

    $('#modalUpdateProperties').on('blur', 'input, select, textarea', function(){
        validator.validate($(this));
    });
});

function ShowProperties(elem){
    var root = elem.closest('.group-items');
    if(elem.val() == '0'){
        root.find('.center-popup-property').addClass('hidden');
        root.find('.right-popup-property').removeClass('hidden');
    }
    else{
        root.find('.center-popup-property').removeClass('hidden');
        root.find('.right-popup-property').addClass('hidden');
    }
}

function goAjax(method, url, data, file){
    obj = {
        type: method,
        url: url,
        data: data,
        success: function(data) {
            $('.ajax-form .error-box').text('');
            var obj = JSON.parse(data), elem = '';
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
                        eval( obj.errorFunc )(obj.content);
                    }
                    else{
                        if(obj.content.length >= 1){
                            obj.content.forEach(function(item){
                                elem = $('.ajax-form [name$="'+item.context+'\]"]');
                                if(elem.length == 0){
                                    elem = $('.ajax-form [name="'+item.context+'"]');
                                }
                                elem.closest('div').find('.error-box').text(item.msg);
                            });
                        }
                        else{
                            elem = $('.ajax-form [name$="'+obj.content.context+'\]"]');
                            if(elem.length == 0){
                                elem = $('.ajax-form [name="'+obj.content.context+'"]');
                            }
                            elem.closest('div').find('.error-box').text(obj.content.msg);
                        }
                        alert('Необходимо исправить ошибки!');
                    }
                    break;
            }
        }
    }

    if(file){
        obj.contentType = false;
        obj.processData = false;
    }

    $.ajax(obj);
}

function GetDataFields(elem){
    var data = new FormData(), type = '', val = '', flag;
    elem.find('input, select, textarea').each(function(){
        type = $(this).attr('type');
        flag = true;
        switch(type){
            case 'file':
                val = $(this)[0].files[0];
                break;
            case 'checkbox':
                if($(this).prop("checked")){
                    val = 'on';
                }else{
                    flag = false;
                }
                break;
            default:
                val = $(this).val();
        }
        if(flag){
            data.append($(this).attr('name'), val);
        }
        val = '';
    });
    return data;
}

function ShowCaptchaError(data){
    $('.recaptcha.error-box').text(data);
}

function UpdatePropertiesSuccess(){
    $('#modalUpdateProperties').modal('toggle');
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
    $('#modalUpdateProperties form').html(data);


    $('.number-range').each(function(){
        new Slider('#' + $(this).attr('id'), {
            formatter: function(value) {
                return 'Текущее значение: ' + value;
            }
        });
    });

    $('.dp').datepicker();

    ShowProperties($('.select-type-popup select'));

    $('.factory-fields').each(function(){
       if($(this).attr('data-c') <= $(this).find('.group-items').length){
           $(this).find('.add-factory-item').addClass('hidden');
       }
    });

    $('[type="color"]').spectrum({
            showButtons: false,
            change: function(color) {
                $(this).val(color.toHexString());
            }
        }
    );
}

function EnableProperties(data){
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
    $('#modalUpdateProperty #param-panel').html(data.paramsPanel);
}

function AddProperty(data){
    $('#modalAddProperty #param-panel').html(data.paramsPanel);
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
    AddLine(data,true);
}

function AddLineBottom(data){
    AddLine(data,false);
}

function AddLine(data, top){
    $(".modal").modal('hide');
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

function ErrorAlert(data){
    alert('Ошибка: ' + data);
}