<?php

include("connection.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "SELECT setting.setting_id, setting.station_code, setting.station_name, station.longitude, station.latitude FROM setting
        JOIN station ON setting.station_code = station.station_code";

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

        $data[] = array(
            'setting_id' => $settingId,
            'station_code' => $stationCode,
            'station_name' => $stationName,
            'latitude' => $latitude,
            'longitude' => $longitude,
        );
    }
}

$conn->close();

echo json_encode($data);

?>
