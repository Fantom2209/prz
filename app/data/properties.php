<?php
namespace app\data;

use \app\helpers\Functions;

class Properties extends \app\core\Model{

    private $tProperties;
    private $tValue;
    private $tType;
    private $tGroup;
    private $vGroup;
    private $tParam;

    public function __construct()
    {
        parent::__construct();
        $this->tProperties = 'SiteProperties';
        $this->tValue = 'PropertiesValue';
        $this->tType = 'PropertyType';
        $this->tGroup = 'PropertyGroup';
        $this->vGroup = 'PropertyValueGroup';
        $this->tParam = 'PropertyParam';
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
            $data[$item['idP']][] = $item;
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

    public function GetPropertyTagById($tag, $id){
        $r = $this->Select(
            array(
                array('table' => 't1', 'field' => 'dop'),
            )
        )->
        Where('`t1`.`id` = ?', array($id))->
        Build()->Run()->GetNext();

        $data = $this->DecodeParams($r['dop']);
        return isset($data[$tag]) ? $data[$tag] : '';
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

    public function GetPropertyInfoByCodeName($name){
        $r = $this->Select()->
        Where('`t1`.`dop` LIKE ?', array('%codeName='.$name.'%'))->
        Build()->Run()->GetNext();

        return isset($r['id']) ? $r : '';
    }

    public function GetPropertiesBySite($site, $type){
        $r = $this->Select(
            array(
                array('table' => 't1', 'field' => 'id'),
                array('table' => 't1', 'field' => 'name', 'label' => 'name'),
                array('table' => 't2', 'field' => 'name', 'label' => 'typeName'),
                array('table' => 't1', 'field' => 'dop'),
                'system',
                'empty',
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
                if(isset($x[1])){
                    $result[trim($x[0])] = trim($x[1]);
                }
            }
        }
        return $result;
    }

    public function EncodeParams($data){
        $str = '';
        foreach($data as $key => $item){
            if($item === '0' || !empty($item)){
                if(!empty($str)){
                    $str .= ';';
                }
                $str .= $key . '=' . $item;
            }
        }
        return $str;
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
        $r = $this->Clear()->Select(array(
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

    public function GetParamsList(){
        $this->SetTable($this->tParam);
        $r = $this->Select()->Build()->Run(true)->GetAll();
        $this->DefaultTable();
        return $r;
    }

    public function AddParams($data){
        $this->SetTable($this->tParam);
        if(!$data) {
            $data = array();
        }
        foreach($data as $key => $val){
            $this->Insert(array('name' => $key))->Run();
        }
        $this->Clear()->DefaultTable();
    }

    public function GetManagersList($data){
        $data = !empty($data['Настройка звонков'][2]['items']) ? $data['Настройка звонков'][2]['items'] : array();

        $result = array();
        foreach($data as $item){
            if(empty($item['info']['dop']['codeName']) || empty($item['values'])){
                continue;
            }

            $i = 0;
            foreach($item['values'] as $val){
                if(!isset($result[$i])){
                    $result[$i] = array();
                }
                $result[$i++][$item['info']['dop']['codeName']] = $val['value'];
            }
        }
        return $result;
    }

    public function GetBranchList($data){
        $data = $this->GetManagersList($data);
        $result = array();
        foreach($data as $item){
            $key = empty($item['branch']) ? 'default' : Functions::ChpuUrl($item['branch']);
            if(empty($result[$key]['tz'])){
                $result[$key]['tz'] = $item['timezone'];
            }
            if(empty($result[$key]['ru'])){
                $result[$key]['ru'] = $item['branch'];
            }

            $result[$key]['managers'][] = $item;
        }
        return $result;
    }

    public function GetRandomManager($branch = 'default', $data = array()){

        if(empty($data[$branch]) || ($countManagers = count($data[$branch]['managers'])) === 0){
            return null;
        }

        return $data[$branch]['managers'][$countManagers - 1 == 0 ? 0 : rand(0,$countManagers - 1)];
    }

    public function GetRandomManagerWithEmail($branch = 'default', $data = array()){
        if(empty($data[$branch]) || ($countManagers = count($data[$branch]['managers'])) === 0){
            return null;
        }

        shuffle($data[$branch]['managers']);

        foreach($data[$branch] as $item){
            if(!empty($item['email'])){
                return $item;
            }
        }

        return array();
    }

    public function GetBranchListWithEmail($data = array()){

        if(count($data) == 0){
            return array();
        }

        $result = array();

        foreach($data as $key => $item){
            foreach($item as $subItem){
                if(!empty($subItem['email'])){
                    $result[] = array('value' => $key, 'title' => !empty($subItem['branch']) ? $subItem['branch'] : 'Без филиала');
                }
            }
        }

        return $result;
    }

    private function GetSettingList($data){
        $result = array();

        foreach($data as $item){
            if(empty($item['info']['dop']['codeName']) || empty($item['values'])){
                continue;
            }

            foreach($item['values'] as $val){
                $result[$item['info']['dop']['codeName']] = $val['value'];
            }
        }

        return $result;
    }

    public function GetBtnSettings($data = array()){
        $data = !empty($data['Внешний вид кнопки заказа']) ? $data['Внешний вид кнопки заказа'] : array();
        $settingsList = array();

        foreach($data as $group){
            foreach($group['items'] as $item){
                $settingsList[] = $item;
            }
        }

        return $this->GetSettingList($settingsList);
    }

    public function GetReportsSettings($data = array()){
        $data = !empty($data['Отчеты и уведомления'][1]['items']) ? $data['Отчеты и уведомления'][1]['items'] : array();
        return $this->GetSettingList($data);
    }

    public function GetWindowSettings($data = array()){
        $data = !empty($data['Внешний вид всплывающего окна']) ? $data['Внешний вид всплывающего окна'] : array();
        $settingsList = array();

        foreach($data as $group){
            foreach($group['items'] as $item){
                $settingsList[] = $item;
            }
        }

        return $this->GetSettingList($settingsList);
    }

    public function GetSettingsOpen($data){
        $data = !empty($data['Автоматическое всплывание окон'][1]['items']) ? $data['Автоматическое всплывание окон'][1]['items'] : array();
        return $this->GetSettingList($data);
    }

    public function GetSettingsText($data){
        $data = !empty($data['Тексты во всплывающем окне'][1]['items']) ? $data['Тексты во всплывающем окне'][1]['items'] : array();
        return $this->GetSettingList($data);
    }

    public function GetSettingsFilter($data){
        $data = !empty($data['Фильтры'][1]['items']) ? $data['Фильтры'][1]['items'] : array();
        return $this->GetSettingList($data);
    }

    public function GetPropertiesGroup(){
        $this->SetTable($this->tGroup);
        $this->Select()->Build()->Run(true);
        $this->SetTable($this->tProperties);
        return $this->GetAll();
    }

}