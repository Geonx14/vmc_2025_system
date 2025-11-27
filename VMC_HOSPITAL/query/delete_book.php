<?php
include '../connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['id'] ?? '';

    // Prepare and execute delete query
    $sql = "DELETE FROM appointments WHERE appointment_id = $id";
    if($conn->query($sql)){
        echo "1";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>