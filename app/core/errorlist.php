<?php
namespace app\core;

class ErrorList
{
    private static $errors;

    public static function Count(){
        return count(self::$errors);
    }

    public static function Add($error){
        self::$errors[] = $error;
    }

    public static function GetErrors(){
        return self::$errors;
    }

    public static function ErrorReporting(){
        $result = self::GetErrors();
        self::$errors = array();
        return $result;
    }
}