<?php
    namespace app\lib;
    use \app\data\Users;
    use \app\lib\modules\Menu;
    use \app\data\Sites;

    class Profile extends \app\core\Page{

        public function __construct($controller, $action)
        {
            parent::__construct($controller, $action);
            $this->userManager->DeleteUserGroup(array(Users::GROUP_GUEST));
            $this->CheckAccess();
            $this->response->Set('AccountName', $this->userManager->Get('UserName'));

            $user = new Users();

            $user_info = $user->GetElementByField('id', $this->userManager->Get('UserId'));
            $user_info = isset($user_info[0])?$user_info[0]:$user_info;
            $menu = $this->response->modules->Add('menu');
            $menu->Init(Menu::TYPE_CLIENT_MAIN);
            $menu->SetBrand('Личный кабинет');

            $menu->Set('Paid', $user_info['paid']);
            $menu->Set('IsAuth', $this->request->IsAuthorized());
            $menu->Set('AccountName', $this->userManager->Get('UserName'));
            $menu->Set('IsAdmin',$this->userManager->InRole(array(Users::GROUP_ADMINISTRATOR)));
            $menu->Set('IsActive', $this->userManager->IsActive());
            if($this->request->IsSuperUser()){
                $menu->Set('IsSuperUser', $this->request->IsSuperUser());
                $menu->Set('SuperAccountName', $this->userManager->Get('BaseName'));
            }

        }

        public function Index(){
            $site = new Sites();
            $result = $site->GetElementByField('id_user', $this->userManager->Get('UserId'));
            $properties = $site->GetProperties('yes');

            $mp = array();
            foreach($properties as $item){
                $item['dop'] = $site->DecodeParams($item['dop']);
                $mp[$item['group']][] = $item;
            }

            $widget = $this->response->modules->Add('widget');
            $widget->SetEditMode();

            $widget->Set('propertiesEmpty', count($properties) == 0);
            $widget->Set('Properties',$mp);
            $widget->Set('IsAdmin',$this->userManager->InRole(array(Users::GROUP_ADMINISTRATOR)));
            $widget->Set('IsSuperUser', $this->request->IsSuperUser());

            $this->response->Set('sitesEmpty',count($result) == 0);
            $this->response->Set('Data',$result);
            $this->response->Set('UserId', $this->userManager->Get('UserId'));
            $this->response->Set('title','Личный кабинет');
        }

    }