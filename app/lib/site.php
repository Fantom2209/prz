<?php
    namespace app\lib;

    use app\core\Config;
    use app\core\Response;
    use app\data\Users;
    use \app\helpers\Validator;
    use \app\core\ErrorInfo;
    use \app\helpers\Html;
    use \app\data\Sites;
    use \app\data\Properties;
    use \app\lib\modules\Widget;

    class Site extends \app\core\Page{

        private $validator;

        public function __construct($controller, $action){
            parent::__construct($controller, $action);
            $this->validator = new Validator();
        }

        public function Add(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $user = new Users();
            $item = $user->GetElementByField('id', $this->userManager->Get('UserId'));
            $item = (isset($item[0])?$item[0]:$item);
            if($user->IsSuccess()){
                $data['email'] = $item['email'];
            }
            else{
                $data['email'] = '';
            }

            $this->response->SetSuccess($data);
            $this->response->SetSuccessFunc('AddSite');

        }

        public function AddPost(){
            $data = $this->request->GetData('UserData');
            $this->validator->Validate($data);

            if(!$this->validator->IsValid()){
                $this->response->SetError($this->validator->ErrorReporting());
            }
            else{
                $data = Validator::CleanKey($data);
                $site = new Sites();
                $site->Insert($data)->Run();
                if($site->IsSuccess()){
                    $item = $site->GetElementByField('id',$site->GetLastId());
                    $item = isset($item[0])?$item[0]:array();
                    $isActive = $item['active'] == '1';
                    $html = Html::Snipet('SiteLine', array(
                        $item['date_added'], $item['url'], $item['email'],
                        Html::ActionPath('site', 'update', array($item['id'])),
                        Html::ActionPath('site', 'property', array($item['id'])),
                        Html::ActionPath('site', 'delete', array($item['id'])),
                        !$isActive ? 'disable-site' : '',
                        $isActive ? Html::ActionPath('site', 'enable', array($item['id'], '0')) : Html::ActionPath('site', 'enable', array($item['id'], '1')),
                        $isActive ? 'Выключить' : 'Включить'
                    ));
                    $this->response->SetSuccess($html);
                    $this->response->SetSuccessFunc('AddLineTop');
                }
                else{
                    $this->response->SetRedirect(Html::ActionPath('error','index', $site->ErrorReporting()));
                }
            }
        }

        public function Update(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $site = new Sites();
            $data = $site->GetElementByField('id',$this->request->GetData(0));
            if($site->IsSuccess()){
                $this->response->SetSuccess($data);
                $this->response->SetSuccessFunc('UpdateSite');
            }
            else{
                $this->response->SetError('Данные не получены!');
            }
        }

        public function UpdatePost()
        {
            $data = $this->request->GetData('UserData');
            $this->validator->Validate($data);

            if (!$this->validator->IsValid()) {
                $this->response->SetError($this->validator->ErrorReporting());
            } else {
                $data = Validator::CleanKey($data);
                $id = $data['id'];
                unset($data['id']);
                $site = new Sites();
                $site->Update($data, '`id` = ?', array($id))->Run();
                if ($site->IsSuccess()) {
                    $item = $site->GetElementByField('id', $id);
                    $item = isset($item[0]) ? $item[0] : array();
                    $isActive = $item['active'] == '1';
                    $html = Html::Snipet('SiteLine', array(
                        $item['date_added'], $item['url'], $item['email'],
                        Html::ActionPath('site', 'update', array($item['id'])),
                        Html::ActionPath('site', 'property', array($item['id'])),
                        Html::ActionPath('site', 'delete', array($item['id'])),
                        !$isActive ? 'disable-site' : '',
                        $isActive ? Html::ActionPath('site', 'enable', array($item['id'], '0')) : Html::ActionPath('site', 'enable', array($item['id'], '1')),
                        $isActive ? 'Выключить' : 'Включить'
                    ));
                    $this->response->SetSuccess($html);
                    $this->response->SetSuccessFunc('UpdateLine');
                } else {
                    $this->response->SetRedirect(Html::ActionPath('error', 'index', $site->ErrorReporting()));
                }
            }
        }

        public function Delete(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $site = new Sites();
            $site->DeleteSite($this->request->GetData(0));
            if($site->IsSuccess()){
                $this->response->SetSuccess();
                $this->response->SetSuccessFunc('DeleteLine');
            }
            else{
                $this->response->SetRedirect(Html::ActionPath('error','index', $site->ErrorReporting()));
            }
        }

        public function Property(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $property = new Properties();
            $id = $this->request->GetData(0);
            $data = $property->GetPropertiesBySite($id, 'yes');

            if($property->IsSuccess()){
                $html = '';
                if(!$data) {
                    $html = '<p>Доступных свойств нет!!!</p>';
                } else {
                    $i = 0;
                    foreach ($data as $title => $group){
                        $fieldsHtml = '';
                        foreach($group as $item){
                            if(!isset($item['id'])){
                                $fieldsHtml .= '<div clas="factory-fields">';
                                foreach($item as $factoryItem){
                                    $fieldsHtml .= Widget::BuildField($factoryItem);
                                }
                                $fieldsHtml .= '</div>';
                                continue;
                            }

                            $fieldsHtml .= Widget::BuildField($item);
                        }

                        if(!empty($fieldsHtml)){
                            $html .= Html::Snipet('AccordionPanel',array(
                                'accordion', 'accordion-panel-' . ++$i, $title, $i == 1 ? ' in' : '', $fieldsHtml
                            ));
                        }
                    }

                    $html = '
                        <input type="hidden" id="field_id" name="UserData[id]">
                        <div class="panel-group" id="accordion">' . $html .'</div>
                        <button class="btn btn-primary">Сохранить</button>
                    ';

                 }

                $this->response->SetSuccess($html);
                $this->response->SetSuccessFunc('UpdateProperties');
            }
            else{
                $this->response->SetError('Данные не получены!');
            }
        }

        public function PropertyPost(){
            $data = $this->request->GetData('UserData');
            $phones = $this->request->GetData('PhoneList');
            $timezones = $this->request->GetData('TimezoneList');

            $this->validator->Validate($data + $phones + $timezones);

            if(!$this->validator->IsValid()){
                $this->response->SetError($this->validator->ErrorReporting());
            }
            else {
                $data = Validator::CleanKey($data);
                $phones = Validator::CleanKey($phones);
                $timezones = Validator::CleanKey($timezones);

                echo "<pre>";
                var_dump($data + $phones + $timezones);
                echo "</pre>";

                $mp = array();
                foreach($phones as $key => $val){
                    $mp[$key] = $val . '|' . $timezones[$key];
                }

                echo "<pre>";
                var_dump($data + $mp);
                echo "</pre>";exit;


                $siteId = $data['id'];
                unset($data['id']);
                $site = new Sites();
                $site->UpdatePropertiesValue($siteId, $data);
                if ($site->IsSuccess()) {
                    $this->response->SetSuccess();
                } else {
                    $this->response->SetError('Ошибка при обновлении!');
                }
            }
        }

        public function Enable(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $site = new Sites();
            $id = $this->request->GetData(0);
            $val = $this->request->GetData(1);
            $site->Update(array('active'=> $val),'`id` = ?', array($id))->Run();
            if($site->IsSuccess()){
                $item = $site->GetElementByField('id',$id);
                $item = isset($item[0]) ? $item[0] : array();
                $isActive = $item['active'] == '1';
                $html = Html::Snipet('SiteLine', array(
                    $item['date_added'], $item['url'], $item['email'],
                    Html::ActionPath('site', 'update', array($item['id'])),
                    Html::ActionPath('site', 'property', array($item['id'])),
                    Html::ActionPath('site', 'delete', array($item['id'])),
                    !$isActive ? 'disable-site' : '',
                    $isActive ? Html::ActionPath('site', 'enable', array($item['id'], '0')) : Html::ActionPath('site', 'enable', array($item['id'], '1')),
                    $isActive ? 'Выключить' : 'Включить'
                ));
                $this->response->SetSuccess($html);
                $this->response->SetSuccessFunc('UpdateLine');
            }
            else{
                $this->response->SetError('Ошибка при обновлении!');
            }
        }


    }