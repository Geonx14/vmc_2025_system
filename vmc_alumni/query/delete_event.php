<?php
include '../connection.php';
$event_id = $_POST['event_id'] ?? null;
if($event_id){
    $conn->query("DELETE FROM events WHERE event_id = $event_id");
    echo "1";
} else {
    echo "0";
}
?>