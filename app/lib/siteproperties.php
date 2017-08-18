<?php
namespace app\lib;

use app\core\Response;
use app\data\Users;
use \app\helpers\Validator;
use \app\helpers\Html;
use \app\data\Sites;

class SiteProperties extends \app\core\Page
{
    private $validator;

    public function __construct($controller, $action){
        parent::__construct($controller, $action);
        $this->validator = new Validator();
    }

    public function AddPost(){
        $data = $this->request->GetData('UserData');
        $this->validator->Validate($data);

        if(!$this->validator->IsValid()){
            $this->response->SetError($this->validator->ErrorReporting());
        }
        else {
            $data = Validator::CleanKey($data);
            $data['system'] = isset($data['system'])?1:0;
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

    public function Update(){
        $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
        $site = new Sites();
        $data = $site->GetPropertyByField('id',$this->request->GetData(0));
        $data = isset($data[0])?$data[0]:array();

        if($site->IsSuccess()){
            $data['checkbox@system'] = $data['system'];
            $data['checkbox@active'] = $data['active']  == 'yes' ? '1' : '0';
            unset($data['active']);
            unset($data['system']);
            $this->response->SetSuccess($data);
            $this->response->SetSuccessFunc('UpdateProperty');
        }
        else{
            $this->response->SetError('Данные не получены!');
        }
    }

    public function UpdatePost(){
        $data = $this->request->GetData('UserData');
        $this->validator->Validate($data);

        if (!$this->validator->IsValid()) {
            $this->response->SetError($this->validator->ErrorReporting());
        } else {
            $data = Validator::CleanKey($data);
            $id = $data['id'];
            unset($data['id']);
            $data['system'] = isset($data['system'])?1:0;
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