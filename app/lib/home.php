<?php
	namespace app\lib;
	use \app\lib\modules\Menu;
	use \app\data\Users;
	
	class Home extends \app\core\Page{

	    public function __construct($controller, $action){
            parent::__construct($controller, $action);
        }

        public function Index(){
            $menu = $this->response->modules->Add('menu');
	        $menu->Init(Menu::TYPE_GUEST_MAIN);
            $menu->Set('IsAuth', $this->request->IsAuthorized());
            $menu->Set('IsAuth', $this->request->IsAuthorized());
            $menu->Set('AccountName', $this->userManager->Get('UserName'));
            $menu->Set('IsAdmin',$this->userManager->InRole(array(Users::GROUP_ADMINISTRATOR)));
            //$this->response->modules->Add('alerts');

			$this->response->Set('title','Главная страница');
			$this->response->Set('content','Информация с главной страницы');
		}
		
		public function Users(){
			$this->Set('title','Пользователи');
			$this->Set('content','Список пользователей');
		}
		
		public function Test(){
			$this->response->Set('title','Тестовая страница');
			$this->response->Set('content','Страница для тестирования');
		}
		
		public function TestPost(){
			if($this->request->GetData('name') == '404'){
				$this->response->NotFound();
			}
			
			if($this->request->GetData('name') == '1'){
				$this->response->GenerateError(1);
			}
			

			$this->response->Set('content', $this->request->GetData('name'));
		}
		
		public function Test2Post(){		
			echo 'Я по посту 2  ' . $this->Param('name');
		}
	}