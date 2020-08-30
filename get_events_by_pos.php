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
if (!isset($data->latitude) or !isset($data->longitude)) {

    // message if value missed
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "user position is missing."));

    die();
}

if (isset($data->distance) && $data->distance < 61 && $data->distance > 0) {
    $eventObject->distance = $data->distance * 1000;
}

$eventObject->user_lat = $data->latitude;
$eventObject->user_long = $data->longitude;

if(!$eventObject->getNearEvents()){
    
    http_response_code(400);
    echo json_encode(array("error" => TRUE, "message" => "Unable to get events."));
    die();
}

// set response code & answer
http_response_code(200);
echo json_encode(array(
    "error" => FALSE,
    "message" => "Found events.",
    "events" => utf8ize($eventObject->results)));

// Debug
// echo json_last_error_msg();

?>