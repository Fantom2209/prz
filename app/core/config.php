<?php
	namespace app\core;

	use \app\lib\modules\Filter;
	
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


	    const URL_ROOT = 'https://perezvon.foolsoft.ru';
        const URL_IMG = self::URL_ROOT . '/assets/img';
        const URL_CSS = self::URL_ROOT . '/assets/css/';
        const URL_JS = self::URL_ROOT . '/assets/js/';
        const URL_FONT = self::URL_ROOT . '/assets/font/';

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
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        );


        const WIDGET_WINDOW_TYPE_VIEW = array(
            '0' => 'right',
            '1' => 'main',
        );

        const WIDGET_BTN_TYPE_VIEW = array(
            '0' => 'btn1',
            '1' => 'btn2',
            '2' => 'btn3',
            'rectangle' => 'btn4',
        );

        const WIDGET_BTN_CIRCLE = 0;
        const WIDGET_BTN_SQUARE = 1;
        const WIDGET_BTN_RECTANGLE = 2;

        const WIDGET_BTN_LOCATION_LEFT = 0;
        const WIDGET_BTN_LOCATION_RIGHT = 1;

        const WIDGET_TIME_WORK = 0;
        const WIDGET_TIME_NOT_WORK = 1;
        const WIDGET_TIME_LUNCH = 2;
        const WIDGET_MSG_NOT_ACTIVE = 'perezvonok: для страницы widget выключен';
        const WIDGET_WINDOW_TYPE_RIGHT = 0;
        const WIDGET_WINDOW_TYPE_CENTER = 1;

        const PAGINATION_COUNT_ON_PAGE = 10;
        const PAGINATION_COUNT_LEFT = 2;
        const PAGINATION_COUNT_RIGHT = 1;

        const WIDGET_OPEN_TRIGGER_TYPE = array(
            'CLICK_CALL_BTN' => '1',
            'LEFT_WINDOW' => '2',
            'AUTO_START' => '3'
        );

        const SELECTOR_SCHEDULE_EDIT = '#modalSchedule .set';


        const FILTER_CALLS = 'calls';

        const FILTER_MODE_DELETE_SPACES = 1;

        const FILTERS_OPTIONS = array(
            self::FILTER_CALLS => array(
                //'site' => array('operation' => Filter::OPERATION_EQUALS),
                'dateFrom' => array('operation' => Filter::OPERATION_BETWEEN_START, 'field' => 'data'),
                'dateTill' => array('operation' => Filter::OPERATION_BETWEEN_END, 'field' => 'data'),
                'tel' => array('mod' => self::FILTER_MODE_DELETE_SPACES)
            )
        );

    }