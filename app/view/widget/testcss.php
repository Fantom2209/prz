<?php
    use \app\core\Config;

    /* кнопка */

    define('SHOW_CALL_BTN', $this->Get('notShowCallBtn') == '0'); // показывать кнопку? (true/false)
    define('FORM_BTN', $this->Get('formCallBtn')); // форма кнопки (0,1,2,3...)
    define('COLOR_BTN', $this->Get('colorCallBtn')); // цвет кнопки (#fff000)
    define('LOCATION_BTN', $this->Get('locationCallBtn')); // расположение кнопки (0,1,2,3..)
    define('BOTTOM_MARGIN_BTN', $this->Get('marginBottomCallBtn') . '%'); // отступ от нижнего края (число в процентах)


    $radius = '0%';
    switch(FORM_BTN){
        case Config::WIDGET_BTN_CIRCLE:
            $radius = '100%';
            break;
    }
?>
<style>
@font-face {
    font-family: "opensans";
    src: url('<?php echo Config::URL_FONT;?>opensans.eot');
    src: url('<?php echo Config::URL_FONT;?>opensans.eot?#iefix')format('embedded-opentype'),
    url('<?php echo Config::URL_FONT;?>opensans.woff') format('woff'),
    url('<?php echo Config::URL_FONT;?>opensans.ttf') format('truetype');
    font-style:normal;
    font-weight:normal;
}

@font-face {
    font-family: "opensanslight";
    src: url('<?php echo Config::URL_FONT;?>opensanslight.eot');
    src: url('<?php echo Config::URL_FONT;?>opensanslight.eot?#iefix')format('embedded-opentype'),
    url('<?php echo Config::URL_FONT;?>opensanslight.woff2') format('woff2'),
    url('<?php echo Config::URL_FONT;?>opensanslight.woff') format('woff'),
    url('<?php echo Config::URL_FONT;?>opensanslight.ttf') format('truetype');
    font-style:normal;
    font-weight:normal;
}

@font-face {
    font-family: 'FontAwesome';
    src: url('<?php echo Config::URL_FONT;?>fontawesome-webfont.eot?v=4.6.4');
    src: url('<?php echo Config::URL_FONT;?>fontawesome-webfont.eot?#iefix&v=4.6.4') format('embedded-opentype'),
    url('<?php echo Config::URL_FONT;?>fontawesome-webfont.woff2?v=4.6.4') format('woff2'),
    url('<?php echo Config::URL_FONT;?>fontawesome-webfont.woff?v=4.6.4') format('woff'),
    url('<?php echo Config::URL_FONT;?>fontawesome-webfont.ttf?v=4.6.4') format('truetype');
    font-weight: normal;
    font-style: normal;
}

.fa{display:inline-block;font:normal normal normal 14px FontAwesome;font-size:inherit;line-height:1;text-rendering:auto;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;font-style:normal!important;}.fa-lg{font-size:1.33333333em;line-height:.75em;vertical-align:-15%}.fa-2x{font-size:2em}.fa-3x{font-size:3em}.fa-4x{font-size:4em}.fa-5x{font-size:5em}.fa-fw{width:1.28571429em;text-align:center}.fa-ul{padding-left:0;margin-left:2.14285714em;list-style-type:none}.fa-ul>li{position:relative}.fa-li{position:absolute;left:-2.14285714em;width:2.14285714em;top:.14285714em;text-align:center}.fa-li.fa-lg{left:-1.85714286em}.fa-border{padding:.2em .25em .15em;border:solid .08em #eee;border-radius:.1em}.fa-pull-left{float:left}.fa-pull-right{float:right}.fa.fa-pull-left{margin-right:.3em}.fa.fa-pull-right{margin-left:.3em}.pull-right{float:right}.pull-left{float:left}.fa.pull-left{margin-right:.3em}.fa.pull-right{margin-left:.3em}.fa-spin{-webkit-animation:fa-spin 2s infinite linear;animation:fa-spin 2s infinite linear}.fa-pulse{-webkit-animation:fa-spin 1s infinite steps(8);animation:fa-spin 1s infinite steps(8)}@-webkit-keyframes fa-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}@keyframes fa-spin{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}.fa-rotate-90{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=1)";-webkit-transform:rotate(90deg);-ms-transform:rotate(90deg);transform:rotate(90deg)}.fa-rotate-180{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=2)";-webkit-transform:rotate(180deg);-ms-transform:rotate(180deg);transform:rotate(180deg)}.fa-rotate-270{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=3)";-webkit-transform:rotate(270deg);-ms-transform:rotate(270deg);transform:rotate(270deg)}.fa-flip-horizontal{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1)";-webkit-transform:scale(-1, 1);-ms-transform:scale(-1, 1);transform:scale(-1, 1)}.fa-flip-vertical{-ms-filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1)";-webkit-transform:scale(1, -1);-ms-transform:scale(1, -1);transform:scale(1, -1)}:root .fa-rotate-90,:root .fa-rotate-180,:root .fa-rotate-270,:root .fa-flip-horizontal,:root .fa-flip-vertical{filter:none}.fa-stack{position:relative;display:inline-block;width:2em;height:2em;line-height:2em;vertical-align:middle}.fa-stack-1x,.fa-stack-2x{position:absolute;left:0;width:100%;text-align:center}.fa-stack-1x{line-height:inherit}.fa-stack-2x{font-size:2em}.fa-inverse{color:#fff}

.fa-phone:before{content:"\f095";font-family:FontAwesome!important;}
.fa-envelope:before{content:"\f0e0";font-family:FontAwesome!important;}
.fa-clock-o:before{content:"\f017";font-family:FontAwesome!important;}
.fa-vk:before {content:"\f189";font-family:FontAwesome!important;}
.fa-times:before {content:"\f00d";font-family:FontAwesome!important;}
.fa-star:before {content:"\f005";font-family:FontAwesome!important;}

#perezvonok_widget.window_wrapper{
    position:fixed;
    width:100%;
    height:100%;
    top:0;
    left:0;
    z-index:2147483000;
    display:none;
}

