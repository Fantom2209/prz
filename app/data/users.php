<?php
	namespace app\data;
	use \app\core\config;
    use app\core\ErrorInfo;

    class Users extends \app\core\Model{

        const GROUP_ADMINISTRATOR = 1;
        const GROUP_CLIENT = 2;
        const GROUP_GUEST = 0;
        const GROUP_DEVELOPER = 3;

	    private $id;
	    private $email;
	    private $role;

	    private $roleTable;

	    public function __construct() {
	        parent::__construct();
	        $this->roleTable = 'Role';
        }

        public function EmailFree($email){
            return 0 == $this->GetCount($email, 'email');
        }

	    public function HashPassword($pass){
	        return MD5(MD5($pass . Config::APP_SECRET));
        }

        public function GetUser($email, $password){
	        $data = $this->Select(array('id', 'email', 'name', 'role_id'))->Where('`email` = ? and `password` = ?', array($email, $this->HashPassword($password)))->Build()->Run()->GetNext();
	        $this->SetUser($data);
        }

        public function GetUserById($id){
            $data = $this->Select(array('id', 'email', 'name', 'role_id'))->Where('`id` = ?', array($id))->Build()->Run()->GetNext();
            $this->SetUser($data);
        }


        private function SetUser($data){
            $this->id = !empty($data['id']) ? $data['id'] : '';
            $this->email = !empty($data['email']) ? $data['email'] : '';
            $this->name = !empty($data['name']) ? $data['name'] : '';
            $this->role = !empty($data['role_id']) ? $data['role_id'] : '';
        }

        public function IsUser(){
            return $this->id != false;
        }

        public function Login(){
            $time = time()+ 3600 * 24 * 7;
            setcookie('UserId', $this->id, $time,'/');
            setcookie('UserName', $this->name, $time, '/');
            setcookie('UserRole', $this->role, $time, '/');
        }

        public function SuperLogin($id, $name){
            setcookie('BaseId', $id, time()+ 3600 * 24 * 7,'/');
            setcookie('BaseName', $name, time()+ 3600 * 24 * 7,'/');
            $this->Login();
        }

        public static function Logout(){
            setcookie('UserId', '', time() - 3600, '/');
            setcookie('BaseId', '', time() - 3600, '/');
            setcookie('BaseName', '', time() - 3600, '/');
            setcookie('UserName', '', time() - 3600, '/');
            setcookie('UserRole', '', time() - 3600, '/');
        }

        public static function InRole($role = array()){
            $active = self::ActiveUserInfo('UserRole');
            return in_array($active ? $active : 0, $role);
        }

        public static function IsAuthorized(){
            return $_COOKIE['UserId'] != false;
        }

        public static function ActiveUserInfo($key){
            return $_COOKIE[$key];
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
                    array('table'=>$this->GetCurrentTableName(),'field'=>'id'),
                    'email',
                    array('table'=> $this->GetCurrentTableName(),'field'=>'name'),
                    'role_id',
                    array('table'=>'Role', 'field'=>'name', 'label'=>'role_name'),
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