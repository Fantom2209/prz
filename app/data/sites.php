<?php
    namespace app\data;

    use \app\core\Config;

    class Sites extends \app\core\Model{

        private $propertiesTable;
        private $propertiesType;
        private $propertiesValueTable;
        private $propertyGroup;

        public function __construct()
        {
            parent::__construct();
            $this->propertiesTable = 'SiteProperties';
            $this->propertiesValueTable = 'PropertiesValue';
            $this->propertiesType = 'PropertyType';
            $this->propertyGroup = 'PropertyGroup';
        }

        public function DeleteSite($id){
            $this->Delete()->Where('`id` = ?', array($id))->Build()->Run();
        }

        public function DeleteProperty($id){
            $sql = 'DELETE FROM `'.$this->prefix.$this->propertiesTable.'` WHERE `id` = ?';
            $this->SetOperData(array($id))->Query($sql)->Build()->Run();
        }

        public function GetPropertiestType(){
            $sql = 'SELECT * FROM `'.$this->prefix.$this->propertiesType.'`';
            return $this->Query($sql)->Run()->GetAll();
        }

        public function GetPropertiesGroup(){
            $sql = 'SELECT * FROM `'.$this->prefix.$this->propertyGroup.'`';
            return $this->Query($sql)->Run()->GetAll();
        }

        public function GetProperties($active = null){
            $sql = 'SELECT `s`.`id`, `s`.`name`, `active`, `s`.`type` AS `typeId`, `t`.`name` AS `typeName`, `dop`, `system`, `g`.`name` as `group`, `g`.`id` as `groupId`  FROM `'.$this->prefix.$this->propertiesTable.'` `s` LEFT JOIN `'.$this->prefix.$this->propertiesType.'` `t` ON `s`.`type` = `t`.`id` LEFT JOIN `'.$this->prefix.$this->propertyGroup.'` `g` ON `s`.`sGroup` = `g`.`id`';
            if($active){
                $this->SetOperData(array($active));
                $sql .= ' WHERE `active` = ?';
            }
            $sql .= ' ORDER BY `g`.`id`, `s`.`id`';
            return $this->Query($sql)->Run()->GetAll();
        }

        public function GetPropertyByField($field, $val){
            $sql = 'SELECT * FROM `'.$this->prefix.$this->propertiesTable.'` WHERE `'.$field.'` = ?';
            return $this->SetOperData(array($val))->Query($sql)->Run()->GetAll();
        }

        public function UpdateProperty($data){
            $data = !$data ? array() : $data;
            $props = $this->GetProperties();
            $this->Clear();
            foreach ($props as $prop) {
                $active = 'no';
                if(isset($data[$prop['id']])){
                    $active = 'yes';
                }
                $operD = array($active, $prop['id']);
                if(!$this->prepared){
                    $sql = 'UPDATE `'.$this->prefix.$this->propertiesTable.'` SET `active` = ? WHERE `id` = ?';
                    $this->SetOperData($operD)->Query($sql)->Run();
                }
                else{
                    $this->SetOperData($operD)->Run();
                }
            }
        }

        public function UpdatePropertySite($id, $data){
            $sql = 'UPDATE `'.$this->prefix.$this->propertiesTable.'` SET `name` = ?, `active` = ?, `type` = ?, `dop` = ?,`system` = ?, `sGroup` = ?, `empty` = ? WHERE `id` = ?';
            $this->SetOperData(array($data['name'],$data['active'],$data['type'],$data['dop'],$data['system'], $data['sGroup'], $data['empty'], $id))->Query($sql)->Run();
        }

        public function GetPropertiesValue($site){
            $sql = 'SELECT `p`.`id`, `value` FROM `'.$this->prefix.$this->propertiesTable.'` `p` LEFT JOIN `'.$this->prefix.$this->propertiesValueTable.'` `v` ON `p`.`id` = `v`.`property_id` WHERE `active` = \'yes\' and (`site_id` = ? or `site_id` IS NULL)';
            return $this->SetOperData(array($site))->Query($sql)->Run()->GetAll();
        }

        public function UpdatePropertiesValue($site, $data){
            foreach($data as $id => $val){
                if($this->IssetProperty($site, $id)){
                    $sql = 'UPDATE `'.$this->prefix.$this->propertiesValueTable.'` SET `value` = ? WHERE `site_id` = ? AND `property_id` = ?';
                }
                else{
                    $sql = 'INSERT INTO `'.$this->prefix.$this->propertiesValueTable.'` (`value`, `site_id`, `property_id`) VALUES (?,?,?);';
                }
                $this->SetOperData(array($val, $site, $id))->Query($sql)->Run();
            }

        }

        private function IssetProperty($site, $id){
            $sql = 'SELECT * FROM `p_PropertiesValue` WHERE `site_id` = ? AND `property_id` = ?';
            return $this->SetOperData(array($site, $id))->Query($sql)->Run()->CountResult() > 0;
        }

        public function AddProperty($data){
            $sql = 'INSERT INTO `'.$this->prefix.$this->propertiesTable.'` (`name`, `active`, `type`, `dop`,`system`,`sGroup`, `vGroup`, `empty`) VALUES (?,?,?,?,?,?,?,?);';
            $this->SetOperData(array($data['name'], $data['active'], $data['type'], $data['dop'], $data['system'], $data['sGroup'], 1, $data['empty']))->Query($sql)->Run();
        }

        public function DecodeParams($str){
            $result = array();
            if(!empty($str)) {
                $params = explode(';', $str);
                foreach ($params as $item) {
                    $x = explode('=', $item);
                    if(!isset($x[1])){
                        var_dump($x);
                    }
                    $result[trim($x[0])] = trim($x[1]);
                }
            }
            return $result;
        }

        public function CheckOwner($idUser, $idSite){
            return $this->Select()->Where('`id` = ? AND id_user = ?', array($idSite, $idUser))->Build()->Run(true)->CountResult();
        }

        public function GetHashSite($url){
            return MD5($url . time() . Config::APP_SECRET);
        }
    }