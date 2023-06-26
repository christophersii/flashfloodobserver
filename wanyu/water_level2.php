<?php

// include the connection.php file to connect to the database
include("connection.php");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$data = array(); // initialize an empty array to hold the data

$station_result = $conn->query("SELECT station.station_code, station.station_name, station.drainage_depth, sensor_reading.water_level, station.drainage_depth - sensor_reading.water_level AS drainage_water_level, sensor_reading.reading_time, sensor_device.device_id FROM station 
JOIN sensor_device ON station.station_code = sensor_device.station_code
JOIN sensor_reading ON sensor_reading.device_id = sensor_device.device_id
WHERE sensor_reading.reading_time = (SELECT MAX(reading_time) FROM sensor_reading WHERE device_id = sensor_device.device_id)");

while ($station_row = $station_result->fetch_assoc()) {
    $station_id = $station_row["station_code"];
    $device_id = $station_row["device_id"];
    $station_name = $station_row["station_name"];
    $water_level = $station_row["water_level"];
    $reading_time = $station_row["reading_time"];
    $drainage_depth = $station_row["drainage_depth"];
   // Subtract water level from drainage depth
   $drainage_water_level = $drainage_depth - ($water_level -25);
                                                           
    // Calculate the normal, alert, warning, and danger levels based on the water level data
    $normal_level = $drainage_depth * 0.5;
    $warning_level = $drainage_depth * 0.7;
    $danger_level = $drainage_depth;
    $level = "";
    if ($drainage_water_level < $normal_level) {
        $level = "Normal";
    } elseif ($drainage_water_level >= $normal_level && $drainage_water_level < $warning_level) {
        $level = "Alert";
    } elseif ($drainage_water_level >= $warning_level && $drainage_water_level < $danger_level) {
        $level = "Warning";
    } elseif ($drainage_water_level >= $danger_level) {
        $level = "Danger";
    }

    // Add the data for this station to the array
    $data[] = array(
        'station_code' => $station_id,
        'station_name' => $station_name,
        'water_level' => $water_level,
        'reading_time' => $reading_time,
        'normal_level' => $normal_level,
        'warning_level' => $warning_level,
        'danger_level' => $danger_level,
        'level' => $level,
        'drainage_water_level' => $drainage_water_level,
    );

    // Check if there are any existing rows in the setting table for this station code
    // Check if there are any existing rows in the setting table for this station code
    $existing_result = $conn->query("SELECT * FROM setting WHERE station_code='$station_id'");
    if ($existing_result !== false && mysqli_num_rows($existing_result) > 0) {
        // Update the existing row with the new water level data, level and reading_time
        $conn->query("UPDATE setting SET station_name='$station_name', water_level='$water_level', reading_time='$reading_time', normal_level='$normal_level', warning_level='$warning_level', danger_level='$danger_level', level='$level', device_id='$device_id', drainage_water_level='$drainage_water_level' WHERE station_code='$station_id'");
    }
    else{
        // Insert a new row with the water level data, level and reading_time
        $conn->query("INSERT INTO setting (station_code, station_name, water_level, reading_time, normal_level, warning_level, danger_level, level, device_id, drainage_water_level) VALUES ('$station_id', '$station_name','$water_level', '$reading_time', '$normal_level', '$warning_level', '$danger_level', '$level', '$device_id', '$drainage_water_level')");
    }
    
}

mysqli_close($conn); // Close MySQL connection

// send the data as a JSON response
echo json_encode($data);

?>
