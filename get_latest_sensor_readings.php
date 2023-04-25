<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
include 'config.php';
  
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get admin_id from POST request
$admin_id = $_POST['admin_id'];

// Prepare SQL query to fetch the stations and their latest sensor readings
$sql = "SELECT s.station_code, s.station_name, sr.water_level, s.drainage_depth - sr.water_level AS drainage_water_level, sr.reading_time
FROM station s
LEFT JOIN sensor_device sd ON s.station_code = sd.station_code
LEFT JOIN sensor_reading sr ON sd.device_id = sr.device_id AND sr.reading_id IN (
          SELECT MAX(sr_inner.reading_id)
          FROM sensor_reading sr_inner
          INNER JOIN sensor_device sd_inner ON sr_inner.device_id = sd_inner.device_id
          WHERE sd_inner.station_code = s.station_code
        )
WHERE s.admin_id = ?
ORDER BY s.station_code DESC";


// Prepare and execute the SQL statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_id);
$stmt->execute();

// Bind the result to variables
$stmt->bind_result($station_code, $station_name, $water_level, $reading_time);

// Fetch the results and store them in an array
$results = array();
while ($stmt->fetch()) {
  $results[] = array(
    'station_code' => $station_code,
    'station_name' => $station_name,
    'water_level' => $water_level,
    'reading_time' => $reading_time
  );
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the results as a JSON object
header('Content-Type: application/json');
echo json_encode($results);
?>
