<?php
include("connection.php");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT level FROM setting";

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $level = strval($row["level"]);
        
        $data[] = array(
            'level' => $level,
        );
    }
}

$conn->close();

echo json_encode($data);
?>
