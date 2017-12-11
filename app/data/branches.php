<?php
namespace app\data;


class Branches extends \app\core\Model {

    public function __construct(){
        parent::__construct();
    }

    public function GetBranchesByUser($id){
        return $this->Select()->Where('`user_id` = ?', array($id))->Build()->Run(true)->GetAll();
    }

    public function GetBranches($id = ''){
        $this->Select();
        if(!empty($id)){
            $this->Where('`id` = ?', array($id));
        }

        return $this->Build()->Run(true)->GetAll();
    }

}