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

// Fetch the station data and latest connectivity_type using the device_id and admin_id
$sql = "SELECT st.*, sr.connectivity_type FROM sensor_device sd
        JOIN station st ON sd.station_code = st.station_code
        LEFT JOIN (
          SELECT device_id, MAX(reading_time) as MaxTime
          FROM sensor_reading
          GROUP BY device_id
        ) sr1 on sr1.device_id = sd.device_id
        LEFT JOIN sensor_reading sr on sr.device_id = sr1.device_id and sr.reading_time = sr1.MaxTime
        WHERE sd.device_id = '$device_id' AND sd.admin_id = '$admin_id'";
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
