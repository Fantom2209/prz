<?php
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	spl_autoload_register(function($class){
		$path = dirname(__FILE__) . '/' . strtolower(str_replace('\\', '/', $class));
		spl_autoload($path);
	});	

	app\core\Application::run();