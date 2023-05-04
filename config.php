<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername= "us-cdbr-east-06.cleardb.net";
//$servername= "localhost";
$username= "b005c8a97ae61d";
$password= "04eca8ce";
$dbname= "heroku_3442ee38bf9fb24";

$conn= new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$conn->query("SET time_zone='+08:00'");

// $servername= "localhost";
// $username= "root";
// $password= "";
// $dbname= "heroku_3442ee38bf9fb24";

// $conn= new mysqli($servername, $username, $password, $dbname);
