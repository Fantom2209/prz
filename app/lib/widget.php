<?php
namespace app\lib;

use \app\core\Config;
use \app\core\ErrorInfo;
use app\core\Response;
use \app\data\Properties;
use \app\data\Blacklist;
use \app\data\Sites;
use \app\data\Calls;
use \app\data\Emails;
use \app\data\Schedule;
use app\helpers\Develop;
use \app\helpers\Validator;
use \app\helpers\Functions;

class Widget extends \app\core\Page{

    private $validator;

    public function __construct($controller, $action, $meta)
    {
        parent::__construct($controller, $action, $meta);
        $this->response->SetLayout(Config::PATH_LAYOUT . 'emptyLayout.php');
        $this->validator = new Validator();
    }

    public function Index(){
        $widget = $this->response->modules->Add('widget');

        $widget->SetTemplate('main');
        ob_start();
        $this->response->modules->widget;
        $html = ob_get_clean();

        $this->response->Set('html',$html);
        //$widget->Set('html','<div id="perezvonok_widget" class="window_wrapper" ><div class="window_panel window_type_1"><div class="close_arrow"></div><div class="icons_panel"><ul class="icon_list"><li class="icon_list_item"><a class="item_link_call trigger" data-p="instant_call"><div class="active"><i class="fa fa-phone"></i></div><span>Позвоним<br>сейчас</span></a></li><li class="icon_list_item"><a class="item_link_delay trigger" data-p="call_on_time"><div><i class="fa fa-clock-o"></i></div><span>Позвоним<br>позже</span></a></li><li class="icon_list_item"><a class="item_link_application trigger" data-p="send_msg"><div><i class="fa fa-envelope"></i></div><span>Ответим<br>по почте</span></a></li><li class="icon_list_item"><a target="_blank" class="item_link_consultant_vk"><div><i class="fa fa-vk"></i></div><span>Ответим<br>Вконтакте</span></a></li></ul></div><!-- panel instant_call --><div id="instant_call" class="panels active_panel"><div class="panel_body"><div class="block_info"><div class="worktime"><div class="text_block">— Установите виджет на свой сайт и тестируйте <br>2 недели бесплатно!<br><br>Далее всего 130 рублей в месяц и никаких скрытых платежей!</div></div><div class="notworktime"><div class="text_block">Установите виджет на свой сайт и тестируйте <br>2 недели бесплатно!<br><br>Далее всего 130 рублей в месяц и никаких скрытых платежей!</div><br><select class="select_date"><option value="1" data-hours="00:00-24:00">Сегодня</option><option value="2" data-hours="00:00-24:00">10 октября</option><option value="3" data-hours="00:00-24:00">11 октября</option><option value="4" data-hours="00:00-24:00">12 октября</option><option value="5" data-hours="00:00-24:00">13 октября</option><option value="6" data-hours="00:00-24:00">14 октября</option><option value="7" data-hours="00:00-24:00">15 октября</option></select> в <select class="select_hours"><option value="14:15" style="display: block;">14:15</option><option value="14:30" style="display: block;">14:30</option><option value="14:45" style="display: block;">14:45</option><option value="15:00" style="display: block;">15:00</option><option value="15:15" style="display: block;">15:15</option></select></div></div><div class="block_form"><div class="phone_line"><input id="clb_phone" class="panel_textbox telefon2" type="text"><button class="panel-button">Жду звонка</button><br><label><img src="https://perezvonok.ru/checked.jpg"><a class="pz_agr_link" href="https://perezvonok.ru/?a=agreement" rel="nofollow" target="_blank">Я согласен на обработку персональных данных</a></label></div><div class="panel_timer"><p>00:25.99</p></div><div class="manager_evaluation"><div class="msg" style="font-size:16px!important;">Оцените ответ менеджера</div><ul class="stars_list"><li data-vote="1"><i class="fa fa-star"></i></li><li data-vote="2"><i class="fa fa-star"></i></li><li data-vote="3"><i class="fa fa-star"></i></li><li data-vote="4"><i class="fa fa-star"></i></li><li data-vote="5"><i class="fa fa-star"></i></li></ul></div></div></div></div><!-- panel instant_call end --><!-- panel call_on_time --><div id="call_on_time" class="panels"><div class="panel_body"><div class="text1">Выберите время</div><div class="text2">удобное для звонка</div><br><div class="block_info"><select class="select_date"><option value="1" data-hours="00:00-24:00">Сегодня</option><option value="2" data-hours="00:00-24:00">10 октября</option><option value="3" data-hours="00:00-24:00">11 октября</option><option value="4" data-hours="00:00-24:00">12 октября</option><option value="5" data-hours="00:00-24:00">13 октября</option><option value="6" data-hours="00:00-24:00">14 октября</option><option value="7" data-hours="00:00-24:00">15 октября</option></select> в <select class="select_hours"><option value="21:30" style="display: block;">21:30</option><option value="21:45" style="display: block;">21:45</option><option value="22:00" style="display: block;">22:00</option><option value="22:15" style="display: block;">22:15</option><option value="22:30" style="display: block;">22:30</option><option value="22:45" style="display: block;">22:45</option><option value="23:00" style="display: block;">23:00</option><option value="23:15" style="display: block;">23:15</option><option value="23:30" style="display: block;">23:30</option><option value="23:45" style="display: block;">23:45</option><option value="24:00" style="display: block;">24:00</option></select></div><div class="block_form"><div class="phone_line"><input id="clb_phone" class="panel_textbox" type="text"><button class="panel_button">Жду звонка</button><br><label><img src="https://perezvonok.ru/checked.jpg"><a class="pz_agr_link" href="https://perezvonok.ru/?a=agreement" rel="nofollow" target="_blank">Я согласен на обработку персональных данных</a></label></div></div></div></div><!-- panel call_on_time end --><!-- panel msg --><div id="send_msg" class="panels"><div class="panel_body"><div class="block_form"><div class="text1">Напишите нам!</div><div class="text2">Мы обязательно ответим в самое ближайшее время</div><br><form><div class="textarea_wrapper"><textarea type="text" name="clien_comment"></textarea><div class="submit_result"></div></div><div class="input_wrap"><input type="text" name="client_name"><div class="submit_result"></div></div><div class="input_wrap"><input type="text" name="client_email"><div class="submit_result"></div></div><div class="submit_btn"><button type="submit" class="panel_button">Жду ответ</button><br><label><img src="https://perezvonok.ru/checked.jpg"><a class="pz_agr_link" href="https://perezvonok.ru/?a=agreement" rel="nofollow" target="_blank">Я согласен на обработку персональных данных</a></label></div></form></div></div></div><!-- panel msg end --><a class="copyright_link" href="https://www.perezvonok.ru/" target="_blank" rel="nofollow">Сервис обратной связи PereZvonok</a></div><div class="window_bg"></div></div>');
    }

