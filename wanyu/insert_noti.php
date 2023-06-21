<?php

include ('connection.php');

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the POST data
$userId = $_POST['user_id'];
$title = $_POST['title'];
$body = $_POST['body'];
$date = date("Y-m-d");
$time = date("H:i:s");

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO user_notification (user_id, title, body, date, time) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $userId, $title, $body, $date, $time);

// Execute the SQL statement
if ($stmt->execute() === TRUE) {
  echo "Notification saved successfully";
} else {
  echo "Error: " . $stmt->error;
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>
