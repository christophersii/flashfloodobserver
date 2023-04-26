<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'config.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$device_id = $_GET['device_id'];
$admin_id = $_GET['admin_id'];

// Fetch the station data using the device_id and admin_id
$sql = "SELECT * FROM stations WHERE device_id = '$device_id' AND admin_id = '$admin_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of the fetched station
  $row = $result->fetch_assoc();
  echo json_encode($row);
} else {
  echo json_encode(["message" => "No station found"]);
}

$conn->close();
?>
