<?php
	namespace app\lib;
	use \app\core\ErrorInfo;
    use \app\core\Config;


	class Error extends \app\core\Page{
					
		public function Index(){

		    $this->response->SetLayout(Config::PATH_LAYOUT . 'errorLayout.php');
			$this->response->Set('title','Произошла ошибка');
			$this->response->Set('code','Ошибка с кодом: ' . $this->request->GetData(0));
			$this->response->Set('msg','Сообщение: ' . ErrorInfo::GetMessage($this->request->GetData(0)));
		}

	}