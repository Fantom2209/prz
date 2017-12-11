<?php
namespace app\data;


class Calls extends \app\core\Model{

    private $tSite;

    private $basePattern;
    private $baseValues;

    private $pattern;
    private $values;

    public function __construct(){
        parent::__construct();
        $this->tSite = 'Sites';
        $this->pattern = '';
        $this->values = array();
    }

    public function PrepareMeta($dataUser, $dataAll, $metaDB){
        $this->values = array();

        foreach($dataUser as $val){
            $this->values[] = $val['id'];
        }

        foreach($dataAll as $val){
            $this->values[] = $val;
        }

        $this->pattern = '`site` IN ('. implode(',', array_fill(0, count($this->values), '?')) . ')';

        $this->basePattern = $this->pattern;
        $this->baseValues = $this->values;

        if(!empty($metaDB['data'])) {
            foreach ($metaDB['data'] as $val) {
                $this->values[] = $val;
            }
        }

        if(!empty($metaDB['pattern'])){
            $this->pattern .= ' AND ' . $metaDB['pattern'];
        }
    }

    public function GetCalls($limit = array()){
        if(count($limit) > 0){
            if(isset($limit['start'])){
                $this->Limit($limit['start'],!empty($limit['count']) ? $limit['count'] : null);
            }
        }

        $r = $this->Select(array(
            array('table' => 't1', 'field' => 'id'),
            array('table' => 't2', 'field' => 'url', 'label' => 'site'),

        ))->Where($this->pattern, $this->values)->Binding('LEFT', $this->tSite, 'site', 'id')->Build()->Run(true)->GetAll();

        return $r;
    }

    public function GetSiteList(){
        $r = $r = $this->Distinct()->Select(array(
            array('table' => 't2', 'field' => 'id'),
            array('table' => 't2', 'field' => 'url', 'label' => 'name'),
        ))->Where($this->basePattern, $this->baseValues)->Binding('LEFT', $this->tSite, 'site', 'id')->Build()->Run(true)->GetAll();

        return $r;
    }

    public function GetComment($id){
        $r = $this->GetElementByField('id', $id);
        if(isset($r[0])){
            $r = $r[0];
        }
        return array('id'=> $r['id'], 'comment' => empty($r['comment']) ? '' : $r['comment']);
    }

    public function GetListCols($data, $cols = array()){
        if(count($cols)){
            return array();
        }
        $result = array();
        foreach($data as $item){
            foreach ($item as $key => $val){
                if(in_array($key, $cols)){
                    if(!in_array($val, $result[$key])){
                        $result[$key][] = $val;
                    }
                }
            }
        }
        return $result;
    }

    public function GetCountCalls(){
        return $this->Select()->Where($this->pattern, $this->values)->Build()->Run(true)->CountResult();
    }

}