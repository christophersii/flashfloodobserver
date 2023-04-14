<?php
    header('Content-Type: application/json');

    $device_id = $_POST['device_id'];

      // Include the database connection details
      include('config.php');

    // Check connection
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Query to get the latest water level reading for the device
    $sql = "SELECT water_level FROM sensor_reading WHERE device_id = '$device_id' ORDER BY reading_time DESC LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the latest water level reading as a string
        $reading = mysqli_fetch_assoc($result)['water_level'];
        echo json_encode(['status' => 'Success', 'reading' => $reading]);
    } else {
        echo json_encode(['status' => 'Failed']);
    }

    // Close connection
    mysqli_close($conn);
?>
