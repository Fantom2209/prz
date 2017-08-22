<?php
namespace app\core;

class ViewModule{
    private $name;
    private $data;
    private $view;

    public function __construct(){
        $this->data = array();
        $this->view = new View();
        $this->name = explode('\\', strtolower(get_class($this)));
        $this->name = end($this->name);
    }

    public function Get($key){
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function Set($key, $value){
        $this->data[$key] = $value;
    }

    public function SetTemplate($file){
        $this->view->Set('template', Config::PATH_MODULES_VIEW . $this->name . Config::PATH_SEPARATOR . $file . '.php');
    }

    public function Show(){
        if (!$this->view->HasTeamplate()) {
            $this->view->Set('template', Config::PATH_MODULES_VIEW . $this->name . Config::PATH_SEPARATOR . 'main.php');
        }
        require_once($this->view->Get('template'));
    }
}