    public function Index1(){
        $timeFrom = '09:00'; $timeTill = '18:00'; $lunchFrom = '13:00'; $lunchTill = '14:00'; //todo
        $widget = $this->response->modules->Add('widget');

        $siteHash = $this->request->GetData(0);
        $siteInfo = $this->CheckSite($siteHash);

        $model = new Properties();

        $siteProperties = $model->GetPropertiesBySite($siteInfo[0]['id'], 'yes');
        $filterSettings = $model->GetSettingsFilter($siteProperties);

        if(!empty($filterSettings['pages'])) {
            if (!modules\Widget::Filter($filterSettings['pages'], $this->request->GetData('__referrer__'))) {
                $this->response->Set('msg', Config::WIDGET_MSG_NOT_ACTIVE);
                $this->response->SetTemplateByName('msg');
                $this->response->Go();
            }
        }

        // кнопка

        $btnSettings = $model->GetBtnSettings($siteProperties);
        $widget->SetRange($btnSettings);

        if($widget->Get('formCallBtn') == Config::WIDGET_BTN_RECTANGLE){
            $widget->SetTemplate(Config::WIDGET_BTN_TYPE_VIEW['rectangle']);
        }
        else{
            $widget->SetTemplate(Config::WIDGET_BTN_TYPE_VIEW[$widget->Get('phoneBtnType')]);
        }

        ob_start();
        $widget->show();
        $widget->Reset();

        $btnHtml = ob_get_clean();

        // окно
        $windowSettings = $model->GetWindowSettings($siteProperties);
        $openSettings = $model->GetSettingsOpen($siteProperties);
        $textSettings = $model->GetSettingsText($siteProperties);
        $branchList = $model->GetBranchList($siteProperties);

        $widget->SetRange($windowSettings);
        $widget->SetRange($textSettings);


        $schedule = new Schedule();
        $branchListResult = $schedule->PrepareBranches($siteInfo[0]['id'], $branchList);

        if($widget->Get('windowType') == Config::WIDGET_WINDOW_TYPE_RIGHT) {
            $widget->Set('emailPanel', count($branchListResult['emailBranches']) > 0);
            $widget->Set('emailBranches', $branchListResult['emailBranches']);
        }

        $dayList = modules\Widget::GetDayList(7, $siteInfo[0]['id']);
        $now = date('Y-m-d');
        $curr = $dayList['days'][0];

        if($curr['val'] == $now){
            $curr['start'] = date('H:i') . ':00';
        }

        $timeList = Functions::GetTimeList($curr['start'], $curr['end'], 15, $dayList['lunch_start'], $dayList['lunch_end']);


        //$timeList = modules\Widget::GetTimeList($timeFrom, $timeTill, $lunchFrom, $lunchTill, 5);



        $widget->Set('timeList', $timeList);
        $widget->Set('dayList', $dayList['days']);
        $widget->Set('siteHash', $siteHash);
        //$widget->Set('branchList', $branchList);

        $widget->Set('isWork', count($branchListResult['workBranches']) > 0);
        $widget->Set('workBranches', $branchListResult['workBranches']);
        $widget->Set('notWorkBranches', $branchListResult['notWorkBranches']);


        //$widget->Set('isNotWork', $workTimeStatus === Config::WIDGET_TIME_NOT_WORK);
        //$widget->Set('isLunch', $workTimeStatus === Config::WIDGET_TIME_LUNCH);
        $widget->Set('isVK', !empty($windowSettings['activeVK']));

        $widget->SetTemplate(Config::WIDGET_WINDOW_TYPE_VIEW[$widget->Get('windowType')]);

        ob_start();
        $widget->show();
        $htmlWindow = ob_get_clean();

        $this->response->Set('html',$htmlWindow . $btnHtml);
        $this->response->Set('siteHash', $siteHash);

        $this->response->SetRange($btnSettings);
        $this->response->Set('trackClosing', $openSettings['trackClosing'] == '1'?'true':'false');
        $this->response->Set('autoStart', $openSettings['autoStart'] == '1'?'false':'true');
        $this->response->Set('autoStartTimer', !empty($openSettings['autoStartTimer'])? $openSettings['autoStartTimer'] * 1000 : 1000);
        $this->response->Set('countPage', !empty($openSettings['countPage']) ? $openSettings['countPage'] : 1);
    }

