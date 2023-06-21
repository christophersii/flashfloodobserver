<?php
// include the connection.php file to connect to the database
include("connection.php");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// get the selected station's ID and levels from the parameters
$user_id = $_POST['user_id'];
$setting_id = $_POST['setting_id'];
$levels = $_POST['levels']; // Comma-separated string of levels

// insert the station ID and levels into the user_station table
$query = "INSERT INTO user_station (user_id, setting_id, levels) VALUES ('$user_id', '$setting_id', '$levels')";
$result = mysqli_query($conn, $query);

// check if the insert was successful
if ($result) {
  echo json_encode($result);
} else {
  echo "Error: " . mysqli_error($conn);
}
?>