<?php  
include '../connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanitize input and extract variables
    $user_id = $_POST['user_id'] ?? null; // Only for updates
    $firstname = $conn->real_escape_string($_POST['firstname'] ?? '');
    $middlename = $conn->real_escape_string($_POST['middlename'] ?? '');
    $lastname = $conn->real_escape_string($_POST['lastname'] ?? '');
    $username = $conn->real_escape_string($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $contact_number = $conn->real_escape_string($_POST['contact_number'] ?? '');
    $role = $conn->real_escape_string($_POST['role'] ?? '');
$specialization = $_POST['specialization'] ?? '';
    // Check required fields
    if(empty($firstname) || empty($lastname) || empty($username) || empty($password) || empty($contact_number) || empty($role)){
        echo "Please fill all required fields.";
        exit;
    }

    // Check if username exists (exclude current user if updating)
    $check_sql = "SELECT * FROM users WHERE username = '$username'";
    if($user_id){
        $check_sql .= " AND user_id != $user_id";
    }
    $check = $conn->query($check_sql)->num_rows;
    if($check > 0){
        echo "Username already exists. Please choose a different username";
        exit;
    }

    // Hash the password
    $password_hashed = $password;
$sepcialization = ", specialization = '$specialization' ";
    if($user_id){ // Update existing user
        $sql = "UPDATE users SET 
                    firstname = '$firstname',
                    middlename = '$middlename',
                    lastname = '$lastname',
                    username = '$username',
                    password = '$password_hashed',
                    contact_number = '$contact_number',
                    role = '$role' $sepcialization 
                WHERE user_id = $user_id";
        $msg = "Account successfully updated.";
    } else { // Insert new user
        $sql = "INSERT INTO users SET 
                    firstname = '$firstname',
                    middlename = '$middlename',
                    lastname = '$lastname',
                    username = '$username',
                    password = '$password_hashed',
                    contact_number = '$contact_number',
                    role = '$role' $sepcialization  ";

        $msg = "Account successfully created.";
    }

    if($conn->query($sql)){
        echo "1";
        exit;
    } else {
        echo "0";
        exit;
    }
}
?>
