<?php

include("connection.php");

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

//POST = send/save data to database
//GET = get data from database

$username = $_POST["username"];
$password = $_POST["password"];
$user_email = $_POST["user_email"];


if(empty($username) || empty($password) || empty($user_email) ){
    echo "Please fill in all the fields.";
}

$sql = "INSERT INTO user (username, password, user_email) VALUES ('$username', '$password', '$user_email')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "Success"]);
    } else {
        echo json_encode(["status" => "Error"]);
    }

mysqli_close($conn);

?>
