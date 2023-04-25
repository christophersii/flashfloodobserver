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
$sql = "SELECT s.station_code, s.station_name, sr.water_level, sr.reading_time
FROM station s
INNER JOIN sensor_reading sr ON s.station_code = sr.station_code
WHERE s.admin_id =? AND sr.id IN (
          SELECT MAX(sr_inner.id)
FROM sensor_reading sr_inner
WHERE sr_inner.station_code = s.station_code
        )
        ORDER BY s.station_name";


// Prepare and execute the SQL statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_id);
$stmt->execute();

// Bind the result to variables
$stmt->bind_result($station_code, $station_name, $water_level, $timestamp);

// Fetch the results and store them in an array
$results = array();
while ($stmt->fetch()) {
  $results[] = array(
    'station_code' => $station_code,
    'station_name' => $station_name,
    'water_level' => $water_level,
    'timestamp' => $timestamp
  );
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the results as a JSON object
header('Content-Type: application/json');
echo json_encode($results);
?>
