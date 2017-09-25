<?php
	namespace app\helpers;
	use app\core\Config;
    use \app\core\ErrorInfo;
	use \app\data\Properties;
	
	class Validator{
		private $key;
	    private $mode;
		private $field;
		private $rule;
		private $empty;
		private $errors;
				
		public function __construct(){

		}
		
		public static function CleanKey($data){
			$result = array();
			if(!$data){
			    return $result;
            }
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
			$this->key = $key;
		    $this->empty = true;
		    $k = explode(':', $key);
			$x = explode('_',$k[0]);
			if(count($x) > 1){
			    switch($x[0]){
                    case 'nn':
                        $this->empty = false;
                        break;
                }
			    $k[0] = $x[1];
			}
            $this->rule = $k[0];
            $this->mode = 'Check_'.$this->rule;
            $this->field = $k[1];
		}
		
		public function Prepare($data){		
			if(!is_array($data)){
                $data = htmlspecialchars(strip_tags(stripslashes(trim($data))));
            }
		    return $data;
		}
		
		public function Check($data){
		    if(!$this->empty && $data === ''){
		        $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_EMPTY, array($this->key));
            }
            elseif($this->empty && $data === ''){
		        return;
            }
            else {
                $this->{$this->mode}($this->Prepare($data));
            }
		}
		
		// правила
		
		private function Check_Email($data){
            if(!preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->key));
            }
		}
		
		private function Check_Phone($data){
			if(!preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->key));
            }
		}
		
		private function Check_DefaultText($data){
			if(mb_strlen($data) < 2 || mb_strlen($data) >= 255){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE_STR, array($this->key, 2, 255));
			}
		}

		private function Check_Login($data){
            $this->Check_DefaultText($data);

		    if(!preg_match('/^[A-Za-z0-9]*$/', $data)){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->key));
            }
        }
		
		private function Check_UNumberShort($data){
			$data = intval($data); 
			if(!$data){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->key));
			}
			elseif($data < 1 || $data > 255){
				$this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_OUT_OF_RANGE, array($this->key, 1, 255));
			}
		}
		
		private function Check_Password($data){

		}

		private function Check_Link($data){
            if(!preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/u', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->key));
            }
        }

        private function Check_Date($data){
            if(!preg_match('/^\d{2}\/\d{2}\/\d{4}$/u', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->key));
            }
        }

        private function Check_String($data){

        }

        private function Check_Number($data){
            if(!preg_match('/^\d*(\.\d{1,})?$/', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->key));
            }
        }

        private function Check_Select($data){

        }

        private function Check_Color($data){
            if(!preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->key));
            }
        }

        private function Check_Multiline($data){

        }

        private function Check_Checkbox($data){

        }

        private function Check_Timezone($data){
            if(!preg_match('/^[+-]\d{1,2}$/u', $data)) {
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FIELD_NOT_CORRECT, array($this->key));
            }
        }

        private function Check_Image($data){
            $x = explode('-', $this->field);

            if($data['error']){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FILE_NOT_CORRECT, array($this->key));
                return;
            }

            $property = new Properties();
            $size = $property->Clear()->GetPropertyTagById('size', $x[0]);
            $type = $property->Clear()->GetPropertyTagById('type', $x[0]);

            if($data['size'] > $size){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FILE_SIZE_EXCEEDED, array($this->key));
            }

            $filename = basename($data['name']);
            $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));

            $type = explode('|', $type);
            if(!in_array($ext,$type) || Config::IMAGE_FORMAT[$ext] != $data['type']){
                $this->errors[] = ErrorInfo::GetMetaErrorItem(ErrorInfo::FILE_NOT_CORRECT_FORMAT, array($this->key));
            }
        }
	}