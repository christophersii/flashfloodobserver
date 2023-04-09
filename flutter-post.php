<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// ... code to connect to database and retrieve data ...
include("config.php");
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// retrieve data from database

$sql = "SELECT device_id, reading_id, water_level, rainfall, temperature, humidity, reading_time FROM sensor_reading WHERE device_id = '246F28D0ED58' order by reading_time desc limit 15";
$result = $conn->query($sql);

// store data in array
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// build response object
$response = array(
    'reading_time' => array(),
    'water_level' => array(),
    'rainfall' => array(),
    'temperature' => array(),
    'humidity' => array()
);

// loop through data and add to response object
foreach ($data as $row) {
    $response['reading_time'][] = $row['reading_time'];
    $response['water_level'][] = $row['water_level'];
    $response['rainfall'][] = $row['rainfall'];
    $response['temperature'][] = $row['temperature'];
    $response['humidity'][] = $row['humidity'];
}

// set response headers and echo JSON-encoded response
header('Content-Type: application/json');
echo json_encode($response);

?>
