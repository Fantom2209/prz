<?php
namespace app\lib\modules;

use \app\helpers\Html;

class Widget extends \app\core\ViewModule {

    private $model;

    public function __construct(){
        parent::__construct();
    }

    public function SetEditMode(){
        $this->SetTemplate('edit');
    }

    public static function BuildField($item){
        /*if($item['system'] == '1' && !($this->Get('IsSuperUser') || $this->Get('IsAdmin'))){
            var_dump($this->Get('IsSuperUser'));
            continue;
        }*/

        $snippet = !empty($item['dop']['snippet']) ? $item['dop']['snippet'] : $item['typeName'];
        $validator = (!empty($item['dop']['validator']) ? $item['dop']['validator'] : $item['typeName']).':';
        $item['id'] .= !empty($item['idV']) ? '-' . $item['idV'] : '';

        switch($snippet){
            case 'Select':
                $val = !empty($item['dop']['value']) ? $item['dop']['value'] : '';
                $elem = explode('|',$val);
                $options = '';
                foreach($elem as $val){
                    $options .= '<option>'.$val.'</option>';
                }
                $param = array(
                    $item['name'], $item['id'], $options
                );
                break;
            case 'Range':
                $max = !empty($item['dop']['max']) ? $item['dop']['max'] : 255;
                $min = !empty($item['dop']['min']) ? $item['dop']['min'] : 0;
                $val = !empty($item['dop']['value']) ? $item['dop']['value'] : '';
                $step = !empty($item['dop']['step']) ? $item['dop']['step'] : '';
                $param = array(
                    $item['name'], $validator, $item['id'], $min, $max, $step, $val
                );
                break;
            case 'Checkbox':
                $val = $item['value'] == '1' ? ' checked="checked"':'';
                $param = array(
                    $item['name'], $item['id'], $val
                );
                break;
            default:
                $param = array(
                    $item['name'], $validator, $item['id'], $item['value']
                );
        }

        return Html::Snipet('Field'.$snippet, $param);
    }

}