<?php
// Database connection details
include 'config.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get admin_id from POST request
$admin_id = $_POST['admin_id'];

// Prepare SQL query to fetch the stations
$sql = "SELECT station_code, station_name FROM station WHERE admin_id = ? ORDER BY station_name";

// Prepare and execute the SQL statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_id);
$stmt->execute();

// Bind the result to variables
$stmt->bind_result($station_code, $station_name);

// Fetch the results and store them in an array
$results = array();
while ($stmt->fetch()) {
  $results[] = array(
    'station_code' => $station_code,
    'station_name' => $station_name
  );
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the results as a JSON object
header('Content-Type: application/json');
echo json_encode($results);
?>
