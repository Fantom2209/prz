<?php
namespace app\helpers;

use \app\core\Config;

class Uploder {

    public static function Upload($pathSrc, $pathResult, $fileName){
        $result = '';
        if(!is_dir($pathResult)){
            mkdir($pathResult, 0777, true);
        }

        if ((move_uploaded_file($pathSrc,$pathResult . $fileName))) {
            $result = str_replace(Config::PATH_ROOT, '/', $pathResult . $fileName);
        }

        return $result;
    }

}