<?php
include 'config.php';

// Get POST data from request
$device_id = $_POST['device_id'];
$admin_id = $_POST['admin_id'];
$station_name = $_POST['station_name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$drainage_depth = $_POST['drainage_depth'];
$threshold_alert = $_POST['threshold_alert'];
$threshold_warning = $_POST['threshold_warning'];
$threshold_danger = $_POST['threshold_danger'];

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$sql = "UPDATE station 
        JOIN sensor_device ON station.station_code = sensor_device.station_code
        SET station_name = ?, latitude = ?, longitude = ?, drainage_depth = ?, threshold_alert = ?, threshold_warning = ?, threshold_danger = ?
        WHERE sensor_device.device_id = ? AND sensor_device.admin_id = ?";

// Prepare a prepared statement
$stmt = $conn->prepare($sql);

// Bind the parameters to the prepared statement
$stmt->bind_param("sssssssss",
  $station_name,
  $latitude,
  $longitude,
  $drainage_depth,
  $threshold_alert,
  $threshold_warning,
  $threshold_danger,
  $device_id,
  $admin_id
);

// Execute the prepared statement
if ($stmt->execute()) {
  $response = [
    "success" => true,
    "message" => "Station updated successfully",
  ];
} else {
  $response = [
    "success" => false,
    "message" => "Error: " . $sql . "<br>" . $conn->error,
  ];
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
