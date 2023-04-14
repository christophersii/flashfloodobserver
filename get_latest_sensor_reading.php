<?php
  // Set headers to allow CORS
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET');

  // Get the station code from the request parameters
  $stationCode = $_GET['station_code'];

  // Include the database connection details
  include('config.php');

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Query to get the latest water level value for the specified station
  $sql = "SELECT water_level FROM sensor_device d
          JOIN sensor_reading r ON d.device_id = r.device_id
          WHERE d.station_code = '$stationCode'
          ORDER BY reading_time DESC
          LIMIT 1";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    // Return the latest water level value as a JSON object
    $row = mysqli_fetch_assoc($result);
    $waterLevel = $row["water_level"];
    $response = array("water_level" => $waterLevel);
    echo json_encode($response);
  } else {
    // If no results are found, return an error message
    $response = array("error" => "No water level readings found for this station");
    echo json_encode($response);
  }

  mysqli_close($conn);
?>
