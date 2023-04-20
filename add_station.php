<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data["device_id"]) && !empty($data["station_name"]) && !empty($data["latitude"]) && !empty($data["longitude"]) && !empty($data["threshold_warning"]) && !empty($data["threshold_danger"]) && !empty($data["drainage_depth"]) && !empty($data["admin_id"])) {
    $device_id = $data["device_id"];
    $station_name = $data["station_name"];
    $latitude = $data["latitude"];
    $longitude = $data["longitude"];
    $threshold_warning = $data["threshold_warning"];
    $threshold_danger = $data["threshold_danger"];
    $drainage_depth = $data["drainage_depth"];
    $admin_id = $data["admin_id"];

    $conn = new mysqli($servername, $username, $password, $dbname);;

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO station (station_name, latitude, longitude, threshold_alert, threshold_warning, threshold_danger, drainage_depth, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddssssi", $station_name, $latitude, $longitude, $threshold_alert, $threshold_warning, $threshold_danger, $drainage_depth, $admin_id);

    if ($stmt->execute()) {
        $station_code = $conn->insert_id;

        $stmt2 = $conn->prepare("INSERT INTO sensor_device (device_id, admin_id, station_code) VALUES (?, ?, ?)");
        $stmt2->bind_param("sii", $device_id, $admin_id, $station_code);

        if ($stmt2->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Station and sensor device added successfully.", "station_code" => $station_code]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Unable to add sensor device to the database."]);
        }

        $stmt2->close();
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to add station to the database."]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(["message" => "Missing data. Please ensure all required fields are provided."]);
}
?>
