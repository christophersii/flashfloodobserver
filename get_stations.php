<?php

  // Include the database connection details
  include('config.php');

 // Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin_id from POST request
$admin_id = $_POST['admin_id'];

// SQL query to fetch stations for the logged-in admin user
$sql = "SELECT * FROM station WHERE admin_id = '$admin_id'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
} else {
    echo "0 results";
}

$conn->close();
?>
