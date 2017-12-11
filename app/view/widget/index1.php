<?php
use \app\core\Config;
use \app\helpers\Html;
?>

(function(s){var w,f={},o=window,l=console,m=Math,z='postMessage',x='HackTimer.js by turuslan: ',v='Initialisation failed',p=0,r='hasOwnProperty',y=[].slice,b=o.Worker;function d(){do{p=0x7FFFFFFF>p?p+1:0}while(f[r](p));return p}if(!/MSIE 10/i.test(navigator.userAgent)){try{s=o.URL.createObjectURL(new Blob(["var f={},p=postMessage,r='hasOwnProperty';onmessage=function(e){var d=e.data,i=d.i,t=d[r]('t')?d.t:0;switch(d.n){case'a':f[i]=setInterval(function(){p(i)},t);break;case'b':if(f[r](i)){clearInterval(f[i]);delete f[i]}break;case'c':f[i]=setTimeout(function(){p(i);if(f[r](i))delete f[i]},t);break;case'd':if(f[r](i)){clearTimeout(f[i]);delete f[i]}break}}"]))}catch(e){}}if(typeof(b)!=='undefined'){try{w=new b(s);o.setInterval=function(c,t){var i=d();f[i]={c:c,p:y.call(arguments,2)};w[z]({n:'a',i:i,t:t});return i};o.clearInterval=function(i){if(f[r](i))delete f[i],w[z]({n:'b',i:i})};o.setTimeout=function(c,t){var i=d();f[i]={c:c,p:y.call(arguments,2),t:!0};w[z]({n:'c',i:i,t:t});return i};o.clearTimeout=function(i){if(f[r](i))delete f[i],w[z]({n:'d',i:i})};w.onmessage=function(e){var i=e.data,c,n;if(f[r](i)){n=f[i];c=n.c;if(n[r]('t'))delete f[i]}if(typeof(c)=='string')try{c=new Function(c)}catch(k){l.log(x+'Error parsing callback code string: ',k)}if(typeof(c)=='function')c.apply(o,n.p)};w.onerror=function(e){l.log(e)};l.log(x+'Initialisation succeeded')}catch(e){l.log(x+v);l.error(e)}}else l.log(x+v+' - HTML5 Web Worker is not supported')})('<?php echo Config::URL_JS;?>/HackTimerWorker.min.js');

