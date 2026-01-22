<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $query_list = "";
    $username = $_POST['username'];
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    foreach ($_POST as $key => $value) {
        if ($key != 'user_id') {
            $query_list .= $key . " = '" . $conn->real_escape_string($value) . "', ";
        }
    }
    $query_list = rtrim($query_list, ", ");

    // Check if username exists (exclude current user if updating)
    $check_sql = "SELECT * FROM users WHERE username = '$username'";
    if ($user_id) {
        $check_sql .= " AND user_id != $user_id";
    }
    $check = $conn->query($check_sql)->num_rows;
    if ($check > 0) {
        echo "Username already exists. Please choose a different username";
        exit;
    }


    if ($user_id) { // Update existing user
        $sql = "UPDATE users SET {$query_list}
                    WHERE user_id = $user_id";
        $msg = "Account successfully updated.";
    } else { // Insert new user
        $sql = "INSERT INTO users SET {$query_list}";

        $msg = "Account successfully created.";
    }

    if ($conn->query($sql)) {
        echo "1";
        exit;
    } else {
        echo "0";
        exit;
    }
}
