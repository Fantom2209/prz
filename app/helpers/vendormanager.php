<?php
namespace app\helpers;
use \app\core\Config;
use \app\helpers\Loger;

class VendorManager {

    const MODULE_PHPMAILER = 1;

    public static function GetInstance($module){

        switch($module){
            case self::MODULE_PHPMAILER:
                require_once Config::PATH_VENDOR . 'phpmailer' . Config::PATH_SEPARATOR . 'phpmailer.php';
                return new \PHPMailer();
                break;
            default:
                Loger::Write($module . ' - не определенный модуль');
                return null;
        }

    }

}