<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	
	include_once "../config/error.php";
	include_once "../config/database.php";
	include_once "../objects/state.php";
	include_once "../objects/lga.php";
	include_once "../objects/ward.php";
	include_once "../objects/party.php";
	
	$data = json_decode(file_get_contents("php://input"));
	
	if(!empty($data->state_id)){
		$error = new ErrorHandler();
		$db = new Database($error);
		$lga = new LGA($db, $error);
		
		if(!$lga->read($data->state_id)){
			$error->display();
		}
	}else{
		http_response_code(503);
		echo json_encode(array("message"=>"bad request"));
	}
?>