#perezvonok_widget.open{
    display:block;
}

#perezvonok_widget .window_panel *{
    font-family:"opensans",Arial,Tahoma,Helvetica,sans-serif;
    font-weight:400;
    font-size:inherit;
    color: <?php echo $this->Get('textColor');?>;
    text-shadow:none;
    letter-spacing:normal;
}


#perezvonok_widget .window_panel{
    z-index: 1000000000;
    text-align:center;
    /*background: rgb(242, 242, 242) url("https://perezvonok.ru/css/panel-bg.png") center center;*/
    <?php if(!empty($this->Get('userBgImage'))){ ?>
    background: rgb(242, 242, 242) url("<?php echo $this->Get('userBgImage');?>") center center;
    <?php } ?>
    opacity: <?php echo $this->Get('opacityWindow');?>;
    overflow: hidden;
    transition: 0.2s ease-out;
    -webkit-transition: 0.2s ease-out;
    -moz-transition: 0.2s ease-out;
    -o-transition: 0.2s ease-out;
    box-shadow:0 0 20px 0 rgba(43, 49, 54, 0.5);
}

#perezvonok_widget .window_panel.window_type_1{
    position: relative;
    padding:20px 0 10px 0;
    width:700px;
    height:auto;
    margin-top:-100%;
    border-radius:4px;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
}

#perezvonok_widget .window_panel.window_type_2{
    position: absolute;
    width:320px;
    right:-320px;
    top:0;
    height:100%;
    background-color:#F2F2F2;
}

#perezvonok_widget.open .window_panel.window_type_2 {
    right: 0;
}

#perezvonok_widget.open .window_panel.window_type_1 {
    margin: 5% auto;
}

#perezvonok_widget .window_type_1 .panels{
    padding: 20px 30px 40px 30px;
}

#perezvonok_widget .window_bg{
    position:fixed;
    width:100%;
    height:100%;
    top:0;
    left:0;
    z-index:214748300;
    display:none;
    background-color:<?php echo $this->Get('bgColor');?>;
    opacity: 0.5;
}

#perezvonok_widget.open .window_bg{
    display:block;
}


/* закрыть */

#perezvonok_widget .window_panel .btn_exit{
    position:absolute;
    opacity:0.7;
    cursor: pointer;
    -webkit-transition:0.3s ease-out;
    -moz-transition:0.3s ease-out;
    -o-transition:0.3s ease-out;
    transition:0.3s ease-out
}

#perezvonok_widget .window_type_1 .btn_exit{
    right:26px;
    top:9px;
    height:15px;
    width:15px;
}

#perezvonok_widget .window_type_2 .btn_exit {
    right: 0;
    height: 50px;
    left: 0;
    top: 50%;
    width: 30px;
    margin-top: -60px;
    background: transparent url("<?php echo Config::URL_IMG . '/arrow.png';?>") no-repeat scroll center center;
    z-index: 11111111111111111111111;
    background-size: contain;
}

