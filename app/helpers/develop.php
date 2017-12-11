<?php
namespace app\helpers;


class Develop{

    public static function VarDamp($data, $exit = true){
        self::ShowData($data, 1, $exit);
    }

    public static function PrintR($data, $exit = true){
        self::ShowData($data, 2, $exit);
    }

    public static function ShowData($data, $type = 1, $exit = true){
        echo '<pre>';
        switch($type){
            case 1:
                var_dump($data);
                break;
            case 2:
                print_r($data);
                break;
        }
        echo '</pre>';
        if($exit){
            exit;
        }
    }

}