<?php

  // Include the database connection details
  include('config.php');

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get admin ID from session or request parameters
  $admin_id = $_SESSION['admin_id'] ?? $_POST['admin_id'];

  // Prepare and execute query to retrieve stations for the given admin ID
  $sql = "SELECT * FROM station WHERE admin_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $admin_id);
  $stmt->execute();

  // Fetch results and store them in an array
  $result = $stmt->get_result();
  $stations = array();
  while ($row = $result->fetch_assoc()) {
    $stations[] = $row;
  }

  // Close statement and database connection
  $stmt->close();
  $conn->close();

  // Return stations as JSON object
  echo json_encode($stations);

?>
