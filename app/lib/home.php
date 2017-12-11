<?php
	namespace app\lib;
	use app\core\UsersManager;
    use \app\lib\modules\Menu;
	
	class Home extends \app\core\Page{

	    public function __construct($controller, $action, $meta){
            parent::__construct($controller, $action, $meta);
        }

        public function Index(){
            $menu = $this->response->modules->Add('menu');
	        $menu->Init(Menu::TYPE_GUEST_MAIN);
            $menu->Set('IsAuth', $this->userManager->IsAuthorized());
            $menu->Set('IsAuth', $this->userManager->IsAuthorized());
            $menu->Set('AccountName', $this->userManager->Get('UserName'));
            $menu->Set('IsAdmin',$this->userManager->InRole(array(UsersManager::GROUP_ADMINISTRATOR)));
            //$this->response->modules->Add('alerts');

			$this->response->Set('title','Главная страница');
			$this->response->Set('content','Информация с главной страницы');
		}

	}