<?php
  // Include the database connection details
  include('config.php');

  $admin_email = $_POST['admin_email'];
  $admin_password = $_POST['admin_password'];

  $sql = "SELECT * FROM admin WHERE admin_email = '$admin_email' AND admin_password = '$admin_password'";//get the admin email and password from the database
  $result = mysqli_query($conn, $sql);//get the result from the database

  if (mysqli_num_rows($result) == 1) {
      $row = mysqli_fetch_assoc($result);//get the row from the database
      $admin_name = $row["admin_name"];//get the email from the database
      echo json_encode(["status" => "Success", "admin_name" => $admin_name]);//send the name to the app
  } else {
      echo json_encode(["status" => "Error"]);
  }
?>
