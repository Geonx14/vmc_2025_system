<?php
include '../connection.php';

$table = $_POST['table'];
$column = $_POST['column'];
$value = $_POST['id'];
$sql = "delete from $table where $column = $value";
$conn->query($sql);
echo $sql;

?>