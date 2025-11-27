<?php
include '../connection.php';

$user_id = $_POST['user_id'];
$event_id = $_POST['event_id'];
$status = $_POST['status'];
$datetime = date('Y-m-d H:i:s');
$is_exist = $conn->query("
    SELECT * FROM event_participants 
    WHERE event_id = '$event_id' 
      AND user_id = '$user_id'
");

if ($is_exist->num_rows > 0) {

    $conn->query("
        delete from event_participants        
        WHERE event_id = '$event_id' 
          AND user_id = '$user_id'
    ");

    echo "2";

} else {

    $conn->query("
        INSERT INTO event_participants (event_id, user_id, status,date_participated) 
        VALUES ('$event_id', '$user_id', '$status','$datetime')
    ");

    echo "1";
}
?>
