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

    public function GetPropertyBySite($site, $property){

        return 'data';
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
                    array('table' => 't1', 'field' => 'name', 'label' => 'title'),
                    array('table' => 't2', 'field' => 'name', 'label' => 'type'),
                    'dop',
                    'system',
                    array('table' => 't4', 'field' => 'value'),
                    array('table' => 't4', 'field' => 'id', 'label' => 'valueId'),
                )
            )->
            Binding('LEFT', $this->tType, 'type', 'id')->
            Binding('LEFT', $this->tGroup, 'sGroup', 'id')->
            Binding('LEFT', $this->tValue, 'id', 'property_id')->
            Where('`t1`.`active` = ? AND (`t4`.`site_id` = ? or `t4`.`site_id` IS NULL)', array($type, $site))->Build()->Run()->GetAll();

        /*$data = array();
        foreach($r as $item){
            if(isset($data[$item['id']])){
                if(isset($data[$item['id']]['id'])){
                    $data[$item['id']] = array($data[$item['id']]);
                }
                $data[$item['id']][] = $item;
            }
            else{
                $data[$item['id']] = $item;
            }
        }*/

        return $r;
    }


}