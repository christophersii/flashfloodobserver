<?php
    require_once "config.php";

    $deviceId = $_POST['device_id'];

    $query = "SELECT water_level FROM sensor_reading WHERE device_id = '$deviceId' ORDER BY reading_time DESC LIMIT 1";
    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $waterLevel = $row['water_level'];
        $response['status'] = "Success";
        $response['water_level'] = $waterLevel;
    }else{
        $response['status'] = "Failed";
        $response['message'] = "Failed to get latest sensor reading.";
    }

    echo json_encode($response);
?>```
