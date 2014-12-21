<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

header('Content-Type: application/json');		# This service returns always JSON

include("configuration.php");					# DB parameters
include("safemsg_util.php");					# Classes

$is_id = array_key_exists("id", $_GET);
if (!$is_id || ($is_id && $_GET["id"]==null)) {
	http_response_code(400);
	$err_msg = array("error"=>"no ID specified", "status"=> 400 );
	echo json_encode($err_msg, JSON_PRETTY_PRINT);
	return;
}

$id = urldecode($_GET["id"]);					# ID in the URL ?id={id}

try {
    $conn = new PDO("mysql:host=$servername;dbname=safemsg",
    		$username, $password);											# Create connection
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT `safemsg`, `bind_ip` ".
    						"FROM `message` WHERE `id`=:id";    			# SQL statement (SELECT)
    $sql_delete = "DELETE FROM `message` WHERE `id`=:id";					# SQL statement (DELETE)
	$q = $conn->prepare($sql);												# Prepare SELECT statement

	$q->execute(array(":id"=>$id));											# Execute SELECT
	$row = $q->fetch();														# Fetch results

	if (empty($row)){														# The ID may not exist
		http_response_code(404); 											# HTTP Status 404 - not found
		$msgo = new RetrievedMessageResponse(null, 404);
		echo json_encode($msgo, JSON_PRETTY_PRINT);							# 404 response
		return;
	}

	$message = $row['safemsg'];												# Retrieve secret message
	$bind_ip  = null;
	$bind_mac = null;

	if (array_key_exists("bind_ip", $row) && $row['bind_ip']!==null) {		# IP Bind!    
    	$bind_ip = json_decode($row['bind_ip']);
    	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    		$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
    		$ip = $_SERVER['REMOTE_ADDR'];
		}
		if (!in_array($ip, $bind_ip)){
			$err_array = array("error"=>"Not found", "status"=>404,"client_ip"=>$ip,"requires_ip"=>$bind_ip);
			echo json_encode($err_array, JSON_PRETTY_PRINT);
			return;
		}
	}



	$q_delete = $conn->prepare($sql_delete);								# DELETE (prepre statement)
	$q_delete->execute(array(":id"=>$id));									# DELETE

	$msgo = new RetrievedMessageResponse($message, 200);					# Response/Success!
	echo json_encode($msgo, JSON_PRETTY_PRINT);
}
catch(PDOException $e)														# Database Exception 
{
    http_response_code(500);
    $err = "{\n  \"exception\":\"Database Exception\",\n  \"error\":\"". 
    			$e->getMessage(). "\"\n}";									# DB error message
    die($err."\n");
}

?>