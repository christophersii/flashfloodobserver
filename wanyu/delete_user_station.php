<?php
include("connection.php");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$userId = $_POST['user_id'];
$userStationId = $_POST['setting_id'];

// Prepare the delete statement
$stmt = $conn->prepare("DELETE FROM user_station WHERE user_id = ? AND setting_id = ?");
$stmt->bind_param("ii", $userId, $userStationId);

// Execute the delete statement
if ($stmt->execute()) {
  // Deletion was successful
  $response = array(
    'status' => 'Success',
    'message' => 'Station deleted successfully'
  );
} else {
  // Failed to delete the station
  $response = array(
    'status' => 'Error',
    'message' => 'Failed to delete the station'
  );
}

// Close the statement and database connection
$stmt->close();
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

?>