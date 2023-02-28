<?php
include("config.php");
$query= $connect->query("SELECT * FROM admin");
?>

<table border="1">
    <tr>
        <td>Admin ID</td>
        <td>Admin Name</td>
        <td>Password</td>
        <td>E-mail</td>
        <td>Phone</td>
    </tr>

    <?php
    $no= 1;
    while($row= $query->fetch_assoc()){
        echo "<tr>
            <td>{$row['admin_id']}</td>
            <td>{$row['admin_name']}</td>
            <td>{$row['admin_password']}</td>
            <td>{$row['admin_address']}</td>
            <td>{$row['admin_contact_no']}</td>
        </tr>";
        $no++;
    }
    ?>
</table>