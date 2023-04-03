<?
require_once("config.php");

$admin_email = $_POST['admin_email'];
$admin_password = $_POST['admin_password'];

// check is account is exists, if not request to create an account

$query = "SELECT * FROM admin WHERE admin_email LIKE '$admin_email' and admin_password LIKE '$admin_password'";
$res = mysqli_query($conn, $query);
$data = mysqli_fetch_array($res);

// data[0] = admin_id, data[1] = admin_name, data[2] = admin_email, data[3] = admin_password, data[4] = admin_phone
if($data[2]>=1){
    // account exists
    $query = "SELECT * FROM admin WHERE admin_password LIKE '$admin_password'";
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_array($res);

    if($data[3] == $admin_password){
        // password is correct
        echo json_encode("true");

        $resarr = array();
        array_push($resarr, array(
            "admin_id"=>$data[0],
            "admin_name"=>$data[1],
            "admin_email"=>$data[3],
            "admin_phone"=>$data[4]
        ));
        json_encode(array("result"=>$resarr));
    }else{
        // password is incorrect
        echo json_encode("false");
    }
}else{
    //account not exists, Create new account
    echo json_encode("dont have an account");
}

?>