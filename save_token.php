<?php
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $admin_id = $_POST['admin_id'];

    // Check if token already exists
    $checkToken = "SELECT * FROM fcm_tokens WHERE token='$token'";
    $result = mysqli_query($conn, $checkToken);

    if (mysqli_num_rows($result) == 0) {
        // Token doesn't exist, insert it into the database
        $insertToken = "INSERT INTO fcm_tokens (token, admin_id) VALUES ('$token', '$admin_id')";
        if (mysqli_query($conn, $insertToken)) {
            echo json_encode(['message' => 'Token saved']);
        } else {
            echo json_encode(['message' => 'Error saving token']);
        }
    } else {
        echo json_encode(['message' => 'Token already exists']);
    }

    mysqli_close($conn);
}
?>