#perezvonok_widget .window_panel .btn_exit:hover{
    opacity:1;
}

#perezvonok_widget .window_type_1 .btn_exit i{
    font-size:32px;
}

/* закрыть конец */

/* список панелей */

#perezvonok_widget .panels{
    display: none;
}

#perezvonok_widget .panels.active_panel{
    display:block;
}


#perezvonok_widget .window_type_2 .panels{
    padding: 5px 30px 15px 30px;
    position: absolute;
    top: 35%;
}

#perezvonok_widget .panels .title_widget{
    display: <?php echo $this->Get('showTitle') ? 'block' : 'none';?>;
    color: <?php echo $this->Get('textColorTitle');?>;
}

#perezvonok_widget .panels .title_block, #perezvonok_widget .panels span{
    vertical-align: unset;
    font-size: 22px;
    line-height: 34px;
    text-align: center;
    letter-spacing: normal;
}

#perezvonok_widget .window_type_2 .panels .title_block, #perezvonok_widget .window_type_2 .panels span{
    font-size: 18px;
    line-height: 28px;
}

#perezvonok_widget .panels .phone_block input{
    padding: 0 8px;
    display: inline-block;
    border: 1px solid #c2c2c2;
    border-radius: 2px;
    background: #f9f9f9;
    letter-spacing: normal;
    left: auto;
    vertical-align: bottom;
    box-shadow: none;
    text-shadow: none;
    box-sizing: unset;
    line-height: 30px;
    height: 42px;
}

#perezvonok_widget .panels .data_control .has_error{
    border: 1px solid red;
    color: red;
}

#perezvonok_widget .panels .error_box{
    color: darkred;
    font-style: italic;
    font-size: 12px;
}

#perezvonok_widget .panels input::-webkit-input-placeholder{
    color: <?php echo $this->Get('textColor');?>;
}

#perezvonok_widget .panels input::-moz-placeholder{
    color: <?php echo $this->Get('textColor');?>;
}

#perezvonok_widget .panels input:-moz-placeholder{
    color: <?php echo $this->Get('textColor');?>;
}

#perezvonok_widget .panels input:-ms-input-placeholder{
    color: <?php echo $this->Get('textColor');?>;
}

#perezvonok_widget .panels textarea::-webkit-input-placeholder{
    color: <?php echo $this->Get('textColor');?>;
}

#perezvonok_widget .panels textarea::-moz-placeholder{
    color: <?php echo $this->Get('textColor');?>;
}

#perezvonok_widget .panels textarea:-moz-placeholder{
    color: <?php echo $this->Get('textColor');?>;
}

#perezvonok_widget .panels textarea:-ms-input-placeholder{
    color: <?php echo $this->Get('textColor');?>;
}

#perezvonok_widget .window_type_1 .panels .phone_block input{
    width: 170px;
    font-size: 20px;
    margin: 0;
    text-align: left;
}

#perezvonok_widget .window_type_2 .panels input, #perezvonok_widget .window_type_2 .panels textarea, #perezvonok_widget .window_type_2 .panels select, #perezvonok_widget .window_type_1 .panels select{
    width: 235px;
    height: 42px;
    font-size: 16px;
    margin: 10px 0 0 0;
    text-align: center;
}

#perezvonok_widget .window_type_2 .panels .phone_block input{
    font-size: 23px;
}

#perezvonok_widget .window_type_2 .panels textarea{
    height:80px;
}

#perezvonok_widget .panels button{
    background-color: <?php echo $this->Get('callBtnColor');?>; /*#1FB250*/
    display: inline-block;
    float: unset;
    margin: 15px 0 0 0;
    width: 161px;
    height: 44px;
    font-size: 16px;
    border-radius: 2px;
    border: none;
    color: <?php echo $this->Get('callBtnTextColor');?>;
    line-height: 30px;
    cursor: pointer;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
    padding: 0;
    letter-spacing: normal;
    text-indent: 0;
    position: static;
    -webkit-transition: all 120ms ease-in 0s;
    -moz-transition: all 120ms ease-in 0s;
    -o-transition: all 120ms ease-in 0s;
    transition: all 120ms ease-in 0s;
    text-shadow: none;
}

#perezvonok_widget .window_type_1 .panels .phone_block button{
    width: 161px;
}

#perezvonok_widget .window_type_2 .panels .phone_block button{
    width: 250px;
}

#perezvonok_widget .panels .phone_line button:hover {
    opacity: 0.7;
}

