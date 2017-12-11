<?php use \app\core\Config; ?>

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

.fa
{
    display: inline-block;
    font: normal normal normal 14px FontAwesome;
    font-size: inherit;
    font-style: normal;
    line-height: 1;
    moz-osx-font-smoothing: grayscale;
    text-rendering: auto;
    webkit-font-smoothing: antialiased;
}

.fa-phone:before{content:"\f095";font-family:FontAwesome;}
.fa-envelope:before{content:"\f0e0";font-family:FontAwesome;}
.fa-clock-o:before{content:"\f017";font-family:FontAwesome;}
.fa-vk:before {content:"\f189";font-family:FontAwesome;}
.fa-times:before {content:"\f00d";font-family:FontAwesome;}
.fa-star:before {content:"\f005";font-family:FontAwesome;}

/* окно */

#perezvonok_widget.window-wrapper{
    position:fixed;
    width:100%;
    height:100%;
    top:0;
    left:0;
    z-index:2147483000;
}

#perezvonok_widget .window_panel{
    background-image: url("https://perezvonok.ru/css/panel-bg.png");
    opacity: 0.95;
    background-color: rgb(242, 242, 242);
    background-position: center center;
    position: fixed;
    right: 0px;
    overflow: hidden;
    -webkit-transition: 0.2s ease-out;
    -moz-transition: 0.2s ease-out;
    -o-transition: 0.2s ease-out;
    transition: 0.2s ease-out;
    z-index: 2147483000;
    color: #555;
}

#perezvonok_widget .window_panel *{
    font-family:"opensans",Arial,Tahoma,Helvetica,sans-serif;
    font-weight:400;
    letter-spacing:normal;
    font-size:inherit;
    text-shadow:none;
    overflow-wrap:normal;
    word-wrap:normal;
}

#perezvonok_widget .window_panel .close_arrow{
    opacity:1;
    right:0px;
    height:40px;
    cursor:pointer;
    left:0px;
    position:absolute;
    top:50%;
    width:20px;
    z-index:1000;
    margin-top:-60px;
    background:transparent url("https://perezvonok.ru/css/close-arrow.png") no-repeat scroll center center;
}

#perezvonok_widget .window_panel.window_type_1 {
    width:320px;
    right:-320px;
    top:0;
    height:100%;
    text-align:center;
    background-color:#F2F2F2;
}

#perezvonok_widget.open .window_panel.window_type_1{
    right: 0;
}

#perezvonok_widget .window_bg{
    position:fixed;
    width:100%;
    height:100%;
    top:0;
    left:0;
    z-index:20000;
    display:none;
    background-color:rgba(0, 0, 0, 0.5);
}

#perezvonok_widget.open .window_bg{
    display:block;
}

#perezvonok_widget .copyright_link{
    opacity:0.4;
    position:absolute;
    left:44px;
    bottom:15px;
    text-decoration:none;
    font-size:13px;
    letter-spacing:normal;
    font-size:13px;
    line-height:22px;
    letter-spacing:normal;
    height:auto;
    z-index:100;
    border:none;
    color:#333333;
}

#perezvonok_widget .copyright_link:hover{
    opacity:0.7;
}

/* окно конец*/

/* панель иконок */

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
    background-color: #999999;
    background-position: center center;
    background-repeat: no-repeat;
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
    border-radius: 4px;
    moz-border-radius: 4px;
    webkit-border-radius: 4px;
}

#perezvonok_widget .icons_panel .icon_list_item span
{
    display: inherit;
    font: 400 11px/10px "opensans",Arial,Tahoma,Helvetica,sans-serif;
    text-align: center;
    transform: none;
    color: #333333;
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

#perezvonok_widget .icons_panel .icon_list_item .active,
#perezvonok_widget .icons_panel .icon_list_item > a:hover > div
{
    background-color: #5CB85C;
}

/* панель иконок конец */

/* панели */

#perezvonok_widget .panels{
    display: none;
}

#perezvonok_widget .panels .panel_body{
    padding:5px 30px 15px 30px;
    position:absolute;
    top:27%;
    z-index:900;
}

#perezvonok_widget .panels.active_panel{
    display:block;
}

#perezvonok_widget .panels .panel_body select {
    appearance:none;
    -moz-appearance:none;
    -webkit-appearance:none;
    -ms-appearance:none;
    background:#fff;
    background-image:url("<?php echo Config::URL_IMG;?>selectarrow.jpg");
    background-position:right center;
    background-repeat:no-repeat;
    background-size:23px 16px;
    cursor:pointer;
    text-shadow:none;
}

#perezvonok_widget .panels .panel_body select option {
    padding:0 7px;
    text-shadow:none;
}

/* панели end */

/* instant_call */

#perezvonok_widget .block_info{
    font-size:18px;
    line-height:28px;
    text-align:center;
    color:#333;
    letter-spacing:normal;
    height:auto
}

#perezvonok_widget .worktime,
#perezvonok_widget .notworktime{
    padding-left:4px;
    text-align:left;
    color:#333333;
    display:block;
}

#perezvonok_widget .notworktime{
    font-size:22px;
    line-height:34px;
    text-align:center;
    letter-spacing:normal;
    display:none;
}

#perezvonok_widget .text_block
{
    font-size: 18px;
    line-height: 28px;
    font-weight: 400;
}

#perezvonok_widget .panels .block_info select
{
    border: 1px solid #c2c2c2;
    border-radius: 2px;
    box-shadow: none;
    box-sizing: border-box;
    color: #333;
    display: inline;
    font-family: inherit;
    font-size: 18px;
    height: auto;
    margin: 5px 0 0 0;
    padding: 9px 4px;
    vertical-align: baseline;
}

