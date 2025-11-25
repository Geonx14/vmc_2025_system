<?php  
include '../connection.php';
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanitize input and extract variables
     $appointment_id = $_POST['appointment_id'] ?? null; // Only for updates
    $doctor_id = $_POST['doctor_id'] ?? null; // Only for updates
    $schedule_date = $conn->real_escape_string($_POST['schedule_date'] ?? '');
    $schedule_time = $conn->real_escape_string($_POST['schedule_time'] ?? '');
    $patient_id = $_SESSION['user_id'] ?? null;
    $status = "pending";
if(isset($appointment_id) && !empty($appointment_id)){
    // Update existing appointment
    $sql = "UPDATE appointments SET doctor_id = '$doctor_id', schedule_date = '$schedule_date', schedule_time = '$schedule_time', status = '$status' WHERE appointment_id = '$appointment_id'";
    if($conn->query($sql)){
        echo "1";
    } else {
        echo "0";
    }
} else {   
   
$sql = "INSERT INTO appointments (doctor_id, patient_id, schedule_date, schedule_time, status) VALUES ('$doctor_id', '$patient_id', '$schedule_date', '$schedule_time', '$status')";
if($conn->query($sql)){
        echo "1";
    } else {
        echo "0";
    
    }
}
}
?>
