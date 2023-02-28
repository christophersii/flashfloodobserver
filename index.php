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
        echo "<tr>"
        echo "<td>".$no."</td>";
        echo "<td>".$row['admin_id']."</td>";
        echo "<td>".$row['admin_username']."</td>";
        echo "<td>".$row['admin_password']."</td>";
        echo "<td>".$row['admin_email']."</td>";
        echo "<td>".$row['admin_phone']."</td>";
        echo "</tr>";
        $no++;
    }
    ?>
</table>
