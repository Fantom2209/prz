<?php
	namespace app\core;
	
	class ErrorInfo{

        const PAGE_NOT_FOUND = 404;
        const ACCESS_DENIED = 500;
        const ACCESS_DENIED_FOR_RESOURCES = 501;
	    const UNDEFINED_ERROR = 0;
		
		const FIELD_EMPTY = 1;
		const FIELD_NOT_CORRECT = 2;
		const FIELD_OUT_OF_RANGE = 3;
		const FIELD_OUT_OF_RANGE_STR = 4;
		const FIELD_EASY_PASSWORD = 5;
		CONST FIELD_CONFIRM_PASSWORD_NOT_CORRECT = 6;
		CONST FIELD_LOGIN_NOT_FREE = 7;
		CONST FIELD_EMAIL_NOT_FREE = 8;

		const DB_CONNECT = 9;
		const DB_QUERY = 10;
        const DB_PREPARE_QUERY = 10;

        const USER_NOT_FOUND = 11;
        const USER_BANED = 12;
        const USER_NOT_ACTIVATE = 13;
        const FILE_NOT_CORRECT = 14;
        const FILE_SIZE_EXCEEDED = 15;
        const FILE_NOT_CORRECT_FORMAT = 16;
        const USER_COOKIES_MODIFIED = 17;

        const WIDGET_UNDEFINED_SITE = 700;
        const WIDGET_LOCKED_PHONE = 701;

		public static function GetMessage($code, $data = array()){
			if(!isset(self::$messages[$code])){
				$code = 0;
			}
			
			$result = self::$messages[$code];
			if(count($data) > 0){
				$patterns = array();
				foreach($data as $key => $item){
					$patterns[] = '{'.$key.'}';
				}
				$result = str_replace($patterns, $data, $result);
			}
			
			return $result;
		}


		// контекст (названия поля и т.д.) всегда передаеться под нулевым индексом
		public static function GetMetaErrorItem($code, $data = array()){
			return array('code' => $code, 'context' => isset($data[0])?$data[0]:'', 'msg' => self::GetMessage($code, $data));
		}
		
		private static $messages = array(
			self::PAGE_NOT_FOUND => 'Страница на найдена',
		    self::UNDEFINED_ERROR => 'Произошла неопределенная ошибка',
			self::FIELD_EMPTY => 'Поле не может быть пустым',
			self::FIELD_NOT_CORRECT => 'Поле имеет некорректное значение',
			self::FIELD_OUT_OF_RANGE_STR => 'Некорректное кол-во символов в поле',
			self::FIELD_OUT_OF_RANGE => 'Выход за границы диапазона',
            self::FIELD_EASY_PASSWORD => 'Слишком простой пароль, используйте латинские буквы + цифры!',
            self::FIELD_CONFIRM_PASSWORD_NOT_CORRECT => 'Пароли не совпадают!',
            self::FIELD_LOGIN_NOT_FREE => 'Аккаунт с таким логином уже существует!',
            self::FIELD_EMAIL_NOT_FREE => 'Аккаунт с таким email`ом уже существует!',
            self::DB_CONNECT => 'Ошибка при подключении к базе данных!',
            self::DB_QUERY => 'Ошибка при выполнении запроса к базе данных!',
            self::DB_PREPARE_QUERY => 'Ошибка при формировании запроса к базе данных',
            self::USER_NOT_FOUND => 'Неверный логин или пароль',
            self::ACCESS_DENIED => 'Нет доступа к данной странице!',
            self::ACCESS_DENIED_FOR_RESOURCES => 'Нет доступа к данным ресурсам!',
            self::USER_BANED => 'Аккаунт заблокирован!',
            self::USER_NOT_ACTIVATE => 'Збой при активации!',
            self::FILE_NOT_CORRECT => 'Файл поврежден!',
            self::FILE_SIZE_EXCEEDED => 'Файл слишком большой!',
            self::FILE_NOT_CORRECT_FORMAT => 'Файл поврежден или имеет недопустимый тип',
            self::USER_COOKIES_MODIFIED => 'Данные сеанса повреждены. Войдите в систему заново',
            self::WIDGET_UNDEFINED_SITE => 'Сайт не определен',
            self::WIDGET_LOCKED_PHONE => 'Отказано в услуге'
		);
		
	}