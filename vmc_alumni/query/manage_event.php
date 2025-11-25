<?php
include '../connection.php';
extract($_POST);
$event_id = $_POST['event_id'] ?? null;
$event_date_start = $_POST['event_date_start'] ?? null;
$event_date_end = $_POST['event_date_end'] ?? null;
$event_title = $_POST['event_title'] ?? null;
$event_desc = $_POST['event_desc'] ?? null;
if($event_id){
    $conn->query("UPDATE events SET event_title = '$event_title', event_desc = '$event_desc', event_date_start = '$event_date_start', event_date_end = '$event_date_end' WHERE event_id = $event_id");
echo "2";
}else{
    $conn->query("INSERT INTO events (event_title, event_desc, event_date_start, event_date_end) VALUES ('$event_title', '$event_desc', '$event_date_start', '$event_date_end')");
    echo "1";
}
?>