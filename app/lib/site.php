<?php
    namespace app\lib;

    use app\core\Config;
    use app\core\Response;
    use app\core\UsersManager;
    use app\data\Users;
    use \app\helpers\Validator;
    use \app\core\ErrorInfo;
    use \app\helpers\Html;
    use \app\data\Sites;
    use \app\data\Properties;
    use \app\lib\modules\Widget;
    use \app\helpers\Uploder;

    /**
     * @group(ADMINISTRATOR,CLIENT)
     */
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

                if(!$site->CheckOwner($this->userManager->Get('UserId'),$id)){
                    $this->response->SetErrorFunc('ErrorAlert');
                    $this->response->SetError(ErrorInfo::GetMessage(ErrorInfo::ACCESS_DENIED_FOR_RESOURCES));
                    $this->response->Go();
                }

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

        public function DeletePVPost(){
            $data = Validator::CleanKey($this->request->GetData('UserData'));
            $property = new Properties();
            $property->DeleteValue($data);
            if($property->IsSuccess()){
                $this->response->SetSuccess();
            }
            else{
                $this->response->SetError('Ошибка при удалении!');
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
                    $j = 0;
                    foreach ($data as $title => $group) {
                        $fieldsHtml = '';
                        foreach($group as $idG => $vGroup) {
                            $before = '';
                            $after = '';
                            $inGroup = '';

                            switch(isset($vGroup['param']['type']) ? $vGroup['param']['type'] : ''){
                                case 'factory':
                                    $count = $property->Clear()->GetPropertyValueByName('factory-group-'.$idG,$id);
                                    if(!$count){
                                        $count = $property->Clear()->GetPropertyTagByName('default', 'factory-group-'.$idG);
                                    }
                                    $before = '<div class="factory-fields" data-c="'.$count.'">';
                                    $after = '<button type="button" class="btn btn-primary add-factory-item">Добавить еще!</button></div>';
                                    $inGroup = '<div class="delete-group-fields" title="Удалить" data-href="'.Html::ActionPath('site', 'deletepv').'">X</div>';
                                    break;
                            }

                            $count = count($vGroup['items'][0]['values']);
                            if($count == 0){
                                $count = 1;
                            }
                            $htmlVGroup = '';
                            for($i = 0; $i < $count; $i++){
                                $htmlVGroup .= '<div class="group-items">';
                                foreach ($vGroup['items'] as $item) {
                                    $htmlVGroup .= Widget::BuildField($item['info'],  isset($item['values'][$i]) ? $item['values'][$i] : array('value' => '', 'idV' => ''), $this->userManager->InRole(array(UsersManager::GROUP_ADMINISTRATOR)) || $this->userManager->IsSuperUser());
                                }
                                $htmlVGroup .= $inGroup . '</div>';
                            }

                            $fieldsHtml .= $before . $htmlVGroup . $after;
                        }

                        if(!empty($fieldsHtml)){
                            $html .= Html::Snipet('AccordionPanel',array(
                                'accordion', 'accordion-panel-' . ++$j, $title, $j == 1 ? ' in' : '', $fieldsHtml
                            ));
                        }
                    }

                    $html = '
                        <input type="hidden" id="field_id" name="UserData[id]" value="'.$id.'">
                        <div class="panel-group" id="accordion">' . $html .'</div>
                        <button class="btn btn-primary submit">Сохранить</button>
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
            $files = array();
            if(count($_FILES) > 0){
                $files = $_FILES;
            }

            $data = $this->request->GetData('UserData');
            $checkboxes = $this->request->GetData('CheckBoxList');
            $sl = $this->request->GetData('SelectList');
            $il = $this->request->GetData('InputList');

            $property = new Properties();

            for(; ($val=current($sl)) != null; next($sl), next($il)){
                $keysl = key($sl);
                $x = explode('-', $keysl);
                if($val == $property->Clear()->GetPropertyTagById('itemtext', $x[0])){
                    $sl[key($il)] = current($il);
                }
            }

            $this->validator->Validate($data + $files + $sl);

            if(!$this->validator->IsValid()){
                $this->response->SetError($this->validator->ErrorReporting());
            }
            else {
                $data = Validator::CleanKey($data);
                $files = Validator::CleanKey($files);
                $sl = Validator::CleanKey($sl);
                $siteId = $data['id'];
                unset($data['id']);

                $site = new Sites();
                if(!$site->CheckOwner($this->userManager->Get('UserId'),$siteId)){
                    $this->response->SetErrorFunc('ErrorAlert');
                    $this->response->SetError(ErrorInfo::GetMessage(ErrorInfo::ACCESS_DENIED_FOR_RESOURCES));
                    $this->response->Go();
                }

                $filesdb = array();
                foreach ($files as $key => $val){
                    $x = explode('-',$key);
                    $filename = basename($val['name']);
                    $ext = substr($filename, strrpos($filename, '.') + 1);
                    $path = Config::PATH_UPLOAD . 'sites' . Config::PATH_SEPARATOR . $siteId . Config::PATH_SEPARATOR;
                    $img = Uploder::Upload($val['tmp_name'], $path, $x[0] . '.' . $ext);
                    if(!empty($img)){
                        $filesdb[$key] = $img;
                    }
                }

                $checkboxes = $property->PrepareCheckBoxes($siteId, $checkboxes);

                $property->UpdatePropertiesValue($siteId, $data + $checkboxes + $filesdb + $sl);

                if ($property->IsSuccess()) {
                    $this->response->SetSuccess();
                    $this->response->SetSuccessFunc('UpdatePropertiesSuccess');
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