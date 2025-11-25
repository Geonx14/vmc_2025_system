<?php
include '../connection.php';
$request_id = isset($_POST['request_id']) ? $_POST['request_id'] : '';  
$status = isset($_POST['status']) ? $_POST['status'] : '';
$schedule = isset($_POST['schedule']) ? mysqli_real_escape_string($conn, trim($_POST['schedule'])) : '';
if($request_id != ''){
$conn->query("update graduation_requests set request_date ='$schedule'  where request_id='$request_id'");
echo "2";
}
else{
$conn->query("insert into graduation_requests (student_id,request_date,status) values ('{$_POST['user_id']}','$schedule','pending')");
echo "1";
}
?>