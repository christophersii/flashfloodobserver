<?php
header('Content-Type: application/json');
require_once 'config.php';

$response = array('success' => false);

if (isset($_POST['device_id']) && isset($_POST['admin_ids']) && isset($_POST['station_name']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['drainage_depth']) && isset($_POST['threshold_alert']) && isset($_POST['threshold_warning']) && isset($_POST['threshold_danger'])) {
    $device_id = $_POST['device_id'];
    $admin_ids = $_POST['admin_ids'];
    $station_name = $_POST['station_name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $drainage_depth = $_POST['drainage_depth'];
    $threshold_alert = $_POST['threshold_alert'];
    $threshold_warning = $_POST['threshold_warning'];
    $threshold_danger = $_POST['threshold_danger'];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if device_id already exists
    $stmt_check = $conn->prepare("SELECT device_id FROM sensor_device WHERE device_id = ?");
    $stmt_check->bind_param("s", $device_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $response['message'] = "Error: device_id already exists";
        $stmt_check->close();
    } else {
        $stmt_check->close();

        // Proceed with the insertion
        $stmt = $conn->prepare("INSERT INTO station (station_name, latitude, longitude, drainage_depth, threshold_alert, threshold_warning, threshold_danger) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sddssss", $station_name, $latitude, $longitude, $drainage_depth, $threshold_alert, $threshold_warning, $threshold_danger);

        if ($stmt->execute()) {
            $station_code = $conn->insert_id;

            $stmt2 = $conn->prepare("INSERT INTO sensor_device (device_id, station_code) VALUES (?, ?)");
            $stmt2->bind_param("si", $device_id, $station_code);

            if ($stmt2->execute()) {
                $device_id_fk = $conn->insert_id;

                $stmt3 = $conn->prepare("INSERT INTO admin_sensor_device (device_id, admin_id) VALUES (?, ?)");
                $success_count = 0;

                foreach ($admin_ids as $admin_id) {
                    $stmt3->bind_param("ii", $device_id_fk, $admin_id);

                    if ($stmt3->execute()) {
                        $success_count++;
                    }
                }

                if ($success_count == count($admin_ids)) {
                    $response['success'] = true;
                } else {
                    $response['message'] = "Error inserting some admin_sensor_device entries: " . $stmt3->error;
                }
                
                $stmt3->close();
                } else {
                    $response['message'] = "Error inserting device: " . $stmt2->error;
                }
            
                $stmt2->close();
                } else {
                    $response['message'] = "Error inserting station: " . $stmt->error;
                }
            $stmt->close();
        }
    $conn->close();
}

echo json_encode($response);
?>
