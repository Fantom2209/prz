<?php
    namespace app\lib;

    use app\core\Config;
    use app\core\Response;
    use app\core\UsersManager;
    use app\data\Users;
    use app\data\Schedule;
    use \app\helpers\Validator;
    use \app\core\ErrorInfo;
    use \app\helpers\Html;
    use \app\helpers\Functions;
    use \app\data\Sites;
    use \app\data\Properties;
    use \app\lib\modules\Widget;
    use \app\helpers\Uploder;

    /**
     * @group(ADMINISTRATOR,CLIENT)
     */
    class Site extends \app\core\Page{

        private $validator;

        public function __construct($controller, $action, $meta){
            parent::__construct($controller, $action, $meta);
            $this->validator = new Validator();
        }

        /**
         * @content(CONTENT_TYPE_JSON)
         */
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

                $data['hash'] = $site->GetHashSite($data['url']);
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
                        $isActive ? 'Выключить' : 'Включить',
                        //Html::ActionPath('site','schedule', array($item['id'])),
                        $isActive ? '<i class="fa fa-check" aria-hidden="true"></i>' : '<i class="fa fa-ban" aria-hidden="true"></i>',
                    ));
                    $this->response->SetSuccess($html);
                    $this->response->SetSuccessFunc('AddLineTop');
                }
                else{
                    $this->response->SetRedirect(Html::ActionPath('error','index', $site->ErrorReporting()));
                }
            }
        }

        /**
         * @content(CONTENT_TYPE_JSON)
         */
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
                    $this->GetResponse();
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
                        $isActive ? 'Выключить' : 'Включить',
                        //Html::ActionPath('site','schedule', array($item['id'])),
                        $isActive ? '<i class="fa fa-check" aria-hidden="true"></i>' : '<i class="fa fa-ban" aria-hidden="true"></i>',
                    ));
                    $this->response->SetSuccess($html);
                    $this->response->SetSuccessFunc('UpdateLine');
                } else {
                    $this->response->SetRedirect(Html::ActionPath('error', 'index', $site->ErrorReporting()));
                }
            }
        }

        /**
         * @content(CONTENT_TYPE_JSON)
         */
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

        /**
         * @content(CONTENT_TYPE_JSON)
         */
        public function Property(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $property = new Properties();
            $site = new Sites();

            $id = $this->request->GetData(0);
            $siteInfo = $site->GetElementByField('id', $id);
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
                                'accordion', 'accordion-panel-' . ++$j, $title, '', $fieldsHtml, 'default'
                            ));
                        }
                    }

                    $html = '
                        <input type="hidden" id="field_id" name="UserData[id]" value="'.$id.'">
                        <div class="panel-group" id="accordion">' .

                            Html::Snipet('AccordionPanel',array(
                                'accordion', 'accordion-panel-' . ++$j, 'График работы', ' in',
                                    $this->Schedule($id), 'default'
                            )) .

                            $html .

                            Html::Snipet('AccordionPanel',array(
                                'accordion', 'accordion-panel-' . ++$j, 'Скрипт', '',
                                '<div class="form-group">
                                    <label><em>Установите скрипт на сайте перед тегом <code>&lt;/body&gt;</code>:</em></label>
                                    <textarea class="form-control" name="script">'.htmlentities('<script src="'.Config::URL_ROOT . Html::ActionPath('widget', 'index1', array($siteInfo[0]['hash'])).'"></script>').'</textarea>
                                </div>', 'default'
                        )) .
                        '</div>
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

            $timeRelax = $this->request->GetData('TimeRelaxData');
            $scheduleData = $this->request->GetData('ScheduleData');

            $property = new Properties();

            for(; ($val=current($sl)) != null; next($sl), next($il)){
                $keysl = key($sl);
                $x = explode('-', $keysl);
                if($val == $property->Clear()->GetPropertyTagById('itemtext', $x[0])){
                    $sl[key($il)] = current($il);
                }
            }

            $this->validator->Validate($data + $files + $sl + $timeRelax + $scheduleData);

            if(!$this->validator->IsValid()){
                $this->response->SetError($this->validator->ErrorReporting());
            }
            else {
                $data = Validator::CleanKey($data);
                $files = Validator::CleanKey($files);
                $sl = Validator::CleanKey($sl);
                $timeRelax = Validator::CleanKey($timeRelax);
                $scheduleData = Validator::CleanKey($scheduleData);
                $siteId = $data['id'];
                unset($data['id']);

                $site = new Sites();
                if(!$site->CheckOwner($this->userManager->Get('UserId'),$siteId)){
                    $this->response->SetErrorFunc('ErrorAlert');
                    $this->response->SetError(ErrorInfo::GetMessage(ErrorInfo::ACCESS_DENIED_FOR_RESOURCES));
                    $this->GetResponse();
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


                $schedule = new Schedule();

                $timeRelax['lunch'] = isset($timeRelax['lunch']) ? '1' : '0';
                $timeRelax['work_holidays'] = isset($timeRelax['work_holidays']) ? '1' : '0';

                $schedule->InsertOrUpdateRelax($siteId, $timeRelax);
                $this->ScheduleAdd($siteId, $scheduleData);

                if ($property->IsSuccess() && $schedule->IsSuccess()) {
                    $this->response->SetSuccess();
                    $this->response->SetSuccessFunc('UpdatePropertiesSuccess');
                } else {
                    $this->response->SetError('Ошибка при обновлении!');
                }
            }
        }

        /**
         * @content(CONTENT_TYPE_JSON)
         */
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
                    $isActive ? 'Выключить' : 'Включить',
                    //Html::ActionPath('site','schedule', array($item['id'])),
                    $isActive ? '<i class="fa fa-check" aria-hidden="true"></i>' : '<i class="fa fa-ban" aria-hidden="true"></i>',
                ));
                $this->response->SetSuccess($html);
                $this->response->SetSuccessFunc('UpdateLine');
            }
            else{
                $this->response->SetError('Ошибка при обновлении!');
            }
        }


        private function Schedule($id){
            $model = new Schedule();

            $scheduleList = $model->GetScheduleList($id);
            $relaxInfo = $model->GetRelaxTimeInfo($id);

            $html = '';
            $j = 0;

            $timeList = Functions::GetTimeList('00:00', '23:59', 15,null, null);

            /*$scheduleValues = array();
            if(count($scheduleList) > 0){

                foreach ($scheduleList as $item){
                    $scheduleValues[$item['day_id']] = $item;
                }
                unset($scheduleList);
            }*/

            $days = $model->GetDaysList();
            foreach ($days as $item){
                $j++;
                $isUpdate = false;
                if(!empty($scheduleList[$j])){
                    $isUpdate = true;
                }

                $selects = array(
                    'work_start' => '',
                    'work_end' => '',
                );

                foreach ($timeList as $key => $val){
                    foreach ($selects as $name => &$select){
                        $select .= '<option value="'.$val.'"'.($isUpdate && $scheduleList[$j][$name] == $val ? ' selected' : '').'>'.$key.'</option>';
                    }
                    unset($select);
                }


                $html .= '
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>'.$item['name'].': <input type="checkbox" name="ScheduleData[work__'.$j.']" '.($isUpdate && $scheduleList[$j]['work'] == '1' ? ' checked' : '').'></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="ScheduleData[work_start__'.$j.']" class="form-control">'.$selects['work_start'].'</select>
                        </div>
                        <div class="col-md-1 text-center">
                            <div> - </div>        
                        </div>
                        <div class="col-md-3">
                            <select name="ScheduleData[work_end__'.$j.']" class="form-control">'.$selects['work_end'].'</select>
                        </div>
                     </div>
                ';

            }

            $selectLunchStart = '';
            $selectLunchEnd = '';

            foreach ($timeList as $key => $val){
                $selectLunchStart .= '<option value="'.$val.'"'.(isset($relaxInfo['lunch_start']) && $relaxInfo['lunch_start'] == $val ? ' selected' : '').'>'.$key.'</option>';
                $selectLunchEnd .= '<option value="'.$val.'"'.(isset($relaxInfo['lunch_end']) && $relaxInfo['lunch_end'] == $val ? ' selected' : '').'>'.$key.'</option>';
            }


            $html = '<div class="panel-group" id="accordion-schedule">' . $html . '
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Обед: <input type="checkbox" name="TimeRelaxData[lunch]" '.(isset($relaxInfo['lunch']) && $relaxInfo['lunch'] == '1' ? ' checked' : '').'></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="TimeRelaxData[lunch_start]" class="form-control">'.$selectLunchStart.'</select>
                    </div>
                    <div class="col-md-1 text-center">
                        <div> - </div>        
                    </div>
                    <div class="col-md-3">
                        <select name="TimeRelaxData[lunch_end]" class="form-control">'.$selectLunchEnd.'</select>
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Принимать звонки по праздникам: <input type="checkbox" name="TimeRelaxData[work_holidays]" '.(isset($relaxInfo['work_holidays']) && $relaxInfo['work_holidays'] == '1' ? ' checked' : '').'></label>
                        </div>
                    </div>
                 </div>
            </div>';

            return $html;
        }

        private function ScheduleAdd($id, $data){
            $fields = array('work', 'work_start', 'work_end');
            $model = new Schedule();
            $days = $model->GetDaysList();
            foreach($days as $item){
                $result = array();
                foreach($fields as $field){
                    $result[$field] = !empty($data[$field . '__' . $item['id']]) ? $data[$field . '__' . $item['id']] : 0;
                    if($result[$field] === 'on') {
                        $result[$field] = 1;
                    }
                }
                $model->InsertOrUpdate($id, $item['id'], $result);
            }
        }

    }