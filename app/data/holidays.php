<?php
    namespace app\data;

    use \app\helpers\Functions;

    class Holidays extends \app\core\Model {

        private $data;

        public function __construct()
        {
            parent::__construct();
        }

        public function Get(){
            return $this->Select()->Build()->Run()->GetNext();
        }

        public function UpdateOrInsert($id, $data){
            $count = $this->GetCount($id, 'id');
            if($count === "0"){
                $this->Insert($data)->Run(true);
            }
            else{
                $this->Update($data, '`id` = ?', array(1))->Run(true);
            }
        }

        public function IsHoliday($now){
            $data = $this->data == null ? $this->Get() : $this->data;
            if(!empty($data['holidays'])){
                $now = explode('.', $now);
                $list = Functions::TextareaToArray($data['holidays']);

                foreach($list as $item){
                    $date = explode('.', $item);
                    $holiday = true;

                    foreach($date as $key => $val){
                        if($val === '*' || $val === $now[$key]){
                            continue;
                        }
                        $holiday = false;
                    }

                    if($holiday){
                        return true;
                    }
                }
            }
            return false;
        }

    }