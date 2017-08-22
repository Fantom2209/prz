<?php
namespace app\lib;

use \app\core\Config;

class Widget extends \app\core\Page{

    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->response->SetLayout(Config::PATH_LAYOUT . 'emptyLayout.php');
    }

    public function Index(){
        $widget = $this->response->modules->Add('widget');
    }

}