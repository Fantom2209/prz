<?php
namespace app\helpers;
use \app\core\Config;

class Loger
{

    public static function Write($msg, $code = null){
        if(!is_dir(Config::PATH_LOG_DIR)){
            mkdir(Config::PATH_LOG_DIR, 0777, true);
        }

        $text = self::GetDateInfo() . ' - ' . self::GetUserInfo() . ': ' . $msg . ($code !== null ? '('.$code.')' : '') . '\r\n';

        file_put_contents(Config::PATH_LOG_FILE, $text, FILE_APPEND);
    }

    private static function GetUserInfo(){
        return 'Test User (Admin)';
    }

    private static function GetDateInfo(){
        return date("Y-m-d H:i:s");
    }

}