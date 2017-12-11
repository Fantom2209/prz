<?php

namespace app\helpers;
use \app\core\Config;

class Pagination {
    public static function Build($countItems, $current, $countLeft, $countRight, $dopParam){
        $data = array();
        $countPage = intval($countItems/Config::PAGINATION_COUNT_ON_PAGE);
        if($countItems % Config::PAGINATION_COUNT_ON_PAGE != 0){
            $countPage++;
        }
        $data['start'] = ($current > 0)?(intval($current - 1)) * Config::PAGINATION_COUNT_ON_PAGE:0;

        $start = $current - $countLeft;
        $end = $current + $countRight;

        if($start < 1){
            $start = 1;
            $end += $countLeft+1-$current;
        }

        if($end > $countPage){
            $start -= $end - $countPage;
            $end = $countPage;

            if($start < 1){
                $start = 1;
            }
        }
        if($end == 1){
            return array('start' => 0, 'html' => '');
        }

        $paramLink = '';
        foreach($dopParam as $key=>$val){
            if($val !== ''){
                $paramLink .= '&'.$key.'='.$val;
            }
        }

        $html = '<div class="row"><div class="col-md-12 text-center"><ul class="pagination pagination-lg">';
        if($start > 1){
            $html .= '<li><a href="?page='. 1 . $paramLink .'">«</a></li><li class="disabled"><span>...</span></li>';
        }
        for($i = $start; $i <= $end; $i++){
            if($i == $current){
                $html .= '<li class="active"><a href="?page='.$i. $paramLink .'">'.$i.'</a></li>';
            }
            else{
                $html .= '<li><a href="?page='.$i. $paramLink . '">'.$i.'</a></li>';
            }

        }
        if($end < $countPage){
            $html .= '<li class="disabled"><span>...</span></li><li><a href="?page='.$countPage.'">»</a></li>';
        }
        $html .= '</ul></div></div>';

        $data['html'] = $html;
        return $data;
    }
}