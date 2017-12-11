<?php

namespace app\lib;
use \app\core\Config;

/**
 * @group(ADMINISTRATOR)
 */
class Tools extends \app\core\Page {

    public function __construct($controller, $action, $meta){
        parent::__construct($controller, $action, $meta);
    }

    public function Index(){
        if(file_exists(Config::PATH_LOG_FILE)){
            $logs = file_get_contents(Config::PATH_LOG_FILE);
            $logs = explode('\r\n',$logs);
        }
        else{
            $logs = array();
        }

        $this->response->Set('emptyLog', count($logs) == 0);
        $this->response->Set('logs',$logs);
        $this->response->Set('title','Инструменты разработчика');
    }

    public function ClearLogs(){
        unlink(Config::PATH_LOG_FILE);
    }

    public function FTest(){
        $class = '\app\data\Properties';
        $method = 'GetBranchListWithEmail';
        $param = array();
        $class = new $class();
        echo "<pre>";
        print_r(call_user_func_array(array($class, $method), $param));
        echo "</pre>";
        exit;
    }
}