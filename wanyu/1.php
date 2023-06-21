<?php

// Include the database connection details
include('connection.php');

$username = $_POST['username'];
$password = $_POST['password'];//

$sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";//get the username and password from the database
$result = mysqli_query($conn, $sql);//get the result from the database

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);//get the row from the database
    $email = $row["user_email"];//get the email from the database
    echo json_encode(["status" => "Success", "email" => $email]);//send the email to the app
} else {
    echo json_encode(["status" => "Error"]);
}
?>
