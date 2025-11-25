<?php
include '../connection.php';
$request_id = isset($_POST['request_id']) ? $_POST['request_id'] : '';  
$status = isset($_POST['status']) ? $_POST['status'] : '';
$schedule = isset($_POST['schedule']) ? mysqli_real_escape_string($conn, trim($_POST['schedule'])) : '';
if($request_id != ''){
$conn->query("update requests set status='$status' where id='$request_id'");
echo "2";
}
else{
$conn->query("insert into requests (user_id,schedule,status) values ('{$_POST['user_id']}','$schedule','pending')");
echo "1";
}
?>