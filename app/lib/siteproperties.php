<?php
namespace app\lib;

use app\core\Response;
use app\data\Properties;
use app\data\Users;
use \app\helpers\Validator;
use \app\helpers\Html;
use \app\data\Sites;

/**
 * @group(ADMINISTRATOR)
 */
class SiteProperties extends \app\core\Page
{
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
        $property = new Properties();
        $params = $property->GetParamsList();

        $paramsHtml = '';
        $selectHtml = '';
        foreach($params as $item){
            $selectHtml .= '<option value="'.$item['name'].'">'.$item['name'].'</option>';
        }

        $hiddenSelect = ' hidden';
        if(empty($selectHtml)){
            $hiddenSelect = '';
        }

        $paramsHtml = '
                <div class="form-group input-group param-tmp hidden">
                    <span class="input-group-addon param-name"></span>
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-danger param-delete-btn" type="button">X</button>
                    </span>
                </div>' . $paramsHtml .'
                <div class="form-group add-param-wrap">
                    <lable>Добавить:</lable>
                    <select class="param-add-select form-control">
                        '.$selectHtml.'
                        <option class="new" value="new">Новый</option>
                    </select><br>
                    <input type="text" class="param-add-input form-control'.$hiddenSelect.'"><br>
                    <button type="button" class="btn btn-primary param-add-btn">Добавить</button>
                 </div>';

        $data['paramsPanel'] =
            '<div class="panel-group" id="accordion">' .
            Html::Snipet('AccordionPanel', array(
                'accordion-add', 'accordion-add-panel-1', 'Параметры', '', $paramsHtml, 'default'
            )) .
            '</div>';

