<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	
	include_once "config/error.php";
	include_once "config/database.php";
	include_once "objects/state.php";
	include_once "objects/lga.php";
	include_once "objects/ward.php";
	include_once "objects/party.php";
	include_once "objects/polling_unit.php";
	
    $error = new ErrorHandler();
    $db = new Database($error);
    $party = new Party($db, $error);
    $parties = $party->all();

    if(!$error->has_error()){
    	 $state = new State($db, $error);
    	 $states = $state->all();
    	 if(!$error->has_error()){
    	 	http_response_code(200);
    	 	echo json_encode(array("states" => $states, "parties" => $parties));
    	 }else{
    	 	$error->display();
    	 }
    }else{
    	$error->display();
    }
?>
