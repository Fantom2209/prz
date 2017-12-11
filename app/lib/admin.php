<?php
namespace app\lib;
use app\core\Config;
use \app\data\Users;
use \app\data\Sites;
use app\helpers\Develop;
use app\helpers\Html;
use \app\lib\modules\Menu;
use \app\data\Properties;
use \app\data\Blacklist;
use \app\data\Holidays;
use \app\core\UsersManager;
use \app\helpers\Pagination;


/**
 * @group(ADMINISTRATOR)
 */
class Admin extends \app\core\Page{

    public function __construct($controller, $action, $meta){
        parent::__construct($controller, $action, $meta);

        $menu = $this->response->modules->Add('menu');
        $menu->Init(Menu::TYPE_ADMIN_MAIN);
        $menu->SetBrand('Панель администратора');
        $menu->Set('IsAuth', $this->userManager->IsAuthorized());
        $menu->Set('IsAdmin',$this->userManager->InRole(array(UsersManager::GROUP_ADMINISTRATOR)));
        $menu->Set('AccountName', $this->userManager->Get('UserName'));
        $menu->Set('IsActive', $this->userManager->IsActive());
    }

    public function Index(){
        $user = new Users();
        $list = $user->GetUsersList();
        $role = $user->GetRole();

        $this->response->Set('Users', $list);
        $this->response->Set('role', $role);
        $this->response->Set('EmptyList', count($list) == 0);
        $this->response->Set('title', 'Работа с пользователями');
    }

    public function Properties(){
        $site = new Sites();
        $propertiesType = $site->GetPropertiestType();
        $propertiesGroup = $site->GetPropertiesGroup();
        
        //$this->response->Set('properties',$site->GetProperties());
        $this->response->Set('PropertiesType',$propertiesType);
        $this->response->Set('PropertiesGroup',$propertiesGroup);
        $this->response->Set('title','Настройка свойств сайтов');

        $model = new Properties();
        $page = $this->request->GetData('page');

        //filter
        $filter = $this->response->modules->Add('filter');
        $filter->Init($this->request, 'properties');
        $filter->Set('page',$page ? $page : 1);
        $filter->Set('groups',$model->GetPropertiesGroup());

        //pagination
        $metaDb = $filter->Get('dbMeta');
        $countItems = $model->Select()->Where($metaDb['pattern'], $metaDb['data'])->Build()->Run(true)->CountResult();
        $p = Pagination::Build($countItems, $page ? $page : 1, Config::PAGINATION_COUNT_LEFT, Config::PAGINATION_COUNT_RIGHT, $filter->GetParamList());
        $this->response->Set('properties',$model->Select()->Where($metaDb['pattern'], $metaDb['data'])->Limit($p['start'],Config::PAGINATION_COUNT_ON_PAGE)->Build()->Run()->GetAll());
        $this->response->Set('pagination', $p['html']);
    }

    public function PropertiesPost(){
        $site = new Sites();
        $site->UpdateProperty($this->request->GetData('UserData'));
        if($site->IsSuccess()){
            $this->response->SetSuccess('Успех!!!');
        }
        else{
            $this->response->SetError('Ошибка при обновлении!');
        }
    }

    public function Blacklist(){
        $model = new Blacklist();
        $info = $model->GetItems();
        $this->response->Set('phones', $info[0]['phones']);
    }

    public function BlacklistPost(){
        $data = $this->request->GetData('UserData');
        $model = new Blacklist();
        $model->UpdateList($data);
        if($model->IsSuccess()){
            $this->response->SetSuccess();
            $this->response->SetSuccessFunc('SuccessOperation');
        }
        else {
            $this->response->SetRedirect(Html::ActionPath('error', 'index', $model->ErrorReporting()));
        }
    }

    public function Holidays(){
        $model = new Holidays();
        $info = $model->Get();
        $this->response->Set('holidays', isset($info['holidays']) ? $info['holidays'] : '');
    }

    public function HolidaysPost(){
        $data = $this->request->GetData('UserData');
        $model = new Holidays();
        $model->UpdateOrInsert(1, $data);

        if($model->IsSuccess()){
            $this->response->SetSuccess();
            $this->response->SetSuccessFunc('SuccessOperation');
        }
        else {
            $this->response->SetRedirect(Html::ActionPath('error', 'index', $model->ErrorReporting()));
        }
    }
}