<?php
namespace app\data;

class Properties extends \app\core\Model{

    private $tProperties;
    private $tValue;
    private $tType;
    private $tGroup;
    private $vGroup;

    public function __construct()
    {
        parent::__construct();
        $this->tProperties = 'SiteProperties';
        $this->tValue = 'PropertiesValue';
        $this->tType = 'PropertyType';
        $this->tGroup = 'PropertyGroup';
        $this->vGroup = 'PropertyValueGroup';
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
        Where('`t1`.`active` = ? AND `t2`.`site_id` = ?', array($type, $site))->OrderBy(array('`t2`.`id`'))->Build()->Run()->GetAll();

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
            Binding('LEFT', $this->tValue, 'id', 'property_id')->
            Where('`t1`.`dop` LIKE ? AND `t2`.`site_id` = ?', array('%name='.$name.'%', $site))->Build()->Run()->GetNext();
        return isset($r['value'])?$r['value']:'';
    }

    public function GetPropertyTagByName($tag, $name){
        $r = $this->Select(
            array(
                array('table' => 't1', 'field' => 'dop'),
            )
        )->
        Where('`t1`.`dop` LIKE ?', array('%name='.$name.'%'))->
        Build()->Run()->GetNext();

        $data = $this->DecodeParams($r['dop']);
        return isset($data[$tag]) ? $data[$tag] : '';
    }

    public function GetPropertiesBySite($site, $type){
        $r = $this->Select(
            array(
                array('table' => 't1', 'field' => 'id'),
                array('table' => 't1', 'field' => 'name', 'label' => 'name'),
                array('table' => 't2', 'field' => 'name', 'label' => 'typeName'),
                array('table' => 't1', 'field' => 'dop'),
                'system',
                array('table' => 't3', 'field' => 'name', 'label' => 'group'),
                array('table' => 't1', 'field' => 'vGroup', 'label' => 'vgId'),
                array('table' => 't4', 'field' => 'dop', 'label' => 'vgParam'),
            )
        )->
        Binding('LEFT', $this->tType, 'type', 'id')->
        Binding('LEFT', $this->tGroup, 'sGroup', 'id')->
        Binding('LEFT', $this->vGroup, 'vGroup', 'id')->
        Where('`t1`.`active` = ?', array($type))->OrderBy(array('`t1`.`sGroup`','`t4`.`position`','`t1`.`id`'))->Build()->Run(true)->GetAll();

        $v = $this->GetValuesBySite($site, $type);

        $data = array();
        foreach($r as $item){
            $group = $item['group'];
            $vGroup = $item['vgId'];
            $item['dop'] = $this->DecodeParams($item['dop']);
            $arr['info'] = $item;
            if(isset($v[$item['id']])){
                $arr['values'] = $v[$item['id']];
            }
            else{
                $arr['values'] = array();
            }
            $data[$group][$vGroup]['items'][] = $arr;

            if(!isset($data[$group][$vGroup]['param'])){
                $data[$group][$vGroup]['param'] = $this->DecodeParams($item['vgParam']);
            }
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
                $x = explode('@',$key);
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

    public function DeleteValue($data){
        $this->SetTable($this->tValue);
        $this->Delete()->Where('`id` = ?', array())->Build();
        foreach($data as $key => $val){
            $k = explode('-',$key);
            if(isset($k[1])){
                $this->SetOperData(array($k[1]))->Run();
            }
        }
    }
}