        $this->response->SetSuccess($data);
        $this->response->SetSuccessFunc('AddProperty');
    }

    public function AddPost(){
        $data = $this->request->GetData('UserData');
        $param = $this->request->GetData('Params');
        $new = $this->request->GetData('newParams');

        if(!$param){
            $param = array();
        }

        if(!$new){
            $new = array();
        }

        $this->validator->Validate($data + $param + $new);

        if(!$this->validator->IsValid()){
            $this->response->SetError($this->validator->ErrorReporting());
        }
        else {
            $data = Validator::CleanKey($data);
            $param = Validator::CleanKey($param);
            $new = Validator::CleanKey($new);

            $property = new Properties();
            $data['dop'] = $property->EncodeParams($param + $new);
            $property->AddParams($new);

            $data['system'] = isset($data['system'])?1:0;
            $data['empty'] = isset($data['empty'])?1:0;
            $data['active'] = isset($data['active'])?'yes':'no';
            $site = new Sites();
            $site->AddProperty($data);

            if($site->IsSuccess()){
                $item = $site->GetPropertyByField('id',$site->GetLastId());
                $item = isset($item[0])?$item[0]:array();
                $html = Html::Snipet('PropertyLine', array(
                        $item['id'], $item['name'],
                        Html::ActionPath('siteproperties', 'update', array($item['id'])),
                        Html::ActionPath('siteproperties', 'delete', array($item['id'])))
                );
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
        $data = $site->GetPropertyByField('id',$this->request->GetData(0));
        $data = isset($data[0])?$data[0]:array();

        if($site->IsSuccess()){
            $data['checkbox@system'] = $data['system'];
            $data['checkbox@empty'] = $data['empty'];
            $data['checkbox@active'] = $data['active']  == 'yes' ? '1' : '0';
            unset($data['active']);
            unset($data['system']);
            unset($data['empty']);

            $property = new Properties();
            $params = $property->GetParamsList();
            $values = $property->DecodeParams($data['dop']);

            $paramsHtml = '';
            $selectHtml = '';
            foreach($params as $item){
                $val = !empty($values[$item['name']]) ? $values[$item['name']] : '';
                if($val){
                    $paramsHtml .= Html::Snipet('FieldParam', array(
                        $item['name'], 'String:', $val
                    ));
                }
                else{
                    $selectHtml .= '<option value="'.$item['name'].'">'.$item['name'].'</option>';
                }
            }

            $hiddenSelect = ' hidden';
            if(empty($selectHtml)){
                $hiddenSelect = '';
            }

            $paramsHtml = '
                <div class="form-group input-group param-tmp hidden">
                    <span class="input-group-addon param-name"></span>
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-danger param-delete-btn" type="button">X</button>
                    </span>
                </div>' . $paramsHtml .'
                <div class="form-group add-param-wrap">
                    <lable>Добавить:</lable>
                    <select class="param-add-select form-control">
                        '.$selectHtml.'
                        <option class="new" value="new">Новый</option>
                    </select><br>
                    <input type="text" class="param-add-input form-control'.$hiddenSelect.'"><br>
                    <button type="button" class="btn btn-primary param-add-btn">Добавить</button>
                 </div>';

            $data['paramsPanel'] =
                '<div class="panel-group" id="accordion">' .
                    Html::Snipet('AccordionPanel', array(
                        'accordion-update', 'accordion-update-panel-1', 'Параметры', '', $paramsHtml, 'default'
                    )) .
                '</div>';

            $this->response->SetSuccess($data);
            $this->response->SetSuccessFunc('UpdateProperty');
        }
        else{
            $this->response->SetError('Данные не получены!');
        }
    }

    public function UpdatePost(){
        $data = $this->request->GetData('UserData');
        $param = $this->request->GetData('Params');
        $new = $this->request->GetData('newParams');

        if(!$param){
            $param = array();
        }

        if(!$new){
            $new = array();
        }

        $this->validator->Validate($data + $param + $new);

        if (!$this->validator->IsValid()) {
            $this->response->SetError($this->validator->ErrorReporting());
        } else {
            $data = Validator::CleanKey($data);
            $param = Validator::CleanKey($param);
            $new = Validator::CleanKey($new);
            $id = $data['id'];
            unset($data['id']);
            $property = new Properties();
            $data['dop'] = $property->EncodeParams($param + $new);
            $property->AddParams($new);
            $data['system'] = isset($data['system'])?1:0;
            $data['empty'] = isset($data['empty'])?1:0;
            $data['active'] = isset($data['active'])?'yes':'no';
            $site = new Sites();
            $site->UpdatePropertySite($id, $data);
            if ($site->IsSuccess()) {
                $item = $site->GetPropertyByField('id', $id);
                $item = isset($item[0]) ? $item[0] : array();
                $html = Html::Snipet('PropertyLine', array(
                        $item['id'], $item['name'],
                        Html::ActionPath('siteproperties', 'update', array($item['id'])),
                        Html::ActionPath('siteproperties', 'delete', array($item['id'])))
                );
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
        $site->DeleteProperty($this->request->GetData(0));

        if($site->IsSuccess()){
            $this->response->SetSuccess();
            $this->response->SetSuccessFunc('DeleteLine');
        }
        else{
            $this->response->SetRedirect(Html::ActionPath('error','index', $site->ErrorReporting()));
        }
    }

    /**
     * @content(CONTENT_TYPE_JSON)
     */
    public function Enable(){
        $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
        $site = new Sites();
        $data = $site->GetProperties();
        if($site->IsSuccess()){
            $html = '';
            foreach($data as $item){
                $html .= '
                    <div class="form-group">
                        <label>'.$item['name'].'</label>
                        <input type="checkbox" name="UserData['.$item['id'].']" '. ($item['active'] == 'yes'?'checked="checked"':'').'>
                    </div>';
            }
            $html .= '<button class="btn btn-primary">Обновить</button>';
            $this->response->SetSuccess($html);
            $this->response->SetSuccessFunc('EnableProperties');
        }
        else{
            $this->response->SetRedirect(Html::ActionPath('error','index', $site->ErrorReporting()));
        }

    }

}