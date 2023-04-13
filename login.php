<?php
  // Include the database connection details
  include('config.php');

  $admin_email = $_POST['admin_email'];
  $admin_password = $_POST['admin_password'];

  $sql = "SELECT * FROM admin WHERE admin_email = '$admin_email' AND admin_password = '$admin_password'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) {
      $row = mysqli_fetch_assoc($result);
      $admin_id = $row["admin_id"]; // retrieve the admin_id
      $admin_name = $row["admin_name"];
      echo json_encode(["status" => "Success", "admin_name" => $admin_name, "admin_id" => $admin_id]); // return the admin_id along with the admin_name
  } else {
      echo json_encode(["status" => "Error"]);
  }
?>
