<?php
include 'config.php';

// Fetch all station thresholds and latest readings
$query = "SELECT s.station_code, s.station_name, s.threshold_alert, s.threshold_warning, s.threshold_danger, r.water_level, r.device_id
          FROM station s
          INNER JOIN sensor_device sd ON s.station_code = sd.station_code
          INNER JOIN (
            SELECT device_id, MAX(reading_time) as latest_reading_time
            FROM sensor_reading
            GROUP BY device_id
          ) latest ON sd.device_id = latest.device_id
          INNER JOIN sensor_reading r ON latest.device_id = r.device_id AND latest.latest_reading_time = r.reading_time";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $device_id = $row['device_id'];
        $station_code = $row['station_code'];
        $station_name = $row['station_name'];
        $water_level = $row['water_level'];
        $threshold_alert = $row['threshold_alert'];
        $threshold_warning = $row['threshold_warning'];
        $threshold_danger = $row['threshold_danger'];

        // Check if water level reaches any thresholds
        $notify_info = '';
        if ($water_level > $threshold_danger) {
            $notify_info = "Danger: \r\nWater level at station $station_name \r\n(device ID: $device_id) has reached the danger threshold.";
        } elseif ($water_level >= $threshold_warning) {
            $notify_info = "Warning: \r\nWater level at station $station_name \r\n(device ID: $device_id) has reached the warning threshold.";
        } elseif ($water_level >= $threshold_alert) {
            $notify_info = "Alert: \r\nWater level at station $station_name \r\n(device ID: $device_id) has reached the alert threshold.";
        }

        // If a notification is required, insert it into the adminnotification table
        if ($notify_info) {
            $insert_query = "INSERT INTO adminnotification (notify_info, noti_time, device_id) VALUES (?, NOW(), ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ss", $notify_info, $device_id);
            $stmt->execute();
        }
    }
}

$conn->close();
?>
