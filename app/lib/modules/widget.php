<?php
namespace app\lib\modules;

class Widget extends \app\core\ViewModule {

    private $model;

    public function __construct(){
        parent::__construct();
    }

    public function SetEditMode(){
        $this->SetTemplate('edit');
    }

}