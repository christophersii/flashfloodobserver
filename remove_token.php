<?php
require_once('config.php');

function removeTokenFromDB($token, $conn) {
    $removeToken = "DELETE FROM fcm_tokens WHERE token='$token'";
    if (mysqli_query($conn, $removeToken)) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $tokensToRemove = $data['tokens'];

    foreach ($tokensToRemove as $token) {
        if (removeTokenFromDB($token, $conn)) {
            echo "Removed token: $token\n";
        } else {
            echo "Error removing token: $token\n";
        }
    }
    
    mysqli_close($conn);
}
?>
