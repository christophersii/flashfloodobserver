<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("config.php");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$station_code = $_POST['station_code'];

$sql = "SELECT sr.device_id, sr.reading_id, sr.water_level, s.drainage_depth - (sr.water_level - 30) AS drainage_water_level, sr.rainfall, sr.temperature, sr.humidity, sr.reading_time 
FROM sensor_reading sr
JOIN sensor_device sd ON sr.device_id = sd.device_id
JOIN station s ON sd.station_code = s.station_code
WHERE sd.station_code = '$station_code'
ORDER BY sr.reading_time DESC 
LIMIT 10";

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$response = array(
    'reading_time' => array(),
    'water_level' => array(),
    'drainage_water_level' => array(),
    'rainfall' => array(),
    'temperature' => array(),
    'humidity' => array()
);

foreach ($data as $row) {
    $response['reading_time'][] = $row['reading_time'];
    //$response['water_level'][] = $row['drainage_water_level'];
    //$response['water_level'][] = $row['water_level'];
    $response['drainage_water_level'][] = $row['drainage_water_level'];
    $response['rainfall'][] = $row['rainfall'];
    $response['temperature'][] = $row['temperature'];
    $response['humidity'][] = $row['humidity'];
}

header('Content-Type: application/json');
echo json_encode($response);

?>
