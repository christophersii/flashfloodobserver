<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once 'config.php';
    include_once 'sensor_device.php';

    $database = new Database($conn);
    $db = $database->getConnection();

    $sensor_device = new SensorDevice($db);

    $data = json_decode(file_get_contents("php://input"));

    $sensor_device->device_id = $data->device_id;
    $sensor_device->admin_id = $data->admin_id;
    $sensor_device->station_code = $data->station_code;

    if ($sensor_device->create()) {
        http_response_code(201);
        echo json_encode(array("status" => "Success"));
    } else {
        http_response_code(503);
        echo json_encode(array("status" => "Failed"));
    }
?>
