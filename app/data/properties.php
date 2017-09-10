<?php
namespace app\data;

class Properties extends \app\core\Model{

    private $tProperties;
    private $tValue;
    private $tType;
    private $tGroup;

    public function __construct()
    {
        parent::__construct();
        $this->tProperties = 'SiteProperties';
        $this->tValue = 'PropertiesValue';
        $this->tType = 'PropertyType';
        $this->tGroup = 'PropertyGroup';
        $this->SetTable($this->tProperties);
    }

    public function GetValuesBySite($site, $type){
        $r = $this->Select(
            array(
                array('table' => 't1', 'field' => 'id', 'label' => 'idP'),
                array('table' => 't2', 'field' => 'id', 'label' => 'idV'),
                array('table' => 't2', 'field' => 'value'),
            )
        )->
        Binding('LEFT', $this->tValue, 'id', 'property_id')->
        Where('`t1`.`active` = ? AND `t2`.`site_id` = ?', array($type, $site))->OrderBy(array('`t1`.`id`'))->Build()->Run()->GetAll();

        $data = array();
        foreach($r as $item){
            if(!isset($data[$item['idP']])){
                $data[$item['idP']] = $item;
            }
            else{
                if(isset($data[$item['idP']]['idP'])){
                    $data[$item['idP']] = array($data[$item['idP']]);
                }
                $data[$item['idP']][] = $item;
            }
        }
        return $data;
    }

    public function GetPropertyValueByName($name, $site){
        $r = $this->Select(
                array(
                    array('table' => 't2', 'field' => 'id'),
                    array('table' => 't2', 'field' => 'value'),
                )
            )->
            Where('`t1`.`dop` LIKE ? AND `t2`.`site_id` = ?', array('%name='.$name.'%', $site))->
            Binding('LEFT', $this->tValue, 'id', 'property_id')->Build()->Run()->GetAll();
        return $r;
    }

    public function GetPropertiesBySite($site, $type){
        $r = $this->Select(
                array(
                    array('table' => 't1', 'field' => 'id'),
                    array('table' => 't1', 'field' => 'name', 'label' => 'name'),
                    array('table' => 't2', 'field' => 'name', 'label' => 'typeName'),
                    'dop',
                    'system',
                    array('table' => 't3', 'field' => 'name', 'label' => 'group'),
                )
            )->
            Binding('LEFT', $this->tType, 'type', 'id')->
            Binding('LEFT', $this->tGroup, 'sGroup', 'id')->
            Where('`t1`.`active` = ?', array($type))->OrderBy(array('`t1`.`sGroup`','`t1`.`id`'))->Build()->Run(true)->GetAll();

        $v = $this->GetValuesBySite($site, $type);

        // иерархия data = [группа][номер свойства][поля с данными] или если свойства дублируеться [группа][номер свойства][индекс][поля с данными]

        $data = array();
        foreach($r as $item){
            $group = $item['group'];
            $item['dop'] = $this->DecodeParams($item['dop']);
            if(isset($v[$item['id']])){
                if(isset($v[$item['id']]['idP'])){
                    $item['idV'] = $v[$item['id']]['idV'];
                    $item['value'] = $v[$item['id']]['value'];
                }
                else{
                    $arr = array();
                    foreach($v[$item['id']] as $val){
                        $item['idV'] = $val['idV'];
                        $item['value'] = $val['value'];
                        $arr[] = $item;
                    }
                    $item = $arr;
                }
            }
            else{
                $item['idV'] = null;
                $item['value'] = null;
            }
            $data[$group][] = $item;
        }

        return $data;
    }

    public function DecodeParams($str){
        $result = array();
        if(!empty($str)) {
            $params = explode(';', $str);
            foreach ($params as $item) {
                $x = explode('=', $item);
                $result[trim($x[0])] = trim($x[1]);
            }
        }
        return $result;
    }

    public function UpdatePropertiesValue($site, $data){

        foreach($data as $key => $val){
            $x = explode('-',$key);
            if(isset($x[1])){
                $sql = 'UPDATE `'.$this->prefix.$this->tValue.'` SET `value` = ? WHERE `id` = ?';
                $this->SetOperData(array($val, $x[1]));
            }
            else{
                $sql = 'INSERT INTO `'.$this->prefix.$this->tValue.'` (`value`, `site_id`, `property_id`) VALUES (?,?,?);';
                $this->SetOperData(array($val, $site, $x[0]));
            }
            $this->Query($sql)->Run();
        }
    }

    public function PrepareCheckBoxes($site, $data){
        $r = $this->Select(array(
            array('table'=>'t1', 'field' => 'id', 'label' => 'idP'),
            array('table'=>'t2', 'field' => 'id', 'label' => 'idV'),
        ))->
        Binding('LEFT',$this->tValue, 'id', 'property_id')->
        Where('`t1`.`type` = ? AND `t2`.`site_id` = ?', array(7, $site))->Build()->Run(true)->GetAll();

        if(!$data){
            $data = array();
        }

        foreach($data as &$item){
            $item = 1;
        }
        unset($item);

        foreach($r as  $item){
            if(!isset($data[$item['idP'].'-'.$item['idV']])){
                $data[$item['idP'].'-'.$item['idV']] = 0;
            }
        }

        return $data;
    }
}