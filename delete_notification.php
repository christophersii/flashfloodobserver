<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the admin_notify_id from the POST request
$admin_notify_id = $_POST['admin_notify_id'];
echo "Received admin_notify_id: " . $admin_notify_id . "\n";

// Prepare a delete statement
$sql = "DELETE FROM adminnotifications WHERE admin_notify_id = ?";

if ($stmt = $conn->prepare($sql)) {
    echo "Statement prepared successfully\n";
  
    // Bind the admin_notify_id parameter to the prepared statement
    $stmt->bind_param("s", $admin_notify_id);

    // Execute the statement
    if ($stmt->execute()) {
      if ($stmt->affected_rows > 0) {
          echo "Notification deleted successfully";
      } else {
          echo "No notification found with admin_notify_id: " . $admin_notify_id;
      }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error . "\n";
}

// Close the connection
$conn->close();
?>
