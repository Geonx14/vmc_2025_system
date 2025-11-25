<?php
include '../connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user_id = $_POST['user_id'] ?? '';

    if(empty($user_id)){
        echo "Invalid User ID.";
        exit;
    }

    // Prepare and execute delete query
    $sql = "DELETE FROM users WHERE user_id = $user_id";
    if($conn->query($sql)){
        echo "1";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>