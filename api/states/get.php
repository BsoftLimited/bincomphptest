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
    include_once "../objects/polling_unit.php";
	include_once "../objects/party.php";
	include_once "../objects/pu_result.php";
	
	$data = json_decode(file_get_contents("php://input"));
	
    $error = new ErrorHandler();
    $db = new Database($error);
    $state = new State($db, $error);
    
    if(!$state->get()){
        $error->display();
    }
?>
