<?php
include("config.php");
$query= $connect->query("SELECT * FROM admin");
?>

<table border="1">
    <tr>
        <td>Admin ID</td>
        <td>Username</td>
        <td>Password</td>
        <td>E-mail</td>
        <td>Phone</td>
    </tr>

    <?php
    $no= 1;
    while($row= $query->fetch_assoc()){
        echo "<tr>
            <td>$no</td>
            <td>{$row['admin_id']}</td>
            <td>{$row['admin_user']}</td>
            <td>{$row['admin_password']}</td>
            <td>{$row['admin_address']}</td>
            <td>{$row['contact_no']}</td>
        </tr>";
        $no++;
    }
    ?>
</table>
