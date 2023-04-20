<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once 'config.php';
    include_once 'station.php';

    $database = new Database($conn);
    $db = $database->getConnection();

    $station = new Station($db);

    $data = json_decode(file_get_contents("php://input"));

    $station->station_name = $data->station_name;
    $station->latitude = $data->latitude;
    $station->longitude = $data->longitude;
    $station->threshold_alert = $data->threshold_alert;
    $station->threshold_warning = $data->threshold_warning;
    $station->threshold_danger = $data->threshold_danger;
    $station->drainage_depth = $data->drainage_depth;
    $station->admin_id = $data->admin_id;

    if ($station->create()) {
        $response = array("status" => "Success", "station_code" => $station->station_code);
        http_response_code(201);
        echo json_encode($response);
    } else {
        http_response_code(503);
        echo json_encode(array("status" => "Failed"));
    }
?>
