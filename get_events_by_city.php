<?php

function utf8ize( $mixed ) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
    }
    return $mixed;
}

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, Origin");

// support CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	http_response_code(200);
	die();
}

// files needed to connect to database
include_once 'config/Database.php';
include_once 'objects/Event.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate other objects
$eventObject = new Event($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// check if data is set
if (!isset($data->city)) {

    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "city is missing."));

    die();
}

$eventObject->city = $data->city;

if(!$eventObject->getByCity()){
    
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to get events."));
    die();
}

// set response code & answer
http_response_code(200);
echo json_encode(array(
    "error" => FALSE,
    "message" => "Events found.",
    "events" => utf8ize($eventObject->results)));

?>