    public function Ajax(){
        $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
        $this->response->SetSuccess('{name:"test", type:"JSON"}');
        $this->response->ActivateCORSE();
    }

    private function CheckSite($hash){
        $site = new Sites();
        $siteInfo = $site->GetElementByField('hash', $hash);

        if(empty($siteInfo[0]['id'])){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $this->response->SetError(ErrorInfo::GetMessage(ErrorInfo::WIDGET_UNDEFINED_SITE));
            $this->response->Go();
        }
        return $siteInfo;
    }

    private function CheckPhone($site, $phone){
        $blacklist = new Blacklist();
        if(!$blacklist->CheckPhone($site, $phone)){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $this->response->SetError(ErrorInfo::GetMessage(ErrorInfo::WIDGET_LOCKED_PHONE));
            $this->response->Go();
        }
    }

    private function GetCallData($instant){
        $data = array();
        $userData = Validator::CleanKey((array)json_decode($this->request->GetData('json_data')));

        $siteInfo = $this->CheckSite($userData['hash']);
        $this->CheckPhone($siteInfo[0]['id'], $userData['clientPhone']);

        $data['site'] = $siteInfo[0]['id'];
        $data['user'] = $siteInfo[0]['id_user'];
        $data['tel'] = $userData['clientPhone'];
        $data['office'] = $userData['branch'];
        $data['event'] = $userData['triggerType'];

        $property = new Properties();

        $siteProperties = $property->GetPropertiesBySite($siteInfo[0]['id'], 'yes');
        $manager = $property->GetRandomManager($userData['branch'], $property->GetBranchList($siteProperties));

        $data['telmanager'] = $manager['phone'];
        $data['office'] = $manager['branch'];

        if($instant){
            $data['data'] = date('Y-d-n H:i:s');
        }
        else{
            $data['data'] = $userData['day'] . ' ' . $userData['time'];
        }

        $data['referer'] = $this->request->GetData('__referrer__');
        $data['ip'] = $this->request->GetData('__ip__');
        $data['platform'] = Functions::IsMobile() ? 'mobile' : 'desktop';

        $brInfo = Functions::BrowserDetect();

        if(!empty($brInfo['platform'])){
            $data['os'] = $brInfo['platform'];
        }

        if(!empty($brInfo['name'])){
            $data['browser'] = $brInfo['name'];
        }

        return $data;
    }

