<?php
namespace app\lib\modules;

use app\core\Config;
use \app\helpers\Html;
use \app\helpers\Functions;
use \app\data\Schedule;
use \app\data\Holidays;

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
                //$hidden = $data['value'] != $item['dop']['itemtext'] ? ' hidden' :'';

                $tvalidator = !empty($item['dop']['validatortext']) ? $item['dop']['validatortext'] . ':' : '';
                $active = isset($item['dop']['itemtext']) ? $item['dop']['itemtext'] : '';

                $val = !empty($item['dop']['value']) ? $item['dop']['value'] : '';
                $elem = explode('|',$val);
                $hidden = !empty($elem[$data['value']]) && $data['value'] != $item['dop']['itemtext']  ? ' hidden' : '';

                $options = '';
                foreach($elem as $key => $val){
                    if(empty($hidden)){
                        $options .= '<option value="'.$key.'"'.($item['dop']['itemtext'] == $key ? ' selected':'').'>'.$val.'</option>';
                    }
                    else{
                        $options .= '<option value="'.$key.'"'.($data['value'] == $key ? ' selected':'').'>'.$val.'</option>';
                    }
                }

                $param = array(
                    $item['name'], $item['id'], $options, $data['value'], $active, isset($hidden)?$hidden:'',isset($tvalidator)?$tvalidator:'', $classContainer
                );
                break;
            case 'Select':

                $val = !empty($item['dop']['value']) ? $item['dop']['value'] : '';
                $elem = explode('|',$val);
                $options = '';
                foreach($elem as $key => $val){
                    $options .= '<option value="'.$key.'"'.($data['value'] == $key ? ' selected':'').'>'.$val.'</option>';
                }

                $param = array(
                    $item['name'], $item['id'], $options, $data['value'], $classContainer
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

    public static function GetTimeList($start, $end, $lunchStart, $lunchEnd, $step, $lunch = true){ //todo использовать метод из Function
        $hours = 23; $minutes = 60;
        $result = array();
        $time = $start;
        $z = 0;
        while($time < $end){
            if(($lunch === false) || ($time < $lunchStart || $time >= $lunchEnd)){
                $result[] = array('val' =>  $time . ':00', 'title' => $time);
                $x = explode(':', $time);
                $x[1] = (int)$x[1] + $step;
                if($x[1] >= $minutes){
                    $x[1] = '00';
                    $x[0] = (int)$x[0] + 1;
                    if($x[0] > $hours){
                        $x[0] = '00';
                    }
                }
                $time = str_pad($x[0], 2, '0', STR_PAD_LEFT) . ':' . str_pad($x[1], 2, '0', STR_PAD_LEFT);
            }else{
                $time = $lunchEnd;
            }
        }
        return $result;
    }

    public static function GetDayList($count, $site){
        $schedule = new Schedule();
        $holidays = new Holidays();

        $daysList = $schedule->GetScheduleList($site);
        $relaxInfo = $schedule->GetRelaxTimeInfo($site);

        $result = array(
            'days' => array(),
            'lunch_start' => $relaxInfo['lunch'] == '1' ? $relaxInfo['lunch_start'] : null,
            'lunch_end' => $relaxInfo['lunch'] == '1' ? $relaxInfo['lunch_end'] : null
        );

        $i = 0;
        while(count($result['days']) < $count){
            $currentDate = date('Y.m.d', strtotime('+'.$i.' days'));
            $currentDay = date('N', strtotime('+'.$i.' days'));

            if((!$holidays->IsHoliday($currentDate) || $relaxInfo['work_holidays'] == '1') && $daysList[$currentDay]['work'] === '1') {
                $date = explode('.', $currentDate);
                $result['days'][] = array(
                    'val' => implode('-', $date),
                    'title' => $i > 0 ? $date[2] . ' ' . Functions::GetRuMonthByNumber($date[1]) : 'Сегодня',
                    'start' => $daysList[$currentDay]['work_start'],
                    'end' => $daysList[$currentDay]['work_end']
                );
            }

            $i++;
        }

        return $result;
    }

    public static function Filter($meta, $ref){
        $p = Functions::TextareaToArray($meta);
        foreach($p as $i => $item){
            $item = trim($item);
            if(!empty($item)){
                $url = explode('?',$item);
                if(strpos($url[0], '*') !== -1){
                    $url = str_replace('*', '', $url[0]);
                    if(strpos($ref,$url) === 0){
                        return false;
                    }
                }
                else{
                    if($url[0] === $ref){
                        return false;
                    }
                }
            }
        }
        return true;
    }

}