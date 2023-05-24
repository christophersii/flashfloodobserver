<?php
    // Include your database configuration file
    include 'config.php';

    // Check if the request method is POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Get the admin_id from the POST request
        $admin_id = $_POST['admin_id'];
        
        // Create the SQL query to delete all notifications from the admin
        $query = "DELETE FROM adminnotification WHERE admin_id = ?";
        
        // Create a prepared statement
        if ($stmt = $conn->prepare($query)) {
            // Bind the admin_id parameter
            $stmt->bind_param('i', $admin_id);

            // Execute the query
            if ($stmt->execute()) {
                // If successfully executed, send a success response
                echo json_encode(array("statusCode" => 200));
            } else {
                // If an error occurred, send an error message
                echo json_encode(array("statusCode" => 201, "message" => "Error deleting records"));
            }
        } else {
            // If an error occurred, send an error message
            echo json_encode(array("statusCode" => 201, "message" => "Error preparing statement"));
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
?>