#perezvonok_widget .panels .time_block select{
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none;
    -ms-appearance: none;
    background: #fff url("<?php echo Config::URL_IMG;?>selectarrow.jpg") no-repeat right center;
    background-size: 23px 16px;
    cursor: pointer;
    text-shadow: none;
    margin: 16px 0 0 0;
    padding: 1px 4px 2px 4px;
    border: 1px solid #c2c2c2;
    border-radius: 2px;
    box-shadow: none;
    color: inherit;
    display: inline;
    font-family: inherit;

    vertical-align: baseline;
}

#perezvonok_widget .window_type_1 .panels .time_block select{
    width: 168px;
    height: 42px;
    font-size: 20px;
}

#perezvonok_widget .window_type_2 .panels .time_block select{
    width: 115px;
    height: 40px;
    font-size: 15px;
}

#perezvonok_widget .window_type_2 .panels .timer p{
    line-height:30px;
    font-size:46px;
    margin:20px 0 0 0;
    padding:0;
    letter-spacing:normal;
    text-align:center;
    font-weight:100;
}

#perezvonok_widget .window_type_2 .panels .assent {
    margin:3px 3px 3px 2px;
    font-size:10px;
    opacity:0.7;
    text-align:left;
    line-height:20px;
    position:static;
    display:inline-block;
}

#perezvonok_widget .window_type_2 .panels .assent img{
    height:14px;
    margin:3px 5px 0 0;
    width:13px;
    float:left;
}

#perezvonok_widget .window_type_2 .panels .assent .copyright_link{
    text-decoration:none;
    margin:0;
    padding:0;
    font-size:10px;
    opacity:1;
    text-align:left;
    line-height:20px;
}

/* список панелей конец */


#perezvonok_widget .footer .copyright_link {
    opacity:0.4;
    text-decoration:none;
    font-size:13px;
    letter-spacing:normal;
    bottom:15px;
    line-height:26px;
    height:auto;
}

#perezvonok_widget .window_type_2 .footer .copyright_link {
    position: absolute;
    left: 44px;
}


#perezvonok_widget .footer .copyright_link:hover{
    opacity:0.7;
    text-decoration:none;
}


#perezvonok_widget .manager_evaluation{
    display: block;
    margin-top: 10px;
}

#perezvonok_widget .manager_evaluation .stars_list {
    padding-left:0;
    list-style:none;
    margin-top:5px;
}

#perezvonok_widget .manager_evaluation .stars_list li {
    display:inline-block;
    padding:6px;
    line-height:normal;
    cursor:pointer;
    -webkit-transition:all 120ms ease-in 0s;
    -moz-transition:all 120ms ease-in 0s;
    -o-transition:all 120ms ease-in 0s;
    transition:all 120ms ease-in 0s;
    font-size:32px;
}

#perezvonok_widget .manager_evaluation .stars_list li.active i {
    color:coral;
}

#perezvonok_widget .manager_evaluation .msg{
    font-size:16px;
}


#perezvonok_widget .footer label{
    margin:3px 3px 3px 2px;
    font-size:13px;
    opacity:0.7;
    text-align:left;
    line-height:20px;
    position:static;
    color:#333333;
    display:inline-block;
}

#perezvonok_widget .footer img{
    height:14px;
    margin:3px 5px 0 0;
    width:13px;
    float:left;
}

#perezvonok_widget .footer .pz_agr_link{
    text-decoration:none;
    margin:0;
    padding:0;
    font-size:13px;
    opacity:1;
    text-align:left;
    line-height:20px;
}

#perezvonok_widget .logo{
    text-align: center;
}

#perezvonok_widget .window_type_1 .logo img{
    max-width:400px;
    max-height:100px;
    padding:0;
}

#perezvonok_widget .window_type_2 .logo img{
    max-width:100px;
}

/*блоки панели*/

#perezvonok_widget .window_type_1 .panels .switch{
    margin: 20px 60px 0 60px;
    text-align: center;
    position: relative;
    cursor: pointer;
}

#perezvonok_widget .window_type_1 .panels .switch i{

    opacity:0.8;
    font-size:22px;
    padding-bottom:1px;
    line-height:20px;
    vertical-align:bottom;
}

#perezvonok_widget .window_type_1 .panels .switch .title{
    display: inline-block;
    padding: 0 0 0 7px;
}

#perezvonok_widget .window_type_1 .panels .switch .title a{
    border-bottom: 1px dotted;
    font-family: "opensans",Arial,Tahoma,Helvetica,sans-serif!important;
    font-size: 16px;
    text-decoration: none;
}

/*блоки панели конец*/



/* иконки для окна 2*/

