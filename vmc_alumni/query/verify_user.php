<?php
include '../connection.php';
$user_id = $_POST['user_id'];
$status = $_POST['status'];
$query = "UPDATE users SET status='$status' WHERE user_id=$user_id";
$update = $conn->query($query);
if ($update) {
    echo "1";
} else {
    echo "0";
}
