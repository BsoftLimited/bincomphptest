<?php
	class ErrorHandler{
		private $errors;
		
		public function __construct(){
			$this->errors = [];
		}
		
		public function add($code, $error, $message){
			$init = array("error" => $error, "message" => $message);
			if(empty($this->errors[$code])){
				$this->errors[$code] = array($init);
			}else{
				array_push($this->errors[$code], $init);
			}
		}
		
		public function display(){
			foreach($this->errors as $code => $error){
				http_response_code($code);
				echo json_encode($error);
			}
		}
		
		public function has_error(){
			return count($this->errors) > 1;
		}
	}