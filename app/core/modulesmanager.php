<?php
namespace app\core;

class ModulesManager
{
    private $modules;

    public function __construct(){
        $this->modules = array();
    }

    public function Get($name){
        if(isset($this->modules[$name])){
           return $this->modules[$name];
        }
        return null;
    }

    public function Add($name){
        $m = '\app\lib\modules\\' . $name;
        $this->modules[$name] = new $m();
        return $this->modules[$name];
    }

    public function Clear(){
        // TODO
    }

    public function __GET($module){
        if(isset($this->modules[$module])){
            $this->modules[$module]->Show();
        }
    }
}