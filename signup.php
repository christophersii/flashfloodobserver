<?php

require_once("config.php");

$admin_email = $_POST['admin_email'];
$admin_name = $_POST['admin_name'];
$admin_password = $_POST['admin_password'];
$admin_phone = $_POST['admin_phone'];

// check if account is registered

$query = "SELECT * FROM admin WHERE admin_email LIKE '$admin_mail'";
$res = mysqli_query($conn, $query);
$data = mysqli_fetch_array($res);

if($data[0]>1){
    // account exists
    echo json_encode("account already exists");
}else{
    // create account
    $query = "INSERT INTO admin (admin_name, admin_email, admin_password, admin_phone) VALUES ('$admin_name', '$admin_email', '$admin_password'), '$admin_phone'";
    $res = mysqli_query($query);
    
    if($res){
        echo json_encode("true");
    }else{
        echo json_encode("false");
    }
}
?>