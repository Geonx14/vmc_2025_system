<?php
include '../connection.php';

extract($_POST);

if (isset($user_id)) {
    $query_list = "";
    foreach ($_POST as $key => $value) {
        if ($key != 'user_id' && $key != 'password') {
            $query_list .= $key . " = '" . $conn->real_escape_string($value) . "', ";
        }
    }
    if (!empty($password)) {
        $query_list .= "password = '" . $conn->real_escape_string($password) . "',";
    }
    $query = rtrim($query_list, ", ");

    $username_check = $conn->query("SELECT * FROM users WHERE username = '$username' AND user_id != $user_id");
    if ($username_check->num_rows > 0) {
        echo 2;
        exit;
    }
    $update = $conn->query("UPDATE users SET " . $query . " WHERE user_id = " . $user_id);
    if ($update) {
        echo 1;
    } else {
        echo 0;
    }
}
