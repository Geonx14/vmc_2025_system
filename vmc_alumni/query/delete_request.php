<?php
include '../connection.php';
$request_id = isset($_POST['request_id']) ? $_POST['request_id'] : '';  
$delete_request = $conn->query("DELETE FROM graduation_requests WHERE request_id='$request_id'");
if($delete_request){    
    echo "1";
}
?>