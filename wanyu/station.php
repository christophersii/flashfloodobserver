<?php

include ("connection.php");
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Perform query and fetch data
$sql = "SELECT station_name, water_level_value FROM station";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  $data = array();
  while($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
} else {
  echo "0 results";
}

$conn->close();

// Output data as JSON
echo json_encode($data);

?>
