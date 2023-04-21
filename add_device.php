<?php
    // Database connection details
    include('config.php');
      
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get POST data
    $admin_id = $_POST['admin_id'];
    $device_id = $_POST['device_id'];
    $station_name = $_POST['station_name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $threshold_alert = $_POST['threshold_alert'];
    $threshold_warning = $_POST['threshold_warning'];
    $threshold_danger = $_POST['threshold_danger'];

    // Insert data into station table
    $station_sql = "INSERT INTO station (station_name, latitude, longitude, threshold_alert, threshold_warning, threshold_danger, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($station_sql);
    $stmt->bind_param("sddsssi", $station_name, $latitude, $longitude, $threshold_alert, $threshold_warning, $threshold_danger, $admin_id);
    $station_insert_result = $stmt->execute();

    if ($station_insert_result) {
        // Get the generated station_code
        $station_code = $conn->insert_id;

        // Insert data into sensor_device table
        $sensor_device_sql = "INSERT INTO sensor_device (device_id, admin_id, station_code) VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sensor_device_sql);
        $stmt2->bind_param("sii", $device_id, $admin_id, $station_code);
        $sensor_device_insert_result = $stmt2->execute();

        if ($sensor_device_insert_result) {
            $response = array("status" => "Success", "message" => "Device added successfully!");
        } else {
            $response = array("status" => "Failed", "message" => "Failed to add device to sensor_device table!");
        }
    } else {
        $response = array("status" => "Failed", "message" => "Failed to add station!");
    }

    // Close the prepared statements
    $stmt->close();
    $stmt2->close();

    // Close the connection
    $conn->close();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
?>
