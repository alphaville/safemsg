<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

header('Content-Type: application/json');      # This service returns always JSON

include("configuration.php");
include("safemsg_util.php");

$sm = $_SERVER['REQUEST_METHOD'];

if (strcmp("GET", $sm) == 0) {    
    return;
}

if (! array_key_exists("msg", $_POST)) {
    http_response_code(404);
    die("{\n \"error\":\"No message sent.\" \n}\n");
}

$bind_ip = null;
if (array_key_exists("bind_ip", $_POST) && $_POST['bind_ip']!==null){    
    $bind_ip = json_encode($_POST['bind_ip']);
}

$safemsg=$_POST['msg'];             # retrieve the message to be encoded

//$id=generate_uuid()."-".generateRandomString(8);
$id=generateRandomString(8);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", 
        $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement
    $sql = "INSERT INTO message (`id`, `safemsg`, `bind_ip`) VALUES (:id,:msg,:ips)";    
	$q = $conn->prepare($sql);

	// Execute
	$q->execute(array(':id'=>$id,
	                  ':msg'=>$safemsg,
                      ':ips'=>$bind_ip));
	$rsp = new RegisterMessageResponse($id, 200);    
	echo json_encode($rsp, JSON_PRETTY_PRINT);
    
}
catch(PDOException $e)
{
    http_response_code(500);    
    //die($e->getMessage());
    $err = "{\n  \"exception\":\"Database Exception\",\n  \"error\":\"". $e->getMessage(). "\"\n}";
    die($err."\n");
}


function generate_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
} 

function generateRandomString($length = 10) {
    $characters = '0123456789qwertyuiopasdfghjklzxcvbnmMNBVCXZASDFGHJKLPOIUYTREWQ.';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?> 


