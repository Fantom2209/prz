<?php
namespace app\data;

use \app\core\Config;

class Schedule extends \app\core\Model{

    private $tDays;
    private $tRelax;

    public function __construct(){
        parent::__construct();
        $this->tDays = 'DaysWeek';
        $this->tRelax = 'TimeRelax';
    }

    public function GetDaysList(){
        $this->SetTable($this->tDays);
        $r = $this->GetList();
        $this->DefaultTable();
        return $r;
    }

    public function GetScheduleList($site, $day = null){
        if($day == null){
            $this->Where('`t1`.`site_id` = ?', array($site));
        }
        else{
            $this->Where('`t1`.`site_id` = ? AND `t1`.`day_id` = ?', array($site, $day));
        }

        $r = $this->Select(
            array(
                array('table' => 't1', 'field' => 'id'),
                'day_id',
                array('table' => 't2', 'field' => 'name', 'label' => 'day_name'),
                'work',
                //'lunch',
                'work_start',
                'work_end',
                //'lunch_start',
                //'lunch_end'
            )
        )->Binding('LEFT', $this->tDays, 'day_id', 'id')->Build()->Run(true);

        if($day == null){
            $scheduleList = $this->GetAll();
            $scheduleValues = array();
            if(count($scheduleList) > 0){
                foreach ($scheduleList as $item){
                    $scheduleValues[$item['day_id']] = $item;
                }
            }

            return $scheduleValues;
        }

        return $this->GetNext();
    }

    public function GetRelaxTimeInfo($site){
        $this->SetTable($this->tRelax);
        $r = $this->Select()->Where('`site_id` = ?', array($site))->Build()->Run(true)->GetNext();
        $this->DefaultTable();
        return $r;
    }

    public function InsertOrUpdateRelax($site, $data){
        $this->SetTable($this->tRelax);
        $count = $this->GetCount($site, 'site_id');
        if($count === "0"){
            $this->Insert($data + array('site_id' => $site))->Run(true);
        }
        else{
            $this->Update($data, '`site_id` = ?', array($site))->Run(true);
        }
        $this->DefaultTable();
    }

    public function InsertOrUpdate($site, $day, $data){
        $count = $this->Select(array('id'))->Where('`site_id` = ? AND `day_id` = ?', array($site, $day))->Build()->Run(true)->CountResult();
        if($count === 0){
            $this->Insert($data + array('site_id' => $site, 'day_id' => $day))->Run(true);
        }
        else{
            $this->Update($data, '`site_id` = ? AND `day_id` = ?', array($site, $day))->Run(true);
        }
    }

    public function PrepareBranches($site, $data = array()){
        $dayInfo = $this->GetScheduleList($site, date('N'));
        $relaxInfo = $this->GetRelaxTimeInfo($site);
        $holidays = new Holidays();

        $timezone = date('P');
        $timezone = str_replace('+', '', $timezone);
        $timezone = explode(':', $timezone);
        $timezoneServer = isset($timezone[0]) ? intval($timezone[0]) : 0;

        $result = array(
            'workBranches' => array(),
            'notWorkBranches' => array(),
            'emailBranches' => array()
        );

        foreach($data as $name => $branch){

            $timezoneOffice = intval(str_replace('+', '', $branch['tz']));
            $officeTime = $this->GetOfficeTime($timezoneServer, $timezoneOffice);

            $isHoliday = $holidays->IsHoliday($officeTime['date']);

            $status = Config::WIDGET_TIME_NOT_WORK;

            if((!$isHoliday || $relaxInfo['work_holidays'] == '1') && $dayInfo['work'] === '1'){
                $status = $this->IsWorkTime($officeTime['time'], $dayInfo['work_start'], $dayInfo['work_end'], $relaxInfo['lunch'] == '0' ? null : $relaxInfo['lunch_start'], $relaxInfo['lunch'] == '0' ? null : $relaxInfo['lunch_end']);

                if($status === Config::WIDGET_TIME_WORK){
                    $result['workBranches'][] = array($name => array('time' => $officeTime['time'], 'name' => $branch['ru']));
                }
            }

            if($dayInfo['work'] === '0' || $status !== Config::WIDGET_TIME_WORK){
                $result['notWorkBranches'][] = array($name => array('time' => $officeTime['time'], 'name' => $branch['ru']));
            }

            foreach($branch['managers'] as $manager){
                if(!empty($manager['email'])){
                    if(!in_array($branch['ru'], $result['emailBranches'])){
                        $result['emailBranches'][] = array($name => $branch['ru']);
                        break;
                    }
                }
            }
        }

        return $result;
    }

    private function GetOfficeTime($server, $office){
        $result = '';

        if($server > $office){
            $result = date('Y.m.d H:i', strtotime('- ' . ($server - $office) . ' hours'));
        }
        elseif($server < $office){
            $result = date('Y.m.d H:i', strtotime('+ ' . ($office - $server) . ' hours'));
        }
        else{
            $result = date('Y.m.d H:i');
        }

        $result = explode(' ', $result);

        return array('date'=> $result[0], 'time' => $result[1]);
    }

    public function IsWorkTime($time, $start, $end, $lunchStart = null, $lunchEnd = null){

        if($time < $start || $time >= $end){
            return Config::WIDGET_TIME_NOT_WORK;
        }
        elseif($lunchStart != null && $time >= $lunchStart && $time < $lunchEnd){
            return Config::WIDGET_TIME_LUNCH;
        }

        return Config::WIDGET_TIME_WORK;
    }

}