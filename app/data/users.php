<?php
	namespace app\data;
	use \app\core\config;
    use app\core\ErrorInfo;

    class Users extends \app\core\Model{

	    private $id;
	    private $email;
	    private $role;

	    private $current;

	    private $roleTable;

	    public function __construct() {
	        parent::__construct();
	        $this->roleTable = 'Role';
        }

        public function EmailFree($email){
            return 0 == $this->GetCount($email, 'email');
        }

        public function GetSessionId($id){
            return MD5($this->GetIp() . time() . $id . Config::APP_SECRET);
        }

	    public function HashPassword($pass){
	        return MD5(MD5($pass . Config::APP_SECRET));
        }

        private function SetSessionId($idUser, $idSession){
	        $this->Update(array('session_id'=>$idSession), '`id` = ?', array($idUser))->Run(true);
        }

        public function GetUser($email, $password){
	        $data = $this->Select(array('id', 'email', 'name', 'role_id', 'session_id'))->Where('`email` = ? and `password` = ?', array($email, $this->HashPassword($password)))->Build()->Run()->GetNext();
	        $this->SetUser($data);
        }

        public function GetUserById($id){
            $data = $this->Select(array('id', 'email', 'name', 'role_id', 'session_id'))->Where('`id` = ?', array($id))->Build()->Run(true)->GetNext();
            $this->SetUser($data);
        }


        private function SetUser($data){
            $this->current = $data;
        }

        public function Get($key){
            return isset($this->current[$key])?$this->current[$key]:'';
        }

        public function IsUser(){
            return !empty($this->Get('id'));
        }

        public function Login($super = false){
            $time = time()+ 3600 * 24 * 7;
            if(!$super){
                $session = $this->GetSessionId($this->Get('id'));
                $this->SetSessionId($this->Get('id'), $session);
                setcookie('Session', $session, $time,'/');
            }
            setcookie('UserId', $this->Get('id'), $time,'/');
            setcookie('UserName', $this->Get('name'), $time, '/');
        }

        public function SuperLogin($id, $name){
            setcookie('BaseId', $id, time()+ 3600 * 24 * 7,'/');
            setcookie('BaseName', $name, time()+ 3600 * 24 * 7,'/');
            $this->Login(true);
        }

        public function Logout(){
            if(self::ActiveUserInfo('BaseId')){
                $this->SetSessionId(self::ActiveUserInfo('BaseId'), '');
            }else{
                $this->SetSessionId(self::ActiveUserInfo('UserId'), '');
            }

            setcookie('UserId', '', time() - 3600, '/');
            setcookie('BaseId', '', time() - 3600, '/');
            setcookie('BaseName', '', time() - 3600, '/');
            setcookie('UserName', '', time() - 3600, '/');
            setcookie('Session', '', time() - 3600, '/');
        }

        /*public static function InRole($role = array()){
            $active = self::ActiveUserInfo('UserRole');
            return in_array($active ? $active : 0, $role);
        }*/

        public static function IsAuthorized(){
            return  !empty(self::ActiveUserInfo('UserId'));
        }

        public static function ActiveUserInfo($key){
            return !empty($_COOKIE[$key]) ? $_COOKIE[$key] : null;
        }

        public static function GetIP(){
            return $_SERVER['REMOTE_ADDR'];
        }

        public function UpdateUser($data, $id){
            $this->Update($data, '`id` = ?', array($id))->Run();
        }

        public function GetUsersList($id = null){
            $this->Select(
                array(
                    array('table'=>'t1','field'=>'id'),
                    'email',
                    array('table'=>'t1','field'=>'name'),
                    'role_id',
                    array('table'=>'t2', 'field'=>'name', 'label'=>'role_name'),
                    'ban',
                    'phone'
                )
            )->Binding(
                'LEFT', 'Role','role_id', 'id'
            );

            if($id){
                $this->Where('`'.$this->prefix.$this->table.'`.`id` = ?', array($id));
            }

            return $this->Build()->Run()->GetAll();
        }

        public function Baning($id, $val){
            $this->Update(array('ban'=>$val),'id = ?', array($id))->Run();
        }

        public function GetRole(){
            $sql = 'SELECT * FROM `'.$this->prefix.$this->roleTable.'`';
            return $this->Query($sql)->Run()->GetAll();
        }

        public function IsBan($email){
            $result = $this->Select(array('ban'))->Where('`email` = ?', array($email))->Build()->Run()->GetNext();
            return $result['ban'] == '1';
        }

        public function GenerateActiveCode(){
            return md5(time());
        }

        public function Activate($id, $code){
            if($this->Select()->Where('`id` = ? AND `hash` = ?', array($id, $code))->Build()->Run()->CountResult() > 0){
                $this->Update(array('active' => '1'))->Run();
            }
            else{
                $this->AddError(ErrorInfo::USER_NOT_ACTIVATE);
            }

        }

        public function IsActive($id){
            return $this->Select()->Where('`id` = ? AND `active` = 1', array($id))->Build()->Run()->CountResult() > 0;
        }
	}