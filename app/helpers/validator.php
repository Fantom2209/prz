<?php
	namespace app\helpers;
	use \app\core\ErrorInfo;
	
	class Validator{
		private $mode;
		private $field;
		
		private $errors;
				
		public function __construct(){
			
		}
		
		public static function CleanKey($data){
			$result = array();
			foreach($data as $key => $item){
				$x = explode(':',$key);
				$x = (isset($x[1])?$x[1]:$x[0]);
				$result[$x] = $item; 
			}
			return $result;
		}
		
		public function IsValid(){
			return count($this->errors) == 0;
		}
		
		public function ErrorReporting(){
			return $this->errors;
		}
		
		public function Clean(){
			$this->errors = array();
		}
		
		public function Validate($data){
			$this->Clean();
			foreach($data as $key => $item){		
				if(strpos($key,':') === false){
				    continue;
                }
			    $this->SetMode($key);
				$this->Check($item);
			}
		}
		
		public function SetMode($key){
			$k = explode(':', $key);
            $this->mode = 'Check_'.$k[0];
            $this->field = $k[1];
		}
		
		public function Prepare($data){		
			return htmlspecialchars(strip_tags(stripslashes(trim($data))));
		}
		
		public function Check($data){
			$this->{$this->mode}($this->Prepare($data));
		}
		
		// правила
		
		private function Check_Email($data){
            if(!preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
            }
		}
		
		private function Check_Phone($data){
			if(!preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
            }
		}
		
		private function Check_DefaultText($data){
			if(mb_strlen($data) < 2 || mb_strlen($data) >= 255){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE_STR, array($this->field, 2, 255));
			}
		}

		private function Check_Login($data){
            $this->Check_DefaultText($data);

		    if(!preg_match('/^[A-Za-z0-9]*$/', $data)){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
            }
        }
		
		private function Check_UNumberShort($data){
			$data = intval($data); 
			if(!$data){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
			}
			elseif($data < 1 || $data > 255){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE, array($this->field, 1, 255));
			}
		}
		
		private function Check_Password($data){
			if(!preg_match('/^[A-Za-z0-9]*$/', $data)){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
            }

            if(mb_strlen($data) < 7 || mb_strlen($data) > 16){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE_STR, array($this->field, 7, 16));
            }

            $num = preg_replace('/[^0-9]/', '', $data);

            if(mb_strlen($num) == mb_strlen($data)){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_EASY_PASSWORD, array($this->field));
            }
		}

		private function Check_Link($data){
            if(!preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/u', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
            }
        }

        private function Check_Date($data){
            if(!preg_match('/^\d{2}\/\d{2}\/\d{4}$/u', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->field));
            }
        }

        private function Check_String($data){

        }

        private function Check_Number($data){

        }

        private function Check_Select($data){

        }

        private function Check_Color($data){

        }

        private function Check_Multiline($data){

        }

        private function Check_Checkbox($data){

        }
	}