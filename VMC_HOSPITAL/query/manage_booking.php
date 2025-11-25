<?php
include '../connection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $appointment_id = $_POST['appointment_id'] ?? '0';
    $datetime_updated = date('Y-m-d H:i:s');
    $status = $_POST['status'] ?? '';
    $professional_fee = $_POST['professional_fee'] ?? "0";
    $decline_reason = $_POST['decline_reason'] ?? "-";


if($status == 'completed'){
     $sql = "update `appointments` set status = '$status' WHERE appointment_id=$appointment_id";
}
else{
 $sql = "update `appointments` set status = '$status', updated_at='$datetime_updated', professional_fee='$professional_fee', rejection_reason='$decline_reason'
         WHERE appointment_id=$appointment_id";
}
  
         
    if($conn->query($sql)){
        echo "1";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>