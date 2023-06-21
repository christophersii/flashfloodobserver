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

// update the station ID and levels into the user_station table
$query = "UPDATE user_station SET levels='$levels' WHERE user_id='$user_id' AND setting_id='$setting_id'";
$result = mysqli_query($conn, $query);

// check if the insert was successful
if ($result) {
  echo json_encode($result);
} else {
  echo "Error: " . mysqli_error($conn);
}
?>