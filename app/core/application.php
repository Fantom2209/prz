<?php 
	namespace app\core;
	
	class Application{

	    private static $controller;
	    private static $action;

		public static function run(){
            self::Init();

            $controllerFull = '\app\lib\\'. self::$controller;
            $controller = new $controllerFull(self::$controller, self::$action);
            self::$action .= (strtolower($_SERVER['REQUEST_METHOD']) == 'post') ? 'Post' : '';

            $controller->{self::$action}();
            $controller->GetResponse();
		}

        private static function Init(){
            if(!isset($_GET['route'])){
                $_GET['route'] = 'home/index';
            }
		    $elementURI = explode('/', $_GET['route']);
            self::$controller = !empty($elementURI[0]) ? $elementURI[0] : 'home';
            self::$action = !empty($elementURI[1]) ? $elementURI[1] : 'index';
        }
	}