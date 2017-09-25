<?php
	namespace app\core;
    use app\data\Users;
    use \app\helpers\Loger;

	class Page{

		protected $data;

        protected $request;
        protected $response;

		protected $userManager;

		public function __construct($controller, $action){
			$this->request = new Request();
			$this->response = new Response($controller, $action);
            $this->userManager = new UsersManager();
			if($this->request->GetData('__method__') == 'post'){
                $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
			}
            $this->userManager->SetCurrentUser($this->request->GetActiveUser());
		}

		protected function CheckAccess(){
            if($this->request->GetData('__method__') != 'post') {
                if (!$this->userManager->HasAccess()) {
                    Loger::Write(ErrorInfo::GetMessage(ErrorInfo::ACCESS_DENIED), ErrorInfo::ACCESS_DENIED);
                    $this->response->GenerateError(ErrorInfo::ACCESS_DENIED);
                }
            }
        }

		public function Get($name){
			return $this->data[$name];
		}
		
		public function Set($name, $value){
			return $this->data[$name] = $value;
		}

		public function GetResponse(){
		    $this->response->Go();
        }
	}