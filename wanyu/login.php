<?php
include('connection.php');
// get the POST data sent by the Flutter app
$user_email = $_POST['user_email'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE user_email = '$user_email' AND password = '$password'";//get the username and password from the database
$result = mysqli_query($conn, $sql);//get the result from the database

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);//get the row from the database
    $response = array(
        'user_id' => $row['user_id'],
        'username' => $row['username'],
        'user_email' => $row['user_email']
    );//get the email from the database
    echo json_encode(["status" => "Success", $response]);//send the email to the app
} else {
    echo json_encode(["status" => "Error"]);
}

?>
