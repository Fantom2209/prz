<?php 
	namespace app\core;
	
	class Application{

	    private static $controller;
	    private static $action;
	    private static $meta;

		public static function run(){
		    self::Init();

            $controllerFull = '\app\lib\\'. self::$controller;
            self::$action .= (strtolower($_SERVER['REQUEST_METHOD']) == 'post') ? 'Post' : '';
            self::GetMetaInfo($controllerFull, self::$action);
            $controller = new $controllerFull(self::$controller, self::$action, self::$meta);
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

        private static function GetMetaInfo($controller, $action){
            $class  = new \ReflectionClass( $controller );
            $method = $class->getMethod( $action );

            $classDoc = $class->getDocComment();
            $methodDoc = $method->getDocComment();

            preg_match_all( '/@([a-z0-9_-]+)([^\n]+)/is', $classDoc, $arr );
            self::$meta['class'] = array_combine($arr[1], $arr[2]);

            preg_match_all( '/@([a-z0-9_-]+)([^\n]+)/is', $methodDoc, $arr );
            self::$meta['method'] = array_combine($arr[1], $arr[2]);
        }
	}