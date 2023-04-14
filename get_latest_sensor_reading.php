<?php
  // Include the database connection details
  include('config.php');

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $device_id = $_POST["device_id"];

  // Get the latest reading for the specified device
  $sql = "SELECT * FROM sensor_reading WHERE device_id = $device_id ORDER BY reading_time DESC LIMIT 1";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo json_encode($row);
  } else {
      echo "No readings found";
  }

  mysqli_close($conn);
?>
