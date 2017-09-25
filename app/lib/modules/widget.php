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

    public static function BuildField($item, $data, $admin){
        if($item['system'] == '1' && !$admin){
            return '';
        }

        $snippet = !empty($item['dop']['snippet']) ? $item['dop']['snippet'] : $item['typeName'];
        $validator = (!empty($item['dop']['validator']) ? $item['dop']['validator'] : $item['typeName']).':';
        if($item['empty'] == '1'){
            $validator = 'nn_'.$validator;
            $item['name'] = '* '.$item['name'];
        }
        $item['id'] .= !empty($data['idV']) ? '-' . $data['idV'] : '';
        $classContainer = !empty($item['dop']['containerClass']) ? $item['dop']['containerClass'] : '';

        switch($snippet){
            case 'Timezone':
                $snippet = 'Select';
                $options = '';
                for($t = 0; $t <= 26; $t++){
                    $time = 14 - $t;
                    if($time >= 0){
                        $time = '+'.$time;
                    }
                    $options .= '<option value="'.$time.'"'.($data['value'] == $time ? ' selected':'').'>UTC '.$time.'</option>';
                }
                $param = array(
                    $item['name'], $item['id'], $options
                );
                break;
            case 'SelectInput':
                $hidden = $data['value'] != $item['dop']['itemtext'] ? ' hidden' :'';
                $tvalidator = !empty($item['dop']['validatortext']) ? $item['dop']['validatortext'] . ':' : '';
            case 'Select':
                $active = isset($item['dop']['itemtext']) ? $item['dop']['itemtext'] : '';
                $val = !empty($item['dop']['value']) ? $item['dop']['value'] : '';
                $elem = explode('|',$val);
                $options = '';
                foreach($elem as $key => $val){
                    $options .= '<option value="'.$key.'"'.($data['value'] == $key ? ' selected':'').'>'.$val.'</option>';
                }
                $param = array(
                    $item['name'], $item['id'], $options, $data['value'], $active, isset($hidden)?$hidden:'',isset($tvalidator)?$tvalidator:'', $classContainer
                );
                break;
            case 'Range':
                $max = !empty($item['dop']['max']) ? $item['dop']['max'] : 255;
                $min = !empty($item['dop']['min']) ? $item['dop']['min'] : 0;
                $val = !empty($data['value']) ? $data['value'] : $item['dop']['value'];
                $step = !empty($item['dop']['step']) ? $item['dop']['step'] : '';
                $param = array(
                    $item['name'], $validator, $item['id'], $min, $max, $step, $val
                );
                break;
            case 'Checkbox':
                $val = $data['value'] == '1' ? ' checked="checked"':'';
                $param = array(
                    $item['name'], $item['id'], $val, $classContainer
                );
                break;
            case 'Image':
                $val = !empty($data['value'])? Html::Snipet('IMG', array($data['value'], $item['name'], 100)) :'';
                $param = array(
                    $item['name'], $validator, $item['id'], $val
                );
                break;
            default:
                $param = array(
                    $item['name'], $validator, $item['id'], $data['value'], $classContainer
                );
        }

        return Html::Snipet('Field'.$snippet, $param);
    }

}