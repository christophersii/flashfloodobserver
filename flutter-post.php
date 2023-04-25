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
//$sql = "SELECT device_id, reading_id, water_level, rainfall, temperature, humidity, reading_time FROM sensor_reading WHERE device_id = '246F28D0ED58' order by reading_time desc limit 10";
//$result = $conn->query($sql);

// Get the device_id from the POST request
$station_code = $_POST["station_code"];

// Use the device_id in the SQL query
$sql = "SELECT sr.device_id, sr.reading_id, sr.water_level, sr.rainfall, sr.temperature, sr.humidity, sr.reading_time
FROM sensor_reading sr
INNER JOIN station st ON sr.device_id = st.device_id
WHERE st.station_code = '$station_code'
ORDER BY sr.reading_time DESC
LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $device_id);
$stmt->execute();
$result = $stmt->get_result();

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
