<?php
include '../connection.php';
session_start();
$username = $conn->real_escape_string($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$result = $conn->query("SELECT * FROM users WHERE username = '$username' and password = '$password'");
if($result->num_rows > 0){
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['fullname'] =  $user['firstname']." ".$user['middlename']." ".$user['lastname'];
   echo "1";
} else {
      echo "0";
}
?>