#perezvonok_widget .panels .panel_body .select_date{
    margin-left:-2px;
    width:140px;
    min-width:auto;
}

#perezvonok_widget .panels .panel_body .select_hours{
    width:92px;
    min-width:auto;
}

#perezvonok_widget .block_form{
    position:relative;
    width:100%;
    text-align:center;
    letter-spacing:normal
}

#perezvonok_widget .block_form .phone_line{
    text-align:center;
    position:relative;
    margin-top:10px;
    display:inline-block;
}

#perezvonok_widget .block_form label{
    margin:3px 3px 3px 2px;
    font-size:10px;
    opacity:0.7;
    text-align:left;
    line-height:20px;
    position:static;
    color:#333333;
    display:inline-block;
}

#perezvonok_widget .block_form img{
    height:14px;
    margin:3px 5px 0 0;
    width:13px;
    float:left;
}

#perezvonok_widget .block_form .pz_agr_link{
    text-decoration:none;
    margin:0;
    padding:0;
    font-size:10px;
    opacity:1;
    text-align:left;
    line-height:20px;
    color:#333333;
}

#perezvonok_widget .panels .panel_body .panel_textbox{
    min-width:230px;
    width:230px;
    height:44px;
    border:1px solid #c2c2c2;
    font-size:22px;
    border-radius:2px;
    background:#fff;
    color:#333;
    line-height:30px;
    padding:2px 12px;
    text-align:left;
    margin:0 2px 0 0;
    box-shadow:none;
    letter-spacing:normal;
    left:auto;
    box-sizing:unset;
}

#perezvonok_widget .panels .panel_body .panel_textbox:focus{
    border-color:#52aff7
}

#perezvonok_widget .panels .panel_body .panel_button{
    display:inline-block;
    float:none;
    margin-top:10px;
    width:256px;
    height:50px;
    font-size:16px;
    border:none;
    border-radius:2px;
    color:#fff;
    line-height:30px;
    cursor:pointer;
    background-image:none;
    text-transform:uppercase;
    font-weight:400;
    text-align:center;
    -webkit-box-shadow:none;
    -moz-box-shadow:none;
    box-shadow:none;
    text-shadow:none;
    padding:0;
    letter-spacing:normal;
    text-indent:0;
    position:static;
    -webkit-transition:all 120ms ease-in 0s;
    -moz-transition:all 120ms ease-in 0s;
    -o-transition:all 120ms ease-in 0s;
    transition:all 120ms ease-in 0s;
    background-color: #5CB85C
}

#perezvonok_widget .panels .panel_body .panel_button:hover{
    opacity:0.7;
}

#perezvonok_widget .panels .panel_body .panel_timer{
    margin:24px 0 0 0;
    opacity:0.9;
}

#perezvonok_widget .panels .panel_body .panel_timer p{
    font-family:"opensanslight","opensans",Arial,Tahoma;
    line-height:30px;
    font-size:46px;
    margin:10px 0 0 0;
    padding:0;
    letter-spacing:normal;
    text-align:center;
    font-weight:100;
    color:#333333
}


#perezvonok_widget .manager_evaluation{
    display:none;
}

#perezvonok_widget .manager_evaluation .stars_list {
    padding-left:0;
    margin-top:10px;
    list-style:none;
    margin-top:20px;
    color:#333333
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

#perezvonok_widget .manager_evaluation .stars_list li:hover {
    color:#f1c40f;
}

#perezvonok_widget .manager_evaluation .msg{
    font-size:16px;
}

/* instant_call конец */

/* call_on_time */

#perezvonok_widget .text1{
    font-size:26px;
    color:#333333;
    line-height:36px;
}

#perezvonok_widget .text2{
    font-size:18px;
    color:#333333;
    line-height:28px;
}

/* call_on_time end */

/* msg */
#perezvonok_widget #send_msg .panel_body{
    margin-top: -60px;
}

#perezvonok_widget .textarea_wrapper
{
    background-color: #fff;
    border: 1px solid #c2c2c2;
    border-radius: 2px ;
    box-sizing: unset;
    height: 120px ;
    margin: 0 auto 10px ;
    padding: 10px;
    text-indent: 0 ;
    width: 234px ;
}

#perezvonok_widget .textarea_wrapper textarea
{
    background-color: #fff;
    border: none ;
    border-radius: 0 ;
    box-shadow: none;
    color: #333;
    font: normal 18px/20px "opensans",Arial,Tahoma,Helvetica,sans-serif ;
    height: 120px;
    margin: 0;
    max-height: 120px;
    max-width: 234px;
    min-width: 234px;
    outline: 0 none ;
    padding: 0 5px 0 0;
    resize: none;
    width: 234px;
}

#perezvonok_widget .input_wrap{
    display:block;
    margin:10px auto 0 auto;
    width:255px;
    min-width:237px;
    font-size:18px;
    height:44px;
}

#perezvonok_widget .input_wrap input{
    width: 100%;
}

#perezvonok_widget .submit_result{
    color: #FF0D29;
    padding-left: 17px;
    text-align: left;
    position: absolute;
    font-family: Calibri,"opensans",Arial,Tahoma,Helvetica,sans-serif;
    font-size: 10px;
    top: 2px;
    right: 18px;
    font-weight: bold;
    pointer-events: none;
}

#perezvonok_widget .submit_btn {
    display:block;
    width:286px;
    margin:0 auto 36px;
}

/* msg end */

.clb_banner-0 {padding:20px 0 10px 0!important; width:700px!important; height:auto!important;top:21%!important;left:50%;margin-left:-344px;box-shadow:0 0px 20px 0 rgba(43, 49, 54, 0.5)!important;text-align:center!important; -webkit-border-radius:4px!important; -moz-border-radius:4px!important; border-radius:4px!important;}