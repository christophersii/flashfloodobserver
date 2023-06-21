<?php
include("connection.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT setting_id, level FROM setting";

$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $settingId = strval($row["setting_id"]);
        $level = strval($row["level"]);
        
        $data[] = array(
            'setting_id' => $settingId,
            'level' => $level,
        );
    }
}

$conn->close();

echo json_encode($data);
?>
