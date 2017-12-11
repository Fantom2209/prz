<?php

namespace app\lib\modules;

use app\core\Config;
use \app\core\Request;

class Filter extends \app\core\ViewModule {

    const OPERATION_EQUALS = 1;
    const OPERATION_LIKE = 2;
    const OPERATION_LIKE_START = 3;
    const OPERATION_LIKE_END = 4;
    const OPERATION_BETWEEN_START = 5;
    const OPERATION_BETWEEN_END = 6;
    const OPERATION_IN = 6;

    private $name;
    private $key;

    public function __construct(){
        parent::__construct();
        $this->key = 'filterName';
    }

    public function Init(Request $request, $tmp = '', $prefix = 'f__'){
        $this->Set('prefix', $prefix);
        $params = $request->GetAllData();

        if(isset($params[$prefix.$this->key])){
            $this->name = $params[$prefix.$this->key];
            unset($params[$prefix.$this->key]);
        }

        $fList = array();
        foreach($params as $key => $val){
            if(strpos($key,$prefix) === 0){
                $fList[$key] = urldecode($val);
            }
        }

        $this->Set('values', $fList);
        if(!empty($tmp)){
            $this->SetTemplate($tmp);
        }
        $this->CreateMeta();
    }

    private function CreateMeta(){
        $items = $this->Get('values');
        $sql = '';
        $data = array();
        if(count($items) > 0){
            $meta = $this->GetSqlItem($items);
            foreach($meta as $item){
                if(!empty($sql)){
                    $sql .= ' AND ';
                }

                $sql .= $item['sql'];
                foreach ($item['values'] as $val){
                    $data[] = $val;
                }
            }
        }

        $this->Set('dbMeta', array('pattern' => $sql, 'data' => $data));
    }

    private function GetSqlItem($items){
        $data = array();
        $config = Config::FILTERS_OPTIONS[$this->name];

        foreach($items as $key => $val) {
            if($val == 'all' || $val === ''){
                continue;
            }
            $key = substr($key, strlen($this->Get('prefix')));

            $operation = empty($config[$key]['operation']) ? self::OPERATION_EQUALS : $config[$key]['operation'];
            $field = empty($config[$key]['field']) ? $key : $config[$key]['field'];
            $mod = empty($config[$key]['mod']) ? '' : $config[$key]['mod'];

            if(!isset($data[$field]['values'])){
                $data[$field]['values'] = array();
            }

            $sqlField = '`'.$field.'`';

            switch($mod){
                case Config::FILTER_MODE_DELETE_SPACES :
                    $sqlField = 'REPLACE('.$sqlField.', " ", "" )';
                    $val = str_replace(' ', '', $val);
                    break;
            }

            switch($operation){
                case self::OPERATION_LIKE :
                    $data[$field]['sql'] = $sqlField . ' LIKE ?';
                    $data[$field]['values'][] = '%'.$val.'%';
                    break;
                case self::OPERATION_LIKE_START :
                    $data[$field]['sql'] = $sqlField . ' LIKE ?';
                    $data[$field]['values'][] = '%'.$val;
                    break;
                case self::OPERATION_LIKE_END :
                    $data[$field]['sql'] = $sqlField . ' LIKE ?';
                    $data[$field]['values'][] = $val.'%';
                    break;
                case self::OPERATION_BETWEEN_START :
                    $data[$field]['sql'] = '('. $sqlField . ' BETWEEN ?';
                    $data[$field]['values'][] = $val.' 00:00:00';
                    break;
                case self::OPERATION_BETWEEN_END :
                    $data[$field]['sql'] .= ' AND ?)';
                    $data[$field]['values'][] = $val.' 23:59:59';
                    break;
                case self::OPERATION_EQUALS :
                default:
                    $data[$field]['sql'] = $sqlField . ' = ?';
                    $data[$field]['values'][] = $val;
            }

        }

        return $data;
    }

    public function GetParamList(){
        $p = $this->Get('values');
        return !empty($p) ? $p : array();
    }

    /*public function CreateOperationConfig($options = array()){
        if(count($options) == 0){
            return '';
        }

        $optionsLine = json_encode($options);

        return '<input type="hidden" name="'.$this->Get('prefix').'filterOptions" value=\''.$optionsLine.'\'>';
    }*/

    public function SetName($name){
        echo '<input type="hidden" name="'.$this->Get('prefix').$this->key.'" value="'.$name.'">';
    }
}