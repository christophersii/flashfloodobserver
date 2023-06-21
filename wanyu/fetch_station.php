<?php

include("connection.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT setting.setting_id, setting.station_code, setting.station_name, setting.level, setting.drainage_water_level, station.longitude, station.latitude, station.drainage_depth, sensor_reading.water_level, sensor_reading.reading_time 
        FROM setting
        JOIN station ON setting.station_code = station.station_code
        JOIN sensor_device ON station.station_code = sensor_device.station_code
        JOIN sensor_reading ON sensor_reading.device_id = sensor_device.device_id
        WHERE sensor_reading.reading_time = (SELECT MAX(reading_time) FROM sensor_reading WHERE device_id = sensor_device.device_id)";

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Convert setting_id and station_name to strings
        $settingId = strval($row["setting_id"]);
        $stationCode = strval($row["station_code"]);
        $stationName = strval($row["station_name"]);
        $latitude = strval($row["latitude"]);
        $longitude = strval($row["longitude"]);
        $drainageDepth = strval($row["drainage_depth"]);
        $waterLevel = strval($row["water_level"]);
        $readingTime = strval($row["reading_time"]);
        $level = strval($row["level"]);
        $reading_time = strval($row["reading_time"]);
        $drainage_water_level = strval($row["drainage_water_level"]);

        $data[] = array(
            'setting_id' => $settingId,
            'station_code' => $stationCode,
            'station_name' => $stationName,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'drainage_depth' => $drainageDepth,
            'water_level' => $waterLevel,
            'reading_time' => $readingTime,
            'level' => $level,
            'reading_time' => $reading_time,
            'drainage_water_level' => $drainage_water_level
        );
    }
}

$conn->close();

echo json_encode($data);

?>
