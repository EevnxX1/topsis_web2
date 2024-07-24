<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])){
    header("Location: ./login.php");
    exit();
}

include("connect.php"); // Ensure connect.php sets up $conn as a mysqli object

// Validate and sanitize the input
$id = isset($_GET['idk']) ? intval($_GET['idk']) : 0;

if ($id > 0) {
    // Prepare and execute the DELETE query
    $delete_kriteria = "DELETE FROM kriteria WHERE id_kriteria = ?";
    
    if ($stmt = $conn->prepare($delete_kriteria)) {
        $stmt->bind_param("i", $id); // Bind the integer parameter
        if ($stmt->execute()) {
            // Redirect to the kriteria page
            header("Location: kriteria.php");
            exit();
        } else {
            // Handle query execution error
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Handle prepare statement error
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    // Handle invalid ID
    echo "Invalid ID.";
}

$conn->close();
?>
