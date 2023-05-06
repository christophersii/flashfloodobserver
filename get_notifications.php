<?php
include 'config.php';

$response = array();

if (isset($_POST['admin_id'])) {
    $admin_id = $_POST['admin_id'];

    $sql = "SELECT an.admin_notify_id, an.notify_info, an.noti_time, an.device_id 
            FROM adminnotification an
            WHERE an.admin_id = ?
            ORDER BY an.noti_time DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notification = array(
                'admin_notify_id' => $row['admin_notify_id'],
                'notify_info' => $row['notify_info'],
                'noti_time' => $row['noti_time'],
                'device_id' => $row['device_id'],
                'admin_id' => $admin_id
            );

            array_push($response, $notification);
        }
    }

    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