    public function InstantCallPost(){
        $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
        $this->response->ActivateCORSE();

        $data = $this->GetCallData(true);

        $model = new Calls();
        $model->Insert($data)->Run();
        $this->response->SetSuccess(array('id' => $model->GetLastId()));
        $this->response->SetSuccessFunc('instantCall');
    }

    public function CallTimePost(){
        $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
        $this->response->ActivateCORSE();

        $data = $this->GetCallData(false);
        $model = new Calls();
        $model->Insert($data)->Run(true);
        $this->response->SetSuccess(array('id' => $model->GetLastId()));
        $this->response->SetSuccessFunc('callTime');
    }

    public function SendEmailPost(){
        $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
        $this->response->ActivateCORSE();

        $data = array();
        $userData = Validator::CleanKey((array)json_decode($this->request->GetData('json_data')));
        $siteInfo = $this->CheckSite($userData['hash']);


        /*$property = new Properties();
        $siteProperties = $property->GetPropertiesBySite($siteInfo[0]['id'], 'yes');
        $manager = $property->GetRandomManagerWithEmail($userData['branch'], $property->GetBranchList($siteProperties));*/


        $data['date'] = date('Y-d-n H:i:s');
        $data['sended'] = 0;
        $data['site'] = $siteInfo[0]['id'];
        $data['name'] = $userData['client_name'];
        $data['office'] = $userData['branch'];
        $data['comment'] = $userData['client_comment'];
        $data['email'] = $userData['client_email'];
        $data['referer'] = $this->request->GetData('__referrer__');
        $data['ip'] = $this->request->GetData('__ip__');

        $model = new Emails();
        $model->Insert($data)->Run(true);

        $this->response->SetSuccess();
        $this->response->SetSuccessFunc('sendEmail');
    }

    public function ManagerEvaluationPost(){
        $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
        $this->response->ActivateCORSE();
        $model = new Calls();

        $userData = (array)json_decode($this->request->GetData('json_data'));

        $model->Update(array('rating' => $userData['mark']), 'id = ?', array($userData['id']))->Run();

        $this->response->SetSuccess();
        $this->response->SetSuccessFunc('managerEvaluation');
    }

    public function Css(){
        $this->response->SetContentType(Response::CONTENT_TYPE_CSS);
        $this->response->ActivateCORSE();
    }

    public function Testcss(){
        $this->response->SetContentType(Response::CONTENT_TYPE_CSS);
        $this->response->ActivateCORSE();

        $siteHash = $this->request->GetData(0);
        $site = new Sites();
        $siteInfo = $site->GetElementByField('hash', $siteHash);

        if(empty($siteInfo[0]['id'])){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $this->response->SetError(ErrorInfo::GetMessage(ErrorInfo::WIDGET_UNDEFINED_SITE));
            $this->response->Go();
        }

        $model = new Properties();

        $siteProperties = $model->GetPropertiesBySite($siteInfo[0]['id'], 'yes');
        $windowSettings = $model->GetWindowSettings($siteProperties);
        $btnSettings = $model->GetBtnSettings($siteProperties);

        $this->response->SetRange($windowSettings);
        $this->response->SetRange($btnSettings);

        $this->response->Set('showTitle', !empty($windowSettings['showTitle']));
        $this->response->Set('squareIcons', !empty($windowSettings['typeTab']));
    }
}