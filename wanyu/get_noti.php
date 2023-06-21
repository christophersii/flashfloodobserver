<?php

include ('connection.php');

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the user_id from the query string


// Prepare the SQL statement
$stmt = $conn->prepare("SELECT * FROM user_notification WHERE user_id = 1 ORDER BY notify_id DESC");


// Execute the SQL statement
$stmt->execute();

// Bind result variables
$result = $stmt->get_result();

// Fetch all the records and add them to an array
$notifications = array();
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

// Encode the array to a JSON string and print it
echo json_encode($notifications);

// Close the prepared statement and database connection
$stmt->close();
$conn->close();

?>
