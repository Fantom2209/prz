<?php
namespace app\lib;
use \app\data\Users;
use \app\data\Sites;
use \app\lib\modules\Menu;
use \app\data\Properties;
use \app\core\UsersManager;


/**
 * @group(ADMINISTRATOR)
 */
class Admin extends \app\core\Page{

    public function __construct($controller, $action){
        parent::__construct($controller, $action);

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
        
        $this->response->Set('properties',$site->GetProperties());
        $this->response->Set('PropertiesType',$propertiesType);
        $this->response->Set('PropertiesGroup',$propertiesGroup);
        $this->response->Set('title','Настройка свойств сайтов');
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
}