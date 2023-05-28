<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config.php';

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(!empty($data->device_id)){
    try {
        // get database connection
        $database = new Database();
        $db = $database->getConnection();

        // query to check if device_id exists
        $query = "SELECT COUNT(*) FROM sensor_device WHERE device_id = :device_id";
        $stmt = $db->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(":device_id", $data->device_id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $num = $stmt->fetchColumn();

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
    }
    catch(PDOException $exception){
        // on error
        http_response_code(500);
        echo json_encode(array("message" => "Error processing your request.", "error" => $exception->getMessage()));
    }
}
else{
    // data is incomplete
    http_response_code(400);
    echo json_encode(array("message" => "Device id not provided."));
}
?>