#perezvonok_widget .icons_panel .icon_list
{
    float: none;
    list-style: none;
    margin: 18px 12px 12px auto;
    padding: 0;
    text-align: center;
    width: 100%;
}

#perezvonok_widget .icons_panel .icon_list_item
{
    background: none;
    cursor: pointer;
    display: inline-block;
    float: none;
    height: auto;
    line-height: 12px;
    margin: 0 5px;
    padding: 0;
    text-align: center;
    vertical-align: top;
    width: 20%;
}

#perezvonok_widget .icons_panel .icon_list_item > a > div
{
    background: <?php echo $this->Get('colorInactiveTab');?>;
    box-shadow: none;
    cursor: pointer;
    display: block;
    height: 50px;
    width: 50px;
    margin: 0 auto 7px;
    moz-user-select: none;
    opacity: 1;
    padding: 0;
    text-align: center;
    user-select: none;
    webkit-user-select: none;
    <?php if(!$this->Get('squareIcons')) { ?>
        border-radius: 4px;
        moz-border-radius: 4px;
        webkit-border-radius: 4px;
    <?php } ?>
}

#perezvonok_widget .icons_panel .icon_list_item span
{
    display: inherit;
    font: 400 11px/10px "opensans",Arial,Tahoma,Helvetica,sans-serif;
    text-align: center;
    transform: none;
}

#perezvonok_widget .icons_panel .icon_list_item a:hover
{
    text-decoration: none;
}

#perezvonok_widget .icons_panel .icon_list_item a{
    color: #fff;
    margin: 0;
    padding: 0;
    position: relative;
}

#perezvonok_widget .icons_panel .item_link_call i
{
    font-size: 36px;
    line-height: 52px;
}

#perezvonok_widget .icons_panel .item_link_delay i
{
    font-size: 36px;
    line-height: 49px;
}

#perezvonok_widget .icons_panel .item_link_application i
{
    font-size: 29px;
    line-height: 48px;
}

#perezvonok_widget .icons_panel .item_link_consultant_vk i
{
    font-size: 30px;
    line-height: 48px;
}

#perezvonok_widget .icons_panel .icon_list_item .active > div,
#perezvonok_widget .icons_panel .icon_list_item > a > div:hover
{
    background-color: <?php echo $this->Get('activeColorTab');?>;
}

/* иконки для окна 2 конец*/

/* buttons */

#btn_perezvonok_widget {
    position: fixed;
    bottom: <?php echo BOTTOM_MARGIN_BTN; ?>;

    <?php
        switch(LOCATION_BTN){
            case Config::WIDGET_BTN_LOCATION_LEFT:
                echo 'left: 1%;';
                break;
            case Config::WIDGET_BTN_LOCATION_RIGHT:
                echo 'right: 1%;';
                break;
        }
    ?>

    width:200px;
    height:200px;
    z-index:2000;
    transition: opacity 0.8s ease;
    opacity:0.6;
    display: <?php echo SHOW_CALL_BTN ? 'block' : 'none';?>
}

#btn_perezvonok_widget:hover{
    opacity:1;
}

#btn_perezvonok_widget .body{
    text-align:center;
    cursor: pointer;
    position:absolute;

    width: 80px;
    height:80px;
    top:70px;
    left:70px;

    -webkit-border-radius:<?php echo $radius;?>;
    -moz-border-radius:<?php echo $radius;?>;
    border-radius:<?php echo $radius;?>;

    border:2px solid transparent;

    background-color: <?php echo COLOR_BTN;?>;

    -moz-transform-origin: 50% 50%;
    -webkit-transform-origin: 50% 50%;
    -o-transform-origin: 50% 50%;
    -ms-transform-origin: 50% 50%;
    transform-origin: 50% 50%;
}


#btn_perezvonok_widget .icons i{
    color:white;
    font-size:60px;
    line-height:85px;
    display: none;
}

#btn_perezvonok_widget .icons span {
    font-family:"opensans",Arial,Tahoma,Helvetica,sans-serif;
    color:white;
    top:20px;
    position:relative;
    font-size:14px;
    font-weight:bold;
    line-height:18px;
    display: none;
}

#btn_perezvonok_widget .icons .active{
    display: inline;
}

#btn_perezvonok_widget .text{
    text-align: center;
    position: absolute;
    width: 100%;
    top: 20px;
    left: 13px;
    font-weight: bold;
}

/***************** 1 ****************/

