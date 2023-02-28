<?php
include("config.php");
$query= $connect->query("SELECT * FROM admin");
?>

<table border="1">
    <tr>
        <td>admin_id</td>
        <td>admin_username</td>
        <td>admin_password</td>
        <td>admin_email</td>
        <td>admin_phone</td>
    </tr>

    <?php
    $no= 1;
    while($row= $query->fetch_assoc()){
        echo "<tr>
            <td>$no</td>
            <td>{$row['admin_id']}</td>
            <td>{$row['admin_username']}</td>
            <td>{$row['admin_password']}</td>
            <td>{$row['admin_email']}</td>
            <td>{$row['admin_phone']}</td>
        </tr>";
        $no++;
    }
    ?>
</table>
