<?php
/**
 * Created by PhpStorm.
 * User: edyar
 * Date: 10.08.2017
 * Time: 9:26
 */

namespace app\data;
use \app\core\config;

class Menu extends \app\core\Model {

    public function GetItems($name){
        return $this->Select(
            array(array('table'=>'t2', 'field' => 'id'),'url', 'title', 'parent', 'position')
        )->Binding(
            'LEFT','MenuItems','id','id_menu'
        )->Where(
            '`name` = ?', array($name)
        )->OrderBy(array('parent', 'position'))->Build()->Run()->GetAll();
    }

}