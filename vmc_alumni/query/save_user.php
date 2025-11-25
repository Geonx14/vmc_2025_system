<?php
include '../connection.php';
extract($_POST);

// Sanitize normal inputs
$firstname = mysqli_real_escape_string($conn, trim($firstname));
$middlename = mysqli_real_escape_string($conn, trim($middlename));
$lastname = mysqli_real_escape_string($conn, trim($lastname));
$birthday = mysqli_real_escape_string($conn, trim($birthday));
$username = mysqli_real_escape_string($conn, trim($username));
$password = mysqli_real_escape_string($conn, trim($password));
$user_type = mysqli_real_escape_string($conn, trim($user_type));
$mobile = mysqli_real_escape_string($conn, trim($mobile));
$year_graduated = isset($year_graduated) ? mysqli_real_escape_string($conn, trim($year_graduated)) : '';
$avatar_file = "";

// Handle Avatar Upload (if uploaded)
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
    $targetDir = "uploads/avatars/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
        $avatar_file = $fileName;
    }
}
$student_course_exists = $conn->query("SELECT * FROM st_course where student_id='$user_id'");

if($student_course_exists->num_rows > 0){
    if($user_type == 'student' || $user_type == 'alumni'){
      $conn->query("update st_course set course='$course',year_graduated = '$year_graduated' where student_id='$user_id'");
    } 
}

if (isset($user_id) && !empty($user_id)) {

    $updateQuery = "
        UPDATE users SET 
            firstname='$firstname',
            middlename='$middlename',
            lastname='$lastname',
            birthday='$birthday',
            username='$username',
            password='$password',
            user_type='$user_type',
            mobile='$mobile'";

    // Update avatar only if uploaded
    if ($avatar_file != "") {
        $updateQuery .= ", avatar='$avatar_file'";
    }

    $updateQuery .= " WHERE user_id=$user_id";

    $update = $conn->query($updateQuery);

    if ($update) {
        echo "2";
    } else {
        echo "0";
    }

}
else{
$check_username_query = $conn->query("SELECT * FROM users WHERE username='$username'");
if ($check_username_query->num_rows > 0) {
    echo "3";
    exit;
}
// INSERT new user
$insert = $conn->query("
    INSERT INTO users (firstname, middlename, lastname, birthday, username, password, avatar, user_type, mobile, date_created)
    VALUES ('$firstname', '$middlename', '$lastname', '$birthday', '$username', '$password', '$avatar_file', '$user_type', '$mobile', NOW())
");
$inserted_user_id = $conn->insert_id;

if ($insert) {
     if($user_type == 'student' || $user_type == 'alumni'){
        $conn->query("insert into st_course (student_id, course,year_graduated) values ('$inserted_user_id', '$course','$year_graduated')");
    }
    echo "1";
} else {
    echo "0";
}
}
?>
