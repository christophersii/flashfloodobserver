<?php

include("config.php");

// Create connection
//$conn = mysqli_connect($host, $username, $password, $dbname);

//POST = send/save data to database
//GET = get data from database

$admin_email = $_POST["admin_email"];
$admin_password = $_POST["admin_password"];
$admin_name = $_POST["admin_name"];
$admin_phone= $_POST["admin_phone"];


if(empty($admin_email) || empty($admin_password) || empty($admin_name) || empty($admin_phone) ){
    echo "Please fill in all the fields.";
}

$sql = "INSERT INTO user (admin_email, admin_password, admin_name, admin_phone) VALUES ('$admin_email', '$admin_password', '$admin_name', '$admin_phone')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "Success"]);
    } else {
        echo json_encode(["status" => "Error"]);
    }

?>