(function(){
    var head = document.head || document.getElementsByTagName('head')[0],
        body = document.body || document.getElementsByTagName('body')[0],
        link = document.createElement("link"),
        wrap = document.createElement('div'),
        widgetOpenTypes = <?php echo json_encode(Config::WIDGET_OPEN_TRIGGER_TYPE);?>,
        system = {
            init: function(){
                this.dom.init();
                this.ajax.init();
            },
            cookie: {
                get: function(name) {
                    var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
                    return matches ? decodeURIComponent(matches[1]) : undefined;
                },
                set: function(name, value){
                    var date = new Date;
                    date.setDate(date.getDate() + 7);
                    document.cookie = name + '=' + value + '; path=/; expires=' + date.toUTCString();
                }
            },
            dom: {
                init: function(){
                    if (typeof window.addEventListener === "function") {
                        this.addListener = function (e, type, fn) {
                            e.addEventListener(type, fn, false);
                        };
                        this.removeListener = function (e, type, fn) {
                            e.removeEventListener(type, fn, false);
                        }
                    }
                    else if (typeof document.attachEvent === "function") {
                        this.addListener = function (e, type, fn) {
                            e.attachEvent("on" + type, fn);
                        };
                        this.removeListener = function (e, type, fn) {
                            e.detachEvent("on" + type, fn);
                        }
                    }
                    else {
                        this.addListener = function (e, type, fn) {
                            e["on" + type] = fn;
                        };
                        this.removeListener = function (e, type, fn) {
                            e["on" + type] = null;
                        }
                    }
                },
                addListener: null,
                removeListener: null,
                hasClass: function(element, className){
                    var rx = new RegExp('(?:^| )' + className + '(?: |$)');
                    return rx.test(element.className);
                },
                addClass: function(element, className){
                    if(!this.hasClass(element, className)){
                        element.className += ' ' + className;
                    }
                },
                deleteClass: function(element, className){
                    if(this.hasClass(element, className)){
                        element.className = element.className.replace(new RegExp('(?:^|\\s)'+ className + '(?:\\s|$)'), ' ').trim();
                    }
                },
                toggleClass: function(element, className){
                    if(this.hasClass(element, className)){
                        this.deleteClass(element, className);
                    }
                    else{
                        this.addClass(element, className);
                    }
                },
                getParentByClass: function(element, className){
                    var curElement = element;
                    while (curElement && ! this.hasClass(curElement, className)){
                        curElement = curElement.parentElement;
                    }
                    return curElement;
                }
            },
            ajax: {
                httpCodes: {
                    success : 200,
                    notFound : 404
                },
                xhr: null,
                userData: null,
                init: function(){
                    var xmlhttp;
                    try{
                        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                    }
                    catch(e){
                        try{
                            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch(E){
                            xmlhttp = false;
                        }
                    }
                    if(!xmlhttp && typeof XMLHttpRequest!='undefined'){
                        xmlhttp = new XMLHttpRequest();
                    }
                    this.xhr = xmlhttp;
                    this.userData = new DataWorker();
                },
                get: function(success,error){
                    this.send('GET',system.ajax.userData.data.action, success, error, null);
                },
                post: function(success,error){
                    var url = system.ajax.userData.data.action;
                    delete system.ajax.userData.data.action;
                    this.send('POST',url,success,error,system.ajax.userData.getJSON());
                },
                send: function(method,url,success,error, data){
                    console.log(data);
                    this.xhr.open(method,url);
                    this.xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    system.dom.addListener(this.xhr, 'readystatechange', function(e){
                        if(e.target.readyState === e.target.DONE){
                            if(e.target.status === system.ajax.httpCodes.success){
                                success(e.target.responseText);
                            }
                            else{
                                error(e.target.responseText);
                            }
                        }
                    });
                    if(data){
                        this.xhr.send('json_data=' + data);
                    }
                    system.ajax.userData.clear();
                }
            }
        },
        windowSelector = '#perezvonok_widget',
        perezvonokWidget = {
            status: null,
            selector: windowSelector,
            triggers:[],
            widget: null,
            ordered: false,
            trackClosing: <?php echo $this->Get('trackClosing');?>,
            autoStart: <?php echo $this->Get('autoStart');?>,
            autoStartTimer: <?php echo $this->Get('autoStartTimer');?>,
            autoStartCountPage: <?php echo $this->Get('countPage');?>,
            templateEngine: {
                selector: '.template_control',
                symbol: '_',
                controls: [],
                init: function(){
                    var i, elements = perezvonokWidget.widget.querySelectorAll(this.selector);
                    for(i = 0; i < elements.length; i++){
                        this.controls.push(new TemplateControl(elements[i], i));
                    }
                },
                remove: function(e) {
                    if(e.keyCode == 8 || e.keyCode == 46){
                        var id = this.getAttribute('data-id'),element, val = this.value, i;
                        if(id !== undefined) {
                            element = perezvonokWidget.templateEngine.getElementById(id);
                            for(i = element.map.length - 1; i >= 0; i--){
                                if(val[element.map[i]] !== perezvonokWidget.templateEngine.symbol){
                                    this.value = val.substr(0, element.map[i]) + perezvonokWidget.templateEngine.symbol + val.substr(1+element.map[i]);
                                    break;
                                }
                            }
                        }
                        e.preventDefault();
                    }
                },
                update: function(e){
                    var val = this.value,
                        pos = this.value.indexOf('_'),
                        chr;

                        if (pos !== -1) {
                            if (e.ctrlKey || e.altKey || e.metaKey) {
                                return;
                            }
                            chr = getChar(e);
                            if (chr !== null) {
                                if (chr >= '0' && chr <= '9') {
                                    this.value = this.value.replace(perezvonokWidget.templateEngine.symbol, chr);
                                }
                            }
                        }

                    e.preventDefault();
                },
                getElementById: function(id){
                    for(var i = 0; i < this.controls.length; i++){
                        if(this.controls[i].id == id){
                            return this.controls[i];
                        }
                    }
                    return null;
                }
            },
            timer: {
                selector: '.timer p',
                interval: null,
                index: 0,
                element: null,
                start: function(){
                    perezvonokWidget.panelsManager.sleep();
                    perezvonokWidget.timer.element = perezvonokWidget.widget.querySelector('.timer p');
                    perezvonokWidget.timer.interval = setInterval(perezvonokWidget.timer.update, 10);
                },
                update: function(){
                    var time = perezvonokWidget.timer.element.innerText;
                    time = time.split(':');
                    time = time[1].split('.');
                    time[0] = parseInt(time[0],10);
                    time[1] = parseInt(time[1],10);
                    time[1]--;
                    if(time[1] < 0){
                        time[1] = 99;
                        time[0]--;
                        if(time[0] < 0){
                            clearInterval(perezvonokWidget.timer.interval);
                            perezvonokWidget.panelsManager.update(perezvonokWidget.panelsManager.getPanelByIdAttribute('rating').id);
                            perezvonokWidget.timer.element.innerText = '00:25.99'; //todo установить время из настрек
                            perezvonokWidget.panelsManager.wakeUp();
                            return;
                        }
                    }

                    if(time[0].toString().length < 2){
                        time[0] = '0' + time[0];
                    }

                    if(time[1].toString().length < 2){
                        time[1] = '0' + time[1];
                    }

                    perezvonokWidget.timer.element.innerText = '00:' + time[0] + '.' + time[1];
                }
            },
            ratingManager: {
                selector: '.manager_evaluation',
                element: null,
                input: null,
                items: [],
                init: function(){
                    this.element = perezvonokWidget.widget.querySelector(this.selector);
                    if(this.element){
                        this.items = this.element.querySelectorAll('.stars_list > li');
                        this.input = this.element.querySelector('input.mark');
                    }
                },
                update: function(){
                    var current = parseInt(this.getAttribute('data-vote'),10),i;
                    perezvonokWidget.ratingManager.input.value = current;
                    for(i = 0; i < perezvonokWidget.ratingManager.items.length; i++){
                        if(parseInt(perezvonokWidget.ratingManager.items[i].getAttribute('data-vote'),10) <= current){
                            system.dom.addClass(perezvonokWidget.ratingManager.items[i],'active');
                        }
                        else{
                            system.dom.deleteClass(perezvonokWidget.ratingManager.items[i],'active');
                        }
                    }
                },
                clear: function(){
                    perezvonokWidget.ratingManager.input.value = 0;
                    for(i = 0; i < perezvonokWidget.ratingManager.items.length; i++){
                        system.dom.deleteClass(perezvonokWidget.ratingManager.items[i],'active');
                    }
                }
            },
            panelsManager: {
                triggerSelector: '.trigger',
                panels:[],
                domTriggers: [],
                currentTrigger: null,
                currentPanel: null,
                prevPanelId: null,
                annulment: false, // запрет на изменение панели от другого объекта
                init: function(){
                    var domPanels = perezvonokWidget.widget.querySelectorAll('.panels_list .panels'),
                        i, idp, prev;

                    this.domTriggers = perezvonokWidget.widget.querySelectorAll(this.triggerSelector);
                    this.currentTrigger = perezvonokWidget.widget.querySelector(this.triggerSelector + '.active');
                    for(i = 0; i < domPanels.length; i++){
                        this.panels.push(new Panel(domPanels[i], i+1));
                    }

                    for(i = 0; i < this.domTriggers.length; i++){
                        idp = this.domTriggers[i].getAttribute('data-p');
                        prev = system.dom.hasClass(this.domTriggers[i], 'prev');
                        if(idp || prev) {
                            system.dom.addListener(this.domTriggers[i], this.domTriggers[i].getAttribute('data-e') || 'click', this.activateTrigger);
                            if(!prev){
                                this.domTriggers[i].setAttribute('data-id', this.getPanelByIdAttribute(idp).id);
                            }
                        }
                        this.domTriggers[i].removeAttribute('data-p');
                        this.domTriggers[i].removeAttribute('data-e');
                    }
                },
                getPanelById: function(id){
                    for(var i = 0; i < this.panels.length; i++){
                        if(this.panels[i].id == id){
                            return this.panels[i];
                        }
                    }
                    return null;
                },
                getPanelByIdAttribute: function(id){
                    for(var i = 0; i < this.panels.length; i++){
                        if(this.panels[i].getAttributeId() === id){
                            return this.panels[i];
                        }
                    }
                    return null;
                },
                activateTrigger: function(e){
                    var id;
                    e.preventDefault();

                    if(perezvonokWidget.panelsManager.annulment){
                        perezvonokWidget.panelsManager.annulment = false;
                        return;
                    }

                    if(system.dom.hasClass(this, 'sleep')){
                        return
                    }

                    id = this.getAttribute('data-id');
                    if(system.dom.hasClass(this, 'prev')){
                        id = perezvonokWidget.panelsManager.prevPanelId;
                    }

                    system.dom.deleteClass(perezvonokWidget.panelsManager.currentTrigger, 'active')
                    perezvonokWidget.panelsManager.currentTrigger = this;
                    system.dom.addClass(perezvonokWidget.panelsManager.currentTrigger, 'active');

                    perezvonokWidget.panelsManager.update(id);
                },
                update: function(id){
                    perezvonokWidget.panelsManager.prevPanelId = perezvonokWidget.panelsManager.currentPanel.id;
                    if(perezvonokWidget.panelsManager.currentPanel){
                        perezvonokWidget.panelsManager.currentPanel.hide();
                    }
                    perezvonokWidget.panelsManager.currentPanel = perezvonokWidget.panelsManager.getPanelById(id);
                    perezvonokWidget.panelsManager.currentPanel.show();

                },
                sleep: function(){
                    var i;
                    for(i = 0; i < this.domTriggers.length; i++){
                        system.dom.addClass(this.domTriggers[i], 'sleep');
                    }
                },
                wakeUp: function(){
                    var i;
                    for(i = 0; i < this.domTriggers.length; i++){
                        system.dom.deleteClass(this.domTriggers[i], 'sleep');
                    }
                }
            },
            btnManager: {
                selector: '#btn_perezvonok_widget',
                element: null,
                isSlideIcons: <?php echo json_encode($this->Get('slideTextCallBtn') == '1' && $this->Get('formCallBtn') != Config::WIDGET_BTN_RECTANGLE); ?>,
                icons: [],
                init: function(){
                    this.element = document.querySelector(this.selector);
                    if(this.isSlideIcons){
                        if(this.element){
                            this.icons = this.element.querySelectorAll('.icons .icon');
                        }
                        this.slideIcons();
                    }
                },
                slideIcons: function(){
                    setInterval(function(){
                        for(var i = 0; i < perezvonokWidget.btnManager.icons.length; i++){
                            system.dom.toggleClass(perezvonokWidget.btnManager.icons[i], 'active');
                        }
                    }, 5000);
                }
            },
            validator: {
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
                    var name = elem.getAttribute('name'),
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

                    this.container = system.dom.getParentByClass(elem, 'data_control');
                    if(this.container){
                        this.errorBox = this.container.querySelector('.error_box');
                    }
                    this.element = elem;
                    this.value = elem.value;
                    this.errorList = [];

                    return true;
                },
                report: function(){
                    if(this.errorList.length > 0){
                        var str = '', i;
                        for(i = 0; i < this.errorList.length; i++){
                            if(str.length != 0){
                                str += ', ';
                            }
                            str += this.errorList[i];
                        }
                        this.setError(str);
                    }
                    else{
                        this.setSuccess();
                    }
                },
                isValid: function(idForm){
                    var form = perezvonokWidget.widget.querySelector(idForm),
                        elements = form.querySelectorAll('input, textarea, select'),
                        i;

                    for(i = 0; i < elements.length; i++){
                        this.validate(elements[i]);
                    }

                    return form.querySelectorAll('.has_error').length === 0;
                },
                setError: function(msg){
                    system.dom.addClass(this.element, 'has_error');
                    this.errorBox.innerText = msg;
                },
                setSuccess: function(){
                    system.dom.deleteClass(this.element, 'has_error');
                    this.errorBox.innerText = '';
                },
                Phone: function(){
                    var regex = new RegExp(/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/);
                    if(!regex.test(this.value)){
                        this.errorList.push('Некорректный формат номера');
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
                Multiline: function(){

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
            },
            init: function(){
                var i,j, elem = null;
                this.widget = document.querySelector(this.selector);
                this.triggers = [
                    new Trigger('', '.btn_perezvonok', [
                        function(){
                            perezvonokWidget.status = widgetOpenTypes.CLICK_CALL_BTN;
                            perezvonokWidget.show();
                        }
                    ]),
                    new Trigger(windowSelector, '.window_bg', [perezvonokWidget.hide]),
                    new Trigger(windowSelector, '.btn_exit', [perezvonokWidget.hide]),
                    new Trigger(windowSelector, '.day_selector', [this.selectDay]),
                    function(){
                        var count;
                        if(perezvonokWidget.autoStart){
                            count = system.cookie.get('showWidgetCount');
                            count = !count ? 0 : count;
                            if(perezvonokWidget.autoStartCountPage <= count){
                                setTimeout(function(){
                                    perezvonokWidget.status = widgetOpenTypes.AUTO_START;
                                    perezvonokWidget.show();
                                }, perezvonokWidget.autoStartTimer);
                                count = -1;
                            }
                            system.cookie.set('showWidgetCount', ++count);
                        }
                    }
                ];

                this.triggers = this.triggers.concat(factoryTriggers(this.widget,'.dataWorker__set',[this.handlingData]));
                this.triggers = this.triggers.concat(factoryTriggers(this.widget,'.dataWorker__add',[this.handlingData]));
                this.triggers = this.triggers.concat(factoryTriggers(this.ratingManager.element, '.stars_list > li', [this.send,this.ratingManager.update, this.ratingManager.clear]));
                this.triggers = this.triggers.concat(factoryTriggers(this.widget,'.ajax_btn',[this.send]));
                this.triggers = this.triggers.concat(factoryTriggers(this.widget,'.ajax_btn',[this.send]));
                this.triggers = this.triggers.concat(factoryTriggers(this.widget,'input, select, textarea',[function(){perezvonokWidget.validator.validate(this)}]));

                for(i = 0; i < this.triggers.length; i++){
                    switch(typeof this.triggers[i]){
                        case 'object':
                            if(this.triggers[i].element){
                                for(j = 0; j < this.triggers[i].event.length; j++){
                                    if(this.triggers[i].action[j]){
                                        system.dom.addListener(this.triggers[i].element, this.triggers[i].event[j],this.triggers[i].action[j]);
                                    }
                                    else{
                                        system.dom.addListener(this.triggers[i].element, this.triggers[i].event[j],this.triggers[i].action[0]);
                                    }
                                }

                            }
                            break;
                        case 'function':
                            this.triggers[i]();
                            break;
                    }
                }

                this.panelsManager.init();
                this.ratingManager.init();
                this.templateEngine.init();
                this.btnManager.init();

                ouibounce(null, {
                    aggressive: true,
                    timer: 0,
                    oneEvent: false,
                    sensitivity: 100,
                    callback: function() {
                        if(perezvonokWidget.trackClosing){
                            perezvonokWidget.status = widgetOpenTypes.LEFT_WINDOW;
                            perezvonokWidget.show();
                        }
                    }
                });

            },
            show: function(){
                console.log(perezvonokWidget.status);
                system.dom.addClass(perezvonokWidget.widget, 'open');
            },
            hide: function(){
                system.dom.deleteClass(perezvonokWidget.widget, 'open');
            },
            send: function(e){
                e.preventDefault();
                perezvonokWidget.panelsManager.update(perezvonokWidget.panelsManager.getPanelByIdAttribute('preloder').id);
                system.ajax.userData.attachData();
                system.ajax.post(function(data){
                    var obj = JSON.parse(data), elem = '';
                    switch(obj.code){
                        case 200:
                            if(obj.successFunc){
                                perezvonokWidget.response[obj.successFunc](obj.content);
                            }
                            else{
                                console.log(data);
                            }
                            break;
                        case 500:
                            if(obj.errorFunc){
                                eval( obj.errorFunc )(obj.content);
                            }
                            else{
                                console.error(data);
                            }
                    }
                }, function(content){
                    console.error(content);
                });
            },
            handlingData: function(){
                var id = this.getAttribute('data-f');
                if(!id){
                    return;
                }
                id = '#' + id;
                if(!perezvonokWidget.validator.isValid(id)){
                    perezvonokWidget.panelsManager.annulment = true;
                    return;
                }
                if(system.dom.hasClass(this, 'dataWorker__set')){
                    system.ajax.userData.setData(perezvonokWidget.widget, id);
                }
                else{
                    system.ajax.userData.addData(perezvonokWidget.widget, id);
                }
            },
            response: {
                instantCall: function(content){
                    perezvonokWidget.panelsManager.update(perezvonokWidget.panelsManager.getPanelByIdAttribute('timer').id);
                    perezvonokWidget.panelsManager.getPanelByIdAttribute('rating').element.querySelector('input[name="id"]').value = content.id;
                    perezvonokWidget.timer.start();
                },
                managerEvaluation: function(content){
                    var panel = perezvonokWidget.panelsManager.getPanelByIdAttribute('result_success');
                    panel.setTitle('Спасибо за оценку!');
                    perezvonokWidget.panelsManager.update(panel.id);
                },
                callTime: function (content) {
                    var panel = perezvonokWidget.panelsManager.getPanelByIdAttribute('result_success');
                    panel.setTitle('Мы обязательно Вам перезвоним!');
                    perezvonokWidget.panelsManager.update(panel.id);
                },
                sendEmail: function(content){
                    var panel = perezvonokWidget.panelsManager.getPanelByIdAttribute('result_success');
                    panel.setTitle('Ждите ответа в скором времени!');
                    perezvonokWidget.panelsManager.update(panel.id);
                }
            }
        };

    link.href = "<?php echo Config::URL_ROOT . Html::ActionPath('widget', 'testcss', array($this->Get('siteHash')));?>";
    link.rel = "stylesheet";
    head.appendChild(link);

    wrap.innerHTML = <?php echo json_encode($this->Get('html')); //echo json_encode($this->Get('html')); ?>;
    body.appendChild(wrap);

    window.onload = function(){
        system.init();
        perezvonokWidget.init();
    };

    function Panel(element, id){
        this.id = id;
        this.element = element;
        if(system.dom.hasClass(this.element, 'active_panel')){
            perezvonokWidget.panelsManager.currentPanel = this;
        }
    }

    Panel.prototype.setTitle = function(text){
        this.element.querySelector('.title_block').innerText = text;
    };

    Panel.prototype.show = function () {
        system.dom.addClass(this.element, 'active_panel');
    };

    Panel.prototype.hide = function () {
        system.dom.deleteClass(this.element, 'active_panel');
    };

    Panel.prototype.getAttributeId = function () {
        return this.element.getAttribute('id');
    };

    function TemplateControl(element, id){
        this.id = id;
        this.element = element;
        this.element.setAttribute('data-id', id);
        this.createMap();
        system.dom.addListener(element, 'keypress',perezvonokWidget.templateEngine.update);
        system.dom.addListener(element, 'keydown',perezvonokWidget.templateEngine.remove);
    }

    TemplateControl.prototype.createMap = function(){
        var i, val = this.element.value;
        this.map = [];
        for(i = 0; i < val.length; i++){
            if(val[i] === perezvonokWidget.templateEngine.symbol){
                this.map.push(i);
            }
        }
    };

    function Trigger(wrappS, elementS, action){
        this.action = action;
        if(typeof wrappS == 'object'){
            this.element = wrappS;
        }
        else{
            this.element = document.querySelector(wrappS + ' ' + elementS);
        }
        if(this.element){
            this.event = this.element.getAttribute('data-e') || 'click';
            this.event = this.event.split(',');
        }
    }

    function factoryTriggers(container, selector, action){
        var result = [],
            elements = [],
            i;

        if(container === null){
            container = document;
        }

        elements = container.querySelectorAll(selector);

        for(i = 0; i < elements.length; i++){
            result.push(new Trigger(elements[i], '', action));
        }

        return result;
    }


    function DataWorker(){
        this.data = {};
    }

    DataWorker.prototype.setData = function(container, selector){
        this.data = this.getData(container, selector);
    };

    DataWorker.prototype.addData = function(container, selector){
        this.data = Object.assign(this.data, this.getData(container, selector));
    };

    DataWorker.prototype.getData = function(container, selector){
        var elements = [],
            result = {},
            i;

        if(container === null){
            container = document;
        }

        container = container.querySelector(selector);
        elements = container.querySelectorAll('input, select, textarea');

        for(i = 0; i < elements.length; i++){
            result[elements[i].getAttribute('name')] = elements[i].value;
        }

        return result;
    };

    DataWorker.prototype.getJSON = function(){
        return JSON.stringify(this.data);
    };

    DataWorker.prototype.attachData = function(){
        console.log(this);
        this.data = Object.assign(this.data, {
            triggerType: perezvonokWidget.status
        });
    };

    DataWorker.prototype.clear = function(){
        this.data = null;
    };


    function getChar(event) {
    if (event.which === null) {
        if (event.keyCode < 32) return null;
        return String.fromCharCode(event.keyCode)
    }

    if (event.which !== 0 && event.charCode !== 0) {
        if (event.which < 32) return null;
        return String.fromCharCode(event.which);
    }

    return null;
}

    function ouibounce(el, custom_config) {
        "use strict";

        var config     = custom_config || {},
            aggressive   = config.aggressive || false,
            sensitivity  = setDefault(config.sensitivity, 20),
            timer        = setDefault(config.timer, 1000),
            delay        = setDefault(config.delay, 0),
            callback     = config.callback || function() {},
            cookieExpire = setDefaultCookieExpire(config.cookieExpire) || '',
            cookieDomain = config.cookieDomain ? ';domain=' + config.cookieDomain : '',
            cookieName   = config.cookieName ? config.cookieName : 'viewedOuibounceModal',
            sitewide     = config.sitewide === true ? ';path=/' : '',
            _delayTimer  = null,
            _html        = document.documentElement;

        function setDefault(_property, _default) {
            return typeof _property === 'undefined' ? _default : _property;
        }

        function setDefaultCookieExpire(days) {
            // transform days to milliseconds
            var ms = days*24*60*60*1000;

            var date = new Date();
            date.setTime(date.getTime() + ms);

            return "; expires=" + date.toUTCString();
        }

        setTimeout(attachOuiBounce, timer);
        function attachOuiBounce() {
            if (isDisabled()) { return; }

            system.dom.addListener(_html, 'mouseleave', handleMouseleave);
            system.dom.addListener(_html, 'mouseenter', handleMouseenter);
            system.dom.addListener(_html, 'keydown', handleKeydown);
            /*_html.addEventListener('mouseleave', handleMouseleave);
            _html.addEventListener('mouseenter', handleMouseenter);
            _html.addEventListener('keydown', handleKeydown);*/
        }

        function handleMouseleave(e) {
            if (e.clientY > sensitivity) { return; }

            _delayTimer = setTimeout(fire, delay);
        }

        function handleMouseenter() {
            if (_delayTimer) {
                clearTimeout(_delayTimer);
                _delayTimer = null;
            }
        }

        var disableKeydown = false;
        function handleKeydown(e) {
            if (disableKeydown) { return; }
            else if(!e.metaKey || e.keyCode !== 76) { return; }

            disableKeydown = true;
            _delayTimer = setTimeout(fire, delay);
        }

        function checkCookieValue(cookieName, value) {
            return parseCookies()[cookieName] === value;
        }

        function parseCookies() {
            // cookies are separated by '; '
            var cookies = document.cookie.split('; ');

            var ret = {};
            for (var i = cookies.length - 1; i >= 0; i--) {
                var el = cookies[i].split('=');
                ret[el[0]] = el[1];
            }
            return ret;
        }

        function isDisabled() {
            return checkCookieValue(cookieName, 'true') && !aggressive;
        }

        // You can use ouibounce without passing an element
        // https://github.com/carlsednaoui/ouibounce/issues/30
        function fire() {
            if (isDisabled()) { return; }

            if (el) { el.style.display = 'block'; }

            callback();
            disable();
        }

        function disable(custom_options) {
            var options = custom_options || {};

            // you can pass a specific cookie expiration when using the OuiBounce API
            // ex: _ouiBounce.disable({ cookieExpire: 5 });
            if (typeof options.cookieExpire !== 'undefined') {
                cookieExpire = setDefaultCookieExpire(options.cookieExpire);
            }

            // you can pass use sitewide cookies too
            // ex: _ouiBounce.disable({ cookieExpire: 5, sitewide: true });
            if (options.sitewide === true) {
                sitewide = ';path=/';
            }

            // you can pass a domain string when the cookie should be read subdomain-wise
            // ex: _ouiBounce.disable({ cookieDomain: '.example.com' });
            if (typeof options.cookieDomain !== 'undefined') {
                cookieDomain = ';domain=' + options.cookieDomain;
            }

            if (typeof options.cookieName !== 'undefined') {
                cookieName = options.cookieName;
            }

            document.cookie = cookieName + '=true' + cookieExpire + cookieDomain + sitewide;

            // remove listeners
            _html.removeEventListener('mouseleave', handleMouseleave);
            _html.removeEventListener('mouseenter', handleMouseenter);
            _html.removeEventListener('keydown', handleKeydown);
        }

        return {
            fire: fire,
            disable: disable,
            isDisabled: isDisabled
        };
    }

    /*exported ouibounce */
})();