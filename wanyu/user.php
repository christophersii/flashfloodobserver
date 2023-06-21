<?php

// Include the database connection details
include('connection.php');

// Retrieve user details from database
$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);

// Create an empty array to store user details
$userDetails = array();

// Loop through the results and add them to the array
while ($row = mysqli_fetch_assoc($result)) {
    $userDetails[] = $row;
}

// Return the user details as a JSON-encoded string
echo json_encode($userDetails);

?>
