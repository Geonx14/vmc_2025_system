<?php
include '../connection.php';

extract($_POST);

if(isset($user_id)){
    $data = " firstname = '$firstname' ";
    $data .= ", middlename = '$middlename' ";
    $data .= ", lastname = '$lastname' ";
    $data .= ", username = '$username' ";
    $data .= ", contact_number = '$contact_number' ";
    if(!empty($password)){
        $data .= ", password = '".$password."' ";
    }
$username_check = $conn->query("SELECT * FROM users WHERE username = '$username' AND user_id != $user_id");
    if($username_check->num_rows > 0){
        echo 2;
        exit;
    }
    $update = $conn->query("UPDATE users SET ".$data." WHERE user_id = ".$user_id);
    if($update){
        echo 1;
    }else{
        echo 0;
    }
}
?>