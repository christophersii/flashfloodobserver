<?php
  // Include the database connection details
  include('config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$stationCode = $_POST["station_code"];

// Get latest water level reading for the specified station
$sql = "SELECT water_level FROM sensor_device sd
        INNER JOIN sensor_reading sr ON sr.device_id = sd.device_id
        WHERE sd.station_code = '$stationCode'
        ORDER BY sr.reading_time DESC
        LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output latest water level reading as JSON
  $row = $result->fetch_assoc();
  echo json_encode(array("water_level" => $row["water_level"]));
} else {
  echo json_encode(array("error" => "No readings found for specified station"));
}

$conn->close();

?>
