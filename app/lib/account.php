<?php
	namespace app\lib;
	
	use app\core\Config;
    use app\core\Response;
    use app\helpers\Loger;
    use \app\helpers\Validator;
	use \app\core\ErrorInfo;
	use \app\data\Users;
	use \app\helpers\Html;
	use \app\helpers\VendorManager;

	class Account extends \app\core\Page{
        private $validator;

        public function __construct($controller, $action){
            parent::__construct($controller, $action);
            $this->validator = new Validator();
        }

        public function CreatePost(){
            $data = $this->request->GetData('UserData');
			$this->validator->Validate($data);

			if(!$this->validator->IsValid()){
			    $this->response->SetError($this->validator->ErrorReporting());
			}
			else{
                $data = Validator::CleanKey($data);
                $user = new Users();

			    if($data['password'] !== $data['confirmPass']){
                    $this->response->SetError(ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_CONFIRM_PASSWORD_NOT_CORRECT,array('confirmPass')));
                }
                elseif(!$user->EmailFree($data['email'])){
                    $this->response->SetError(ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_EMAIL_NOT_FREE,array('email')));
                }
                else {
                    $data['role_id'] = Config::CATEGORY_CLIENT;
                    $data['active'] = 0;
                    $data['hash'] = $user->GenerateActiveCode();
                    $data['password'] = $user->HashPassword($data['password']);
                    $data['register_ip'] = $user->GetIP();

                    unset($data['confirmPass']);
                    $user->Insert($data)->Run();
                    if($user->IsSuccess()){
                        $link = Config::URL_ROOT . Html::ActionPath('account','activate', array($user->GetLastId(), $data['hash']));

                        $mail = VendorManager::GetInstance(VendorManager::MODULE_PHPMAILER);
                        $mail->SetFrom('robot@perezvon.foolsoft.ru', 'Administrator');
                        $mail->addAddress($data['email'], $data['name']);
                        $mail->isHTML(true);
                        $mail->Subject = 'Create account';
                        $mail->Body = '<p>Спасибо за регистрацию на сайте <a href="'. Config::URL_ROOT . Html::ActionPath('home', 'index').'">Perezvonok</a></p><p>Активация аккаунта: <a href="'.$link.'">сюда!</a></p>';
                        $mail->AltBody = 'Текст письма';
                        if(!$mail->send()){
                            Loger::Write($mail->ErrorInfo);
                        }

                        $user->GetUserById($user->GetLastId());
                        $user->Login();
                        $this->response->SetRedirect(Html::ActionPath('profile','index'));
                    }
                    else{
                        $this->response->SetRedirect(Html::ActionPath('error','index', $user->ErrorReporting()));
                    }
                }
			}
		}

		public function SuperLogin(){
            $user = new Users();
            $user->GetUserById($this->request->GetData(0));
            if ($user->IsUser()) {
                $user->SuperLogin($this->userManager->Get('UserId'),$this->userManager->Get('UserName'));
                $this->response->Redirect('profile');
            } else {
                $this->response->GenerateError(ErrorInfo::USER_NOT_FOUND);
            }

        }

		public function LoginPost(){
            $data = $this->request->GetData('UserData');
            $this->validator->Validate($data);

            if(!$this->validator->IsValid()){
                $this->response->SetError($this->validator->ErrorReporting());
            }
            else {
                $recaptcha = $this->request->GetData('g-recaptcha-response');
                $secret = Config::RECAPTCHA_SECRET;
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR'];
                $status = 1;
                if(!empty($recaptcha)) {
                    $curl = curl_init();
                    if(!$curl) {
                        $status = 2;
                    } else {
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
                        $curlData = curl_exec($curl);
                        curl_close($curl);
                        $curlData = json_decode($curlData, true);
                        if($curlData['success']) {
                            $status = 0;
                        }
                    }
                }

                if($status === 1) {
                    $this->response->SetError('Используйте капчу');
                    $this->response->SetErrorFunc('ShowCaptchaError');
                    return;
                }
                else if($status === 2) {
                    $this->response->SetError('Попробуйте еще раз');
                    $this->response->SetErrorFunc('ShowCaptchaError');
                    return;
                }



                $data = Validator::CleanKey($data);
                $user = new Users();
                $user->GetUser($data['email'], $data['password']);
                if ($user->IsUser()) {
                    if($user->IsBan($data['email'])){
                        $this->response->SetRedirect(Html::ActionPath('error', 'index', array(ErrorInfo::USER_BANED)));
                    }
                    else{
                        $user->Login();
                        $this->response->SetRedirect(Html::ActionPath('profile'));
                    }
                } else {
                    $this->response->SetError(ErrorInfo::GetMetaErrorItem(ErrorInfo::USER_NOT_FOUND, array('email')));
                }
            }
        }
		
		public function Logout(){
			Users::Logout();
			$this->response->Redirect();
		}

		public function Ban(){
		    $user = new Users();
		    $user->Baning($this->request->GetData(0), 1);
            if($user->IsSuccess()){
                $this->response->Redirect('admin','index');
            }
            else{
                $this->response->Redirect('error','index', $user->ErrorReporting());
            }
        }

        public function Amnesty(){
            $user = new Users();
            $user->Baning($this->request->GetData(0), 0);
            if($user->IsSuccess()){
                $this->response->Redirect('admin','index');
            }
            else{
                $this->response->Redirect('error','index', $user->ErrorReporting());
            }
        }

        public function Update(){
            $this->response->SetContentType(Response::CONTENT_TYPE_JSON);
            $user = new Users();
            $data = $user->GetUsersList($this->request->GetData(0));
            if($user->IsSuccess()){
                $this->response->SetSuccess($data);
                $this->response->SetSuccessFunc('UpdateUser');
            }
            else{
                $this->response->SetError('Данные не получены!');
            }
        }

        public function UpdatePost(){
            $data = $this->request->GetData('UserData');
            $this->validator->Validate($data);

            if(!$this->validator->IsValid()){
                $this->response->SetError($this->validator->ErrorReporting());
            }
            else {
                $data = Validator::CleanKey($data);
                $user = new Users();
                $id = $data['id'];
                unset($data['id']);
                $user->UpdateUser($data, $id);
                if($user->IsSuccess()){
                    $item = $user->GetUsersList($id);
                    $item = isset($item[0])?$item[0]:array(); // todo
                    $isBan = $item['ban'] == '1';
                    $classBan = $isBan ? 'ban-item' : '';
                    $html = Html::Snipet('UserLine', array(
                        $classBan, $item['id'], $item['name'], $item['email'], $item['role_name'],
                        Html::ActionPath('account', 'update', array($item['id'])),
                        Html::ActionPath('account', $isBan ? 'amnesty' : 'ban', array($item['id'])),
                        ($isBan ? 'Помиловать' : 'Бан'),
                        Html::ActionPath('account', 'superlogin', array($item['id']))
                    ));
                    $this->response->SetSuccess($html);
                    $this->response->SetSuccessFunc('UpdateLine');
                }
                else{
                    $this->response->SetError('Ошибка при обновлении!');
                }
            }
        }

        public function RolePost(){
            $user = new Users();
            $data = $user->GetRole();
            if($user->IsSuccess()){
                $this->response->SetSuccess($data);
                //$this->response->SetSuccessFunc('CreateSelectRole');
            }
            else{
                $this->response->SetError('Данные не получены!');
            }
        }

        public function Upcast(){
            $id = $this->userManager->Get('BaseId');
            Users::Logout();
            $user = new Users();
            $user->GetUserById($id);
            $user->Login();
            if($user->IsSuccess()){
                $this->response->Redirect('admin', 'index');
            }
            else{
                $this->response->GenerateError(array(ErrorInfo::USER_NOT_FOUND));
            }
        }

        public function Activate(){
            $user = new Users();
            $user->Activate($this->request->GetData(0),$this->request->GetData(1));
            if($user->IsSuccess()){
                $this->response->Redirect('profile', 'index');
            }
            else{
                $this->response->Redirect('error', 'index', $user->ErrorReporting());
            }
        }

	}