<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("config.php");

$api_key_value = "tPmAT5Ab3j7F9";

$api_key = $device_id = $water_level = $rainfall = $temperature = $humidity = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $device_id = test_input($_POST["device_id"]);
        $water_level = test_input($_POST["water_level"]);
        $rainfall = test_input($_POST["rainfall"]);
        $temperature = test_input($_POST["temperature"]);
        $humidity = test_input($_POST["humidity"]);
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        // Set timezone
        date_default_timezone_set("Asia/Kuala_Lumpur");

        // Get current date
        $current_date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO sensor_reading (device_id, water_level, rainfall, temperature, humidity, timestamp)
        VALUES ('" . $device_id . "', '" . $water_level . "', '" . $rainfall . "', '" . $temperature . "', '" . $humidity . "', '" . $reading_time . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
