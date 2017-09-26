<?php
namespace app\core;

use app\data\Users;

class Request
{
    private $data;

    public function __construct(){
        if(strtolower($_SERVER['REQUEST_METHOD']) !== 'post') {
            $elementURI = explode('/', $_GET['route']);
            $c = count($elementURI);
            $result = array();
            if($c > 2){
                for($i = 2; $i < $c; $i++){
                    if(!empty($elementURI[$i])){
                        if(strpos($elementURI[$i],':') !== false){
                            $x = explode(':',$elementURI[$i]);
                            $result[$x[0]] = $x[1];
                        }
                        else{
                            $result[] = $elementURI[$i];
                        }
                    }
                }
            }
        }
        else{
            $result = $_POST;
        }
        $this->data = $result;
        $this->data['__method__'] = strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function GetAllData(){
        return $this->data;
    }

    public function GetData($key){
        return isset($this->data[$key])?$this->data[$key]:null;
    }

    /*public function IsAuthorized(){
        return isset($_COOKIE['UserId']);
    }

    public function IsSuperUser(){
        return isset($_COOKIE['BaseId']);
    }

    public function GetActiveUser(){
        $result = array();
        if($this->IsAuthorized()){
            if($this->IsSuperUser()){
                $result['BaseId'] = $_COOKIE['BaseId'];
                $result['BaseName'] = $_COOKIE['BaseName'];
            }
            $result['UserId'] = $_COOKIE['UserId'];
            $result['UserName'] = $_COOKIE['UserName'];
            $result['UserRole'] = $_COOKIE['UserRole'];
        }else{
            $result['UserRole'] = Users::GROUP_GUEST;
            $result['UserName'] = 'Гость';
        }
        $result['UserIP'] = Users::GetIP();
        return $result;
    }*/

}