<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config.php';

// get posted data
$device_id = $_POST['device_id'];

// make sure data is not empty
if(!empty($device_id)){
    // check if the connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // query to check if device_id exists
    $query = "SELECT COUNT(*) FROM sensor_device WHERE device_id = ?";
    $stmt = $conn->prepare($query);

    // bind id of product to be updated
    $stmt->bind_param("s", $device_id);

    // execute query
    $stmt->execute();

    // get retrieved row
    $stmt->bind_result($num);
    $stmt->fetch();

    if($num>0){
        // device exists
        http_response_code(200);
        echo json_encode(array("exists" => true));
    }
    else{
        // device does not exist
        http_response_code(200);
        echo json_encode(array("exists" => false));
    }

    $stmt->close();
    $conn->close();
}
else{
    // data is incomplete
    http_response_code(400);
    echo json_encode(array("message" => "Device id not provided."));
}
?>
