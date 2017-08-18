<?php 
	namespace app\core;
	
	class View{
		
		private $options;
				
		public function __construct(){
			$this->options = array(
				'layout' => '',
				'template' => '',
			);
		}
		
		public function Get($name){
			return $this->options[$name];
		}
		
		public function Set($name, $value){
			$this->options[$name] = $value;
		}
		
		public function HasLayout(){
			return !empty($this->options['layout']);
		}
		
		public function HasTeamplate(){
			return !empty($this->options['template']);
		}
	}