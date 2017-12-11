<?php
namespace app\data;

use \app\helpers\Functions;

class Blacklist extends \app\core\Model {

    public function __construct(){
        parent::__construct();
    }

    public function GetItems($site = null){
        if($site != null){
            $this->Where('`site` = ? OR `site` IS NULL', array($site));
        }
        else{
            $this->Where('`site` IS NULL');
        }

        return $this->Select()->Build()->Run()->GetAll();
    }

    public function UpdateList($data, $site = null){
        if($site == null){
            $this->Update($data, '`site` IS NULL')->Run();
        }
        else{
            $this->Update($data, '`site` = ?', $site)->Run();
        }
    }

    public function CheckPhone($site, $phone){
        $data = $this->GetItems($site);
        $phone = str_replace(array("\t", "\r", "\n", ' '), '', $phone);
        foreach ($data as $item){
            $phones = Functions::TextareaToArray($item['phones']);
            if(in_array($phone, $phones)){
                return false;
            }
        }
        return true;
    }

}