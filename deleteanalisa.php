<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])){
    header("Location: ./login.php");
    exit();
}

include("connect.php"); // Ensure connect.php uses mysqli and sets up $conn correctly

// Validate and sanitize the input
$id = isset($_GET['idk']) ? intval($_GET['idk']) : 0;

if ($id > 0) {
    // Prepare and execute the DELETE query
    $delete_kelas = "DELETE FROM analisa WHERE id_analisa = ?";
    if ($stmt = $conn->prepare($delete_kelas)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Redirect to the analysis page
            header("Location: ./analisa.php");
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
