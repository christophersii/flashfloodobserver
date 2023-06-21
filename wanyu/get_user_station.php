<?php
include("connection.php");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the user ID from the parameter
$user_id = $_GET['user_id'];

// Retrieve the user's added stations and their latest readings from the database
$sql = "SELECT user_station.levels, setting.setting_id, setting.station_code, setting.station_name, setting.level,station.longitude, station.latitude, sensor_reading.water_level, sensor_reading.reading_time
        FROM user_station
        JOIN setting ON user_station.setting_id = setting.setting_id
        JOIN station ON setting.station_code = station.station_code
        JOIN sensor_device ON station.station_code = sensor_device.station_code
        JOIN sensor_reading ON sensor_reading.device_id = sensor_device.device_id
        WHERE user_station.user_id = '$user_id'
        AND sensor_reading.reading_time = (SELECT MAX(reading_time) FROM sensor_reading WHERE device_id = sensor_device.device_id)";

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $settingId = strval($row["setting_id"]);
        $stationCode = strval($row["station_code"]);
        $stationName = strval($row["station_name"]);
        $latitude = strval($row["latitude"]);
        $longitude = strval($row["longitude"]);
        $waterLevel = strval($row["water_level"]);
        $readingTime = strval($row["reading_time"]);
        $level = strval($row["level"]);
        $levels = strval($row["levels"]);

        $data[] = array(
            'setting_id' => $settingId,
            'station_code' => $stationCode,
            'station_name' => $stationName,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'water_level' => $waterLevel,
            'reading_time' => $readingTime,
            'level' => $level,
            'levels' => $levels
        );
    }
}

$conn->close();

echo json_encode($data);
?>
