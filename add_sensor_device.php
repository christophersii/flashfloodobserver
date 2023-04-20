<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "config.php";

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->device_id) &&
    !empty($data->admin_id) &&
    !empty($data->station_name) &&
    !empty($data->latitude) &&
    !empty($data->longitude) &&
    !empty($data->threshold_warning) &&
    !empty($data->threshold_danger) &&
    !empty($data->drainage_depth)
) {
    $sql = "INSERT INTO station (station_name, latitude, longitude, threshold_alert, threshold_warning, threshold_danger, drainage_depth, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sddssssi", $data->station_name, $data->latitude, $data->longitude, $data->threshold_alert, $data->threshold_warning, $data->threshold_danger, $data->drainage_depth, $data->admin_id);

    if (mysqli_stmt_execute($stmt)) {
        $station_code = mysqli_insert_id($link);

        $sql = "INSERT INTO sensor_device (device_id, admin_id, station_code) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $data->device_id, $data->admin_id, $station_code);

        if (mysqli_stmt_execute($stmt)) {
            http_response_code(201);
            echo json_encode(array("message" => "Device and station were added successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to add device."));
        }
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to add station."));
    }
    mysqli_stmt_close($stmt);
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to add device and station. Data is incomplete."));
}

mysqli_close($link);
?>