#btn_perezvonok_widget.type_1 .body .icons{
    -webkit-animation:lptr-circle-img-anim 2s infinite ease-in-out;
    -moz-animation:lptr-circle-img-anim 2s infinite ease-in-out;
    -ms-animation:lptr-circle-img-anim 2s infinite ease-in-out;
    -o-animation:lptr-circle-img-anim 2s infinite ease-in-out;
    animation:lptr-circle-img-anim 2s infinite ease-in-out;
}

@keyframes lptr-circle-img-anim{
    0%{transform:rotate(0deg) scale(1) skew(1deg)}
    10%{transform:rotate(-25deg) scale(1) skew(1deg)}
    20%{transform:rotate(25deg) scale(1) skew(1deg)}
    30%{transform:rotate(-25deg) scale(1) skew(1deg)}
    40%{transform:rotate(25deg) scale(1) skew(1deg)}
    100%,50%{transform:rotate(0deg) scale(1) skew(1deg)}
}

@-moz-keyframes lptr-circle-img-anim{
    0%{transform:rotate(0deg) scale(1) skew(1deg)}
    10%{-moz-transform:rotate(-25deg) scale(1) skew(1deg)}
    20%{-moz-transform:rotate(25deg) scale(1) skew(1deg)}
    30%{-moz-transform:rotate(-25deg) scale(1) skew(1deg)}
    40%{-moz-transform:rotate(25deg) scale(1) skew(1deg)}
    100%,50%{-moz-transform:rotate(0deg) scale(1) skew(1deg)}}

@-webkit-keyframes lptr-circle-img-anim{
    0%{-webkit-transform:rotate(0deg) scale(1) skew(1deg)}
    10%{-webkit-transform:rotate(-25deg) scale(1) skew(1deg)}
    20%{-webkit-transform:rotate(25deg) scale(1) skew(1deg)}
    30%{-webkit-transform:rotate(-25deg) scale(1) skew(1deg)}
    40%{-webkit-transform:rotate(25deg) scale(1) skew(1deg)}
    100%,50%{-webkit-transform:rotate(0deg) scale(1) skew(1deg)}
}

@-o-keyframes lptr-circle-img-anim{
    0%{-o-transform:rotate(0deg) scale(1) skew(1deg)}
    10%{-o-transform:rotate(-25deg) scale(1) skew(1deg)}
    20%{-o-transform:rotate(25deg) scale(1) skew(1deg)}
    30%{-o-transform:rotate(-25deg) scale(1) skew(1deg)}
    40%{-o-transform:rotate(25deg) scale(1) skew(1deg)}
    100%,50%{-o-transform:rotate(0deg) scale(1) skew(1deg)}
}

#btn_perezvonok_widget.type_1 .circle{
    -moz-transform-origin: 50% 50%;
    -webkit-transform-origin: 50% 50%;
    -o-transform-origin: 50% 50%;
    -ms-transform-origin: 50% 50%;
    transform-origin: 50% 50%;
    width:160px;
    height:160px;
    top:30px;
    left:30px;
    position:absolute;
    background-color:transparent;
    -webkit-border-radius:<?php echo $radius; ?>;
    -moz-border-radius:<?php echo $radius; ?>;
    border-radius:<?php echo $radius; ?>;
    border:2px solid rgba(30,30,30,.4);
    opacity:0.1;
    -webkit-animation:lptr-circle-anim 1.2s infinite ease-in-out;
    -moz-animation:lptr-circle-anim 1.2s infinite ease-in-out;
    -ms-animation:lptr-circle-anim 1.2s infinite ease-in-out;
    -o-animation:lptr-circle-anim 1.2s infinite ease-in-out;
    animation:lptr-circle-anim 1.2s infinite ease-in-out;
    -webkit-transition:all .5s;
    -moz-transition:all .5s;-o-transition:all .5s;
    transition:all .5s;
}

#btn_perezvonok_widget.type_1:hover .circle{
    border-color:#3eb5e8;
    opacity:.5;
}

@-moz-keyframes lptr-circle-anim{
    0%{-moz-transform:rotate(0deg) scale(0.5) skew(1deg);opacity:.1;-moz-opacity:.1;-webkit-opacity:.1;-o-opacity:.1}
    30%{-moz-transform:rotate(0deg) scale(.7) skew(1deg);opacity:.5;-moz-opacity:.5;-webkit-opacity:.5;-o-opacity:.5}
    100%{-moz-transform:rotate(0deg) scale(1) skew(1deg);opacity:.6;-moz-opacity:.6;-webkit-opacity:.6;-o-opacity:.1}
}

