<?php
include '../connection.php';
$delete_id = $_POST['delete_id'];
$delete = $conn->query("DELETE FROM users WHERE user_id='$delete_id'");
if($delete){
    echo "1";
} else {
    echo "0";
}
?>