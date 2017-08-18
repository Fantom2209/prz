<?php
namespace app\core;
use \app\data\Users;

class UsersManager{

    private $accessGroups;
    private $current;

    public function __construct(){
        $this->accessGroups = array(
            Users::GROUP_GUEST,
            Users::GROUP_ADMINISTRATOR,
            Users::GROUP_CLIENT,
            Users::GROUP_DEVELOPER
        );
    }

    public function Get($key){
        return isset($this->current[$key])?$this->current[$key]:'';
    }

    public function DeleteUserGroup($data = array()){
        $new = array();
        foreach($this->accessGroups as $item){
            if(in_array($item, $data)){
                continue;
            }
            $new[] = $item;
        }
        $this->accessGroups = $new;
    }

    public function SetCurrentUser($user){
        $this->current = $user;
    }

    public function InRole($role = array()){
        return in_array($this->current['UserRole'], $role);
    }

    public function HasAccess(){
        return $this->InRole($this->accessGroups);
    }

    public function IsActive(){
        $user = new Users();
        return $user->IsActive($this->current['UserId']);
    }

}