@-webkit-keyframes lptr-circle-anim{
    0%{-webkit-transform:rotate(0deg) scale(0.5) skew(1deg);-webkit-opacity:.1}
    30%{-webkit-transform:rotate(0deg) scale(.7) skew(1deg);-webkit-opacity:.5}
    100%{-webkit-transform:rotate(0deg) scale(1) skew(1deg);-webkit-opacity:.1}
}

@-o-keyframes lptr-circle-anim{
    0%{-o-transform:rotate(0deg) scale(0.5) skew(1deg);-o-opacity:.1}
    30%{-o-transform:rotate(0deg) scale(.7) skew(1deg);-o-opacity:.5}
    100%{-o-transform:rotate(0deg) scale(1) skew(1deg);-o-opacity:.1}
}

@keyframes lptr-circle-anim{
    0%{transform:rotate(0deg) scale(0.5) skew(1deg);opacity:.1}
    30%{transform:rotate(0deg) scale(.7) skew(1deg);opacity:.5}
    100%{transform:rotate(0deg) scale(1) skew(1deg);opacity:.1}
}


#btn_perezvonok_widget.type_1 .circle_fill{
    -moz-transform-origin: 50% 50%;
    -webkit-transform-origin: 50% 50%;
    -o-transform-origin: 50% 50%;
    -ms-transform-origin: 50% 50%;
    transform-origin: 50% 50%;

    width:110px;
    height:110px;
    top:55px;
    left:55px;
    position:absolute;

    -webkit-border-radius:<?php echo $radius; ?>;
    -moz-border-radius:<?php echo $radius; ?>;
    border-radius:<?php echo $radius; ?>;
    border:2px solid transparent;
    opacity:.3;
    background-color:#3eb5e8;
    -webkit-animation:lptr-circle-fill-anim 2.3s infinite ease-in-out;
    -moz-animation:lptr-circle-fill-anim 2.3s infinite ease-in-out;
    -ms-animation:lptr-circle-fill-anim 2.3s infinite ease-in-out;
    -o-animation:lptr-circle-fill-anim 2.3s infinite ease-in-out;
    animation:lptr-circle-fill-anim 2.3s infinite ease-in-out;
    -webkit-transition:all .5s;
    -moz-transition:all .5s;
    -o-transition:all .5s;
    transition:all .5s;
}

#btn_perezvonok_widget.type_1:hover .circle_fill{
    opacity:0.75;
}

@-moz-keyframes lptr-circle-fill-anim{
    0%{-moz-transform:rotate(0deg) scale(0.7) skew(1deg);opacity:.2}
    50%{-moz-transform:rotate(0deg) scale(1) skew(1deg);opacity:.2}
    100%{-moz-transform:rotate(0deg) scale(0.7) skew(1deg);opacity:.2}
}

@-webkit-keyframes lptr-circle-fill-anim{
    0%{-webkit-transform:rotate(0deg) scale(0.7) skew(1deg);opacity:.2}
    50%{-webkit-transform:rotate(0deg) scale(1) skew(1deg);opacity:.2}
    100%{-webkit-transform:rotate(0deg) scale(0.7) skew(1deg);opacity:.2}
}

@-o-keyframes lptr-circle-fill-anim{
    0%{-o-transform:rotate(0deg) scale(0.7) skew(1deg);opacity:.2}
    50%{-o-transform:rotate(0deg) scale(1) skew(1deg);opacity:.2}
    100%{-o-transform:rotate(0deg) scale(0.7) skew(1deg);opacity:.2}
}

@keyframes lptr-circle-fill-anim{
    0%{transform:rotate(0deg) scale(0.7) skew(1deg);opacity:.2}
    50%{transform:rotate(0deg) scale(1) skew(1deg);opacity:.2}
    100%{transform:rotate(0deg) scale(0.7) skew(1deg);opacity:.2}
}


/***************** 2 ****************/

#btn_perezvonok_widget.type_2 .body{
    width: 90px;
    height: 90px;
}

#btn_perezvonok_widget.type_2 .body .icons{
    -webkit-animation: Rotate 3000ms linear infinite;
    animation:Rotate 3000ms linear infinite;
}

@-webkit-keyframes Rotate
{
    0% {-webkit-transform:rotate(0deg);}
    4% {-webkit-transform:rotate(-15deg);}
    8% {-webkit-transform:rotate(0deg);}
    12% {-webkit-transform:rotate(-15deg);}
    16% {-webkit-transform:rotate(0deg);}
    20% {-webkit-transform:rotate(-15deg);}
    24% {-webkit-transform:rotate(0deg);}
    100% {-webkit-transform:rotate(0deg);}
}

@keyframes Rotate
{
    0% {transform:rotate(0deg);}
    4% {transform:rotate(-15deg);}
    8% {transform:rotate(0deg);}
    12% {transform:rotate(-15deg);}
    16% {transform:rotate(0deg);}
    20% {transform:rotate(-15deg);}
    24% {transform:rotate(0deg);}
    100% {transform:rotate(0deg);}
}

#btn_perezvonok_widget.type_2 .triangle{
    width:0;
    height:0;
    border-style:solid;
    border-width:9px 0 9px 20px;
    border-top-color:transparent;
    border-bottom-color:transparent;
    border-right-color:transparent;
    border-left-color: <?php echo COLOR_BTN;?>;
    content:' ';
    line-height:0;
    position:absolute;
    left:100%;
    top:50%;
    margin-top:6px;
    margin-left:-43px;
    z-index:-1;
}

#btn_perezvonok_widget.type_2 .circle_fill{

    width:90px;

    height:90px;top:70px;left:70px;position:absolute;
    border: none;
    border-radius: <?php echo $radius; ?>;
    background-color: #3498DB;
    background-size:cover;
    background-repeat: no-repeat;
    -webkit-animation: pulse 2.55s infinite cubic-bezier(0.56, 0, 0, 1);
    -moz-animation: pulse 1.85s infinite cubic-bezier(0.66, 0, 0, 1);
    -ms-animation: pulse 1.85s infinite cubic-bezier(0.66, 0, 0, 1);
    animation: pulse 1.85s infinite cubic-bezier(0.66, 0, 0, 1);
    opacity:0.4;
    transition: opacity 0.7s ease;
    box-shadow:0 0 0 0 #3498DB;
}

@-webkit-keyframes pulse {to {box-shadow: 0 0 0 45px rgba(36, 156, 121, 0);}}
@-moz-keyframes pulse {to {box-shadow: 0 0 0 45px rgba(36, 156, 121, 0);}}
@-ms-keyframes pulse {to {box-shadow: 0 0 0 45px rgba(36, 156, 121, 0);}}
@keyframes pulse {to {box-shadow: 0 0 0 45px rgba(36, 156, 121, 0);}}

/***************** 3 ****************/

#btn_perezvonok_widget.type_3 {
    position: fixed;
    bottom: 1%;
    right: 1%;
    width:200px;
    height:200px;
    z-index:2000;
    transition: opacity 0.8s ease;
}

#btn_perezvonok_widget.type_3 .body{
    -webkit-animation:shakesix 6s 0s both infinite;
    -moz-animation:shake 6s 0s both infinite;
    -o-animation:shakesix 6s 0s both infinite;
    animation:shakesix 6s 0s both infinite;
}

/*#btn_perezvonok_widget.type_3 .body .icons{
    text-align:center;
    -webkit-border-radius:100%;
    -moz-border-radius:100%;
    border-radius:100%;
}*/

@-webkit-keyframes shakesix {
    80%, 100% {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
    }
    82%, 86%, 90%, 94%, 98% {
        -webkit-transform: translate3d(-10px, 0, 0);
        transform: translate3d(-10px, 0, 0);
    }
    84%, 88%, 92%, 96% {
        -webkit-transform: translate3d(10px, 0, 0);
        transform: translate3d(10px, 0, 0);
    }
}

@keyframes shakesix {
    80%, 100% {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
    }
    82%, 86%, 90%, 94%, 98% {
        -webkit-transform: translate3d(-10px, 0, 0);
        transform: translate3d(-10px, 0, 0);
    }
    84%, 88%, 92%, 96% {
        -webkit-transform: translate3d(10px, 0, 0);
        transform: translate3d(10px, 0, 0);
    }
}


/***************** 4 ***************/

#btn_perezvonok_widget.type_4{
    max-width: 500px;
    width: auto;
    height: auto;
    <?php
        switch(LOCATION_BTN){
            case Config::WIDGET_BTN_LOCATION_LEFT:
                echo 'left: 5%;';
                break;
            case Config::WIDGET_BTN_LOCATION_RIGHT:
                echo 'right: 5%;';
                break;
        }
    ?>
}

#btn_perezvonok_widget.type_4 .body{
    max-width: 500px;
    position: static;
    width: auto;
    height: auto;
}

#btn_perezvonok_widget.type_4 .body .icons{
    padding: 16px;
}

#btn_perezvonok_widget.type_4 .body .icons span{
    position: static;
    top:0;
}

</style>