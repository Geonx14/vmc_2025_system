<?php
include '../connection.php';

$user_id  = $_POST['user_id'];
$event_id = $_POST['event_id'];
$status   = $_POST['status'];

$is_exist = $conn->query("SELECT * FROM event_participants WHERE event_id = $event_id AND user_id = $user_id");

if ($is_exist->num_rows > 0) {

    // Update existing participant
    $conn->query("UPDATE event_participants SET status = '$status' WHERE event_id = $event_id AND user_id = $user_id");
    echo "2"; // updated

} else {

    // FIXED: parameters were reversed before
    $conn->query("INSERT INTO event_participants (event_id, user_id, status) VALUES ($event_id, $user_id, '$status')");
    echo "1"; // inserted
}
?>
