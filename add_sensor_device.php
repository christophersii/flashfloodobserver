<?php

include("config.php");
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the data from the POST request
$admin_id = $_POST['admin_id'];
$device_id = $_POST['device_id'];
$station_name = $_POST['station_name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$threshold_warning = $_POST['threshold_warning'];
$threshold_danger = $_POST['threshold_danger'];
$drainage_depth = $_POST['drainage_depth'];

// Insert station data into the station table
$sql = "INSERT INTO station (station_name, latitude, longitude, threshold_warning, threshold_danger, drainage_depth, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdddds", $station_name, $latitude, $longitude, $threshold_warning, $threshold_danger, $drainage_depth, $admin_id);
$stmt->execute();

// Get the generated station_code
$station_code = $conn->insert_id;

// Insert the device and station_code data into the sensor_device table
$sql2 = "INSERT INTO sensor_device (device_id, admin_id, station_code) VALUES (?, ?, ?)";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("sss", $device_id, $admin_id, $station_code);
$stmt2->execute();

// Close the prepared statements
$stmt->close();
$stmt2->close();

// Check if both queries were successful
if ($stmt && $stmt2) {
    echo json_encode(array("status" => "Success"));
} else {
    echo json_encode(array("status" => "Failed"));
}

// Close connection
$conn->close();
?>
