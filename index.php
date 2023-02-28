<?php
include("config.php");

$query= $connect->query("SELECT * FROM `admin`");
?>

<table border="1">
    <tr>
        <th>admin_id</th>
        <th>admin_username</th>
        <th>admin_password</th>
        <th>admin_email</th>
        <th>admin_phone</th>
    </tr>

    <?php
    $no= 1;
    while($row= $query->fetch_assoc()){
        echo "<tr>";
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
