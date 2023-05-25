<?php
    include("config.php");

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    // Prepare statement
    $stmt = $conn->prepare("DELETE FROM station WHERE station_code = ?");

    // Bind the 'station_code' parameter to the SQL statement. 's' stands for string.
    $stmt->bind_param("s", $_POST['station_code']);

    // Execute the prepared statement
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Station successfully deleted.";
    } else {
        echo "No station found with that code.";
    }

    // Close the prepared statement and the database connection
    $stmt->close();
    $conn->close();
?>
