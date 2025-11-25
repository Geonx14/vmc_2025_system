<?php
include '../connection.php';
$delete_id = $_POST['delete_id'];
$delete = $conn->query("DELETE FROM users WHERE user_id='$delete_id'");
if($delete){
    echo json_encode(['status'=>'success', 'message'=>'User deleted successfully.']);
} else {
    echo json_encode(['status'=>'error', 'message'=>'Failed to delete user.']);
}
?>