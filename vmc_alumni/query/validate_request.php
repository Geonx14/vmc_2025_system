<?php
include '../connection.php';
$request_id = isset($_POST['request_id']) ? $_POST['request_id'] : '';  
$status = isset($_POST['status']) ? $_POST['status'] : '';
$conn->query("update graduation_requests set status='$status'  where request_id='$request_id'");
echo "1";
?>