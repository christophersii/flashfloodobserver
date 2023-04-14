<?php
require_once 'config.php';

// Get all stations
$sql = "SELECT * FROM station WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_POST['admin_id']);
$stmt->execute();
$result = $stmt->get_result();

$stations = array();
while ($row = $result->fetch_assoc()) {
    $station = array(
        "station_code" => $row['station_code'],
        "station_name" => $row['station_name']
    );
    
    // Get latest sensor reading for station
    $sql2 = "SELECT water_level, reading_time FROM sensor_reading 
             INNER JOIN sensor_device ON sensor_reading.device_id = sensor_device.device_id
             WHERE sensor_device.station_code = ?
             ORDER BY reading_time DESC
             LIMIT 1";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("s", $row['station_code']);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    
    if ($result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $station["water_level"] = $row2["water_level"];
        $station["reading_time"] = $row2["reading_time"];
    } else {
        $station["water_level"] = "N/A";
        $station["reading_time"] = "N/A";
    }
    
    $stations[] = $station;
}

echo json_encode($stations);
$stmt->close();
$stmt2->close();
$conn->close();
?>
