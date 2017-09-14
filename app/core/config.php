<?php
	namespace app\core;
	
	class Config{
	    const DEFAULT_BRAND = 'Perezvonok';

		const PATH_SEPARATOR = '/';
	    const PATH_ROOT = '/home/h8840/data/www/perezvon.foolsoft.ru' . self::PATH_SEPARATOR;
        const PATH_SOURCE = self::PATH_ROOT . 'app' . self::PATH_SEPARATOR;
        const PATH_UPLOAD = self::PATH_ROOT . 'upload' . self::PATH_SEPARATOR;
        const PATH_RECOURCES = self::PATH_ROOT . 'assets' . self::PATH_SEPARATOR;
        const PATH_VENDOR = self::PATH_SOURCE . 'vendor' . self::PATH_SEPARATOR;
        const PATH_VIEW = self::PATH_SOURCE . 'view' . self::PATH_SEPARATOR;
        const PATH_LAYOUT = self::PATH_VIEW . 'shared' . self::PATH_SEPARATOR;
        const PATH_MODULES_VIEW = self::PATH_VIEW . 'modules' . self::PATH_SEPARATOR;
        const PATH_TMP = self::PATH_SOURCE . 'tmp' . self::PATH_SEPARATOR;
        const PATH_LOG_DIR = self::PATH_TMP . 'logs' . self::PATH_SEPARATOR;
        const PATH_LOG_FILE = self::PATH_LOG_DIR . 'log.txt';


	    const URL_ROOT = 'http://perezvon.foolsoft.ru';
        const URL_IMG = self::URL_ROOT . '/assets/images/';
        const URL_CSS = self::URL_ROOT . '/assets/css/';
        const URL_JS = self::URL_ROOT . '/assets/js/';

        const DB_HOST = 'localhost';
        const DB_NAME = 'h8840_freelance';
        const DB_USER = 'h8840_freelance';
        const DB_PASSWORD = 'freelance';
        const DB_PREFIX = 'p_';

        const APP_SECRET = '%@zs:ZIP.';

        const CATEGORY_ADMINISTRATOR = 1;
        const CATEGORY_CLIENT = 2;

        const RECAPTCHA_CODE = '6LewDS0UAAAAAPLyFvDl_CqHzAvdk9ZNJXblTnf4';
        const RECAPTCHA_SECRET = '6LewDS0UAAAAAPFNAKYD6tq2Rfy-NIWdb84jc4vR';

        const IMAGE_FORMAT = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        );


    }