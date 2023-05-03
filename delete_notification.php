<?php
include 'config.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the admin_notify_id from the POST request
$admin_notify_id = $_POST['admin_notify_id'];

// Prepare a delete statement
$sql = "DELETE FROM adminnotifications WHERE admin_notify_id = ?";

if ($stmt = $conn->prepare($sql)) {
    // Bind the admin_notify_id parameter to the prepared statement
    $stmt->bind_param("s", $admin_notify_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Notification deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Close the connection
$conn->close();
?>
