<?php
    header("Content-Type: application/json; charset=UTF-8");

    // Retrieve POST data
    $admin_id = $_POST['admin_id'];

    include 'config.php';

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get the latest water level reading for each station
    $sql = "SELECT s.station_code, s.station_name, sr.water_level
            FROM station s
            LEFT JOIN sensor_device sd ON s.station_code = sd.station_code
            LEFT JOIN sensor_reading sr ON sd.device_id = sr.device_id
            WHERE s.admin_id = '$admin_id'
            GROUP BY s.station_code
            ORDER BY s.station_name";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Convert MySQL result set to JSON format
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo "0 results";
    }

    $conn->close();
?>
