<?php
use \app\core\Config;
use \app\helpers\Html;
?>

(function(){
    var head = document.head || document.getElementsByTagName('head')[0],
        body = document.body || document.getElementsByTagName('body')[0],
        link = document.createElement("link"),
        wrap = document.createElement('div'),
        xhr = new XMLHttpRequest(),
        windowSelector = '#perezvonok_widget',
        perezvonokWidget = {
            selector: windowSelector,
            triggers:[],
            widget: null,
            ordered: false,
            panelsManager: {
                triggerSelector: '.trigger',
                panels:[],
                currentTrigger: null,
                currentPanel: null,
                init: function(){
                    this.currentTrigger = document.querySelector(this.triggerSelector + ' .active');
                    var triggers = document.querySelectorAll(this.triggerSelector);
                    for(var i = 0; i < triggers.length; i++){
                        var idp = triggers[i].getAttribute('data-p');
                        if(idp) {
                            triggers[i].addEventListener(triggers[i].getAttribute('data-e') || 'click', this.update);
                            triggers[i].setAttribute('data-id', i+1);
                            this.panels.push(new Panel(idp, i+1));
                        }
                        triggers[i].removeAttribute('data-p');
                        triggers[i].removeAttribute('data-e');
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
                update: function(){
                    deleteClass(perezvonokWidget.panelsManager.currentTrigger, 'active')
                    if(perezvonokWidget.panelsManager.currentPanel){
                        perezvonokWidget.panelsManager.currentPanel.hide();
                    }
                    perezvonokWidget.panelsManager.currentPanel = perezvonokWidget.panelsManager.getPanelById(this.getAttribute('data-id'));
                    perezvonokWidget.panelsManager.currentPanel.show();
                    perezvonokWidget.panelsManager.currentTrigger = this.querySelector('div');
                    addClass(perezvonokWidget.panelsManager.currentTrigger, 'active');
                }
            },
            init: function(){
                var i = 0, elem = null;
                this.triggers = [
                    new Trigger('', '.btn_perezvonok_widget', 'click', function(){ perezvonokWidget.show(); }),
                    new Trigger(windowSelector, '.window_bg', 'click', function(){ perezvonokWidget.hide(); }),
                    new Trigger(windowSelector, '.close_arrow', 'click', function(){ perezvonokWidget.hide(); })
                ];
                this.panelsManager.init();
                this.widget = document.querySelector(this.selector);
                for(; i < this.triggers.length; i++){
                    if(this.triggers[i].element){
                        this.triggers[i].element.addEventListener(this.triggers[i].event,this.triggers[i].action);
                    }
                }
            },
            show: function(){
                addClass(this.widget, 'open');
            },
            hide: function(){
                deleteClass(this.widget, 'open');
            }
        };

    link.href = "<?php echo Config::URL_ROOT . Html::ActionPath('widget', 'testcss');?>";
    link.rel = "stylesheet";
    head.appendChild(link);

    wrap.innerHTML = <?php echo json_encode($this->Get('html')); //echo json_encode($this->Get('html')); ?>;
    body.appendChild(wrap);

    window.onload = function(){

        perezvonokWidget.init();

        /*ajax(xhr, 'GET',"<?php echo Config::URL_ROOT . Html::ActionPath('widget', 'ajax');?>",function(content){
            console.log(content);
        },
        function(content){
            console.error(content);
        }
    );*/

    }

    function ajax(xhr,method,url,success,error){
        xhr.open(method,url);
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4){
                if(xhr.status == 200){
                    success(xhr.responseText);
                }
                else{
                    error(xhr.responseText);
                }
            }
        }
        xhr.send();
    }

    function Panel(idElement, id){
        this.id = id;
        this.element = document.querySelector('#' + idElement);
        if(hasClass(this.element, 'active_panel')){
            perezvonokWidget.panelsManager.currentPanel = this;
        }
    }

    Panel.prototype.show = function () {
        addClass(this.element, 'active_panel');
    };

    Panel.prototype.hide = function () {
        deleteClass(this.element, 'active_panel');
    };

    function Trigger(wrappS, elementS, event, action){
        this.event = event;
        this.action = action;
        this.element = document.querySelector(wrappS + ' ' + elementS);
        console.log(wrappS + ' ' + elementS);
        console.log(this.element);
    }

    function hasClass(element, className) {
        var rx = new RegExp('(?:^| )' + className + '(?: |$)');
        return rx.test(element.className);
    }

    function addClass(element, className){
        element.className += ' ' + className;
    }

    function deleteClass(element, className){
        element.className = element.className.replace(new RegExp('(?:^|\\s)'+ className + '(?:\\s|$)'), ' ').trim();
    }

})();