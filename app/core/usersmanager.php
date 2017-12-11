<?php
namespace app\core;
use \app\data\Users;

class UsersManager{

    const GROUP_ADMINISTRATOR = 1;
    const GROUP_CLIENT = 2;
    const GROUP_GUEST = 0;
    const GROUP_DEVELOPER = 3;

    private $current;

    public function __construct(){
        $this->SetCurrentUser($this->GetActiveUser());
    }

    public function Get($key){
        return isset($this->current[$key])?$this->current[$key]:'';
    }

    public function SetCurrentUser($user){
        $this->current = $user;
    }

    public function InRole($role = array()){
        return in_array($this->current['UserRole'], $role);
    }

    public function HasRealUser(){
        return !empty($this->Get('ServerSession')) && $this->Get('UserSession') === $this->Get('ServerSession');
    }

    public function HasAccess($meta){
        $role = '';

        if(!empty($meta['method']['group'])){
            $role = $meta['method']['group'];
        }
        elseif (!empty($meta['class']['group'])){
            $role = $meta['class']['group'];
        }
        $role = str_replace(array('(',')'), '', $role);
        $role = explode(',', $role);

        $accessGroup = array();
        foreach($role as $item){
            $item = trim($item);
            if(defined('self::'. 'GROUP_'.$item)){
                $accessGroup[] = constant('self::'. 'GROUP_'.$item);
            }
        }

        if(count($accessGroup) == 0){
            return true;
        }

        return $this->InRole($accessGroup);
    }

    public function IsActive(){
        $user = new Users();
        return $user->IsActive($this->current['UserId']);
    }

    public function IsAuthorized(){
        return self::ActiveUserInfo('UserId') != null;
    }

    public function IsSuperUser(){
        return self::ActiveUserInfo('BaseId') != null;
    }

    public function GetActiveUser(){
        $result = array();
        if($this->IsAuthorized()){
            if($this->IsSuperUser()){
                $result['BaseId'] = self::ActiveUserInfo('BaseId');
                $result['BaseName'] = self::ActiveUserInfo('BaseName');
            }
            $result['UserId'] = self::ActiveUserInfo('UserId');
            $result['UserName'] = self::ActiveUserInfo('UserName');
            $result['UserSession'] = self::ActiveUserInfo('Session');

            $user = new Users();
            $idUser = $this->IsSuperUser() ? $result['BaseId'] : $result['UserId'];

            $user->GetUserById($idUser);
            if($user->IsUser()){
                $result['UserRole'] = $user->Get('role_id');
                $result['ServerSession'] = $user->Get('session_id');
            }
        }else{
            $result['UserRole'] = self::GROUP_GUEST;
            $result['UserName'] = 'Гость';
        }
        $result['UserIP'] = Users::GetIP();
        return $result;
    }

    public static function ActiveUserInfo($key){
        return !empty($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }
}