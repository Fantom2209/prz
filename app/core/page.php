<?php
	namespace app\core;
    use app\data\Users;
    use \app\helpers\Loger;
    use \app\helpers\Html;

	class Page{

		protected $data;

        protected $request;
        protected $response;

		protected $userManager;

		public function __construct($controller, $action, $meta){
			$this->request = new Request();
			$this->response = new Response($controller, $action);
            $this->userManager = new UsersManager();
			if($this->request->GetData('__method__') == 'post'){
                $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
			}

			$this->response->ChechContentType($meta); // задать новый тип контента, если он задан в анотации вызываемого метода
			$this->CheckAccess($meta);
		}

		public function CheckAccess($meta){

		    if($this->userManager->IsAuthorized()){
		        if(!$this->userManager->HasRealUser()){
                    Loger::Write(ErrorInfo::GetMessage(ErrorInfo::USER_COOKIES_MODIFIED) . ' (session_id not correct)', ErrorInfo::USER_COOKIES_MODIFIED);
                    $user = new Users();

                    if($this->userManager->IsSuperUser()){
                        $user->Logout(true);
                    }
                    else{
                        $user->Logout();
                    }

                    if($this->response->GetContentType() == Response::CONTENT_TYPE_JSON){
                        $this->response->SetRedirect(Html::ActionPath('error', 'index', array(ErrorInfo::USER_COOKIES_MODIFIED)));
                        $this->response->Go();
                    }
                    else{
                        $this->response->GenerateError(ErrorInfo::USER_COOKIES_MODIFIED);
                    }
                }
            }

		    if (!$this->userManager->HasAccess($meta)) {
                Loger::Write(ErrorInfo::GetMessage(ErrorInfo::ACCESS_DENIED), ErrorInfo::ACCESS_DENIED);
                if($this->response->GetContentType() == Response::CONTENT_TYPE_JSON){
                    $this->response->SetError(ErrorInfo::GetMessage(ErrorInfo::ACCESS_DENIED_FOR_RESOURCES));
                    $this->response->SetErrorFunc('ErrorAlert');
                    $this->response->Go();
                }
                else{
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