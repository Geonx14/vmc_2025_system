<?php
include '../connection.php';

// Get POST values
$contribution_id = isset($_POST['contribution_id']) ? $_POST['contribution_id'] : '';
$alumni_id = isset($_POST['alumni_id']) ? intval($_POST['alumni_id']) : 0;
$type = isset($_POST['type']) ? $_POST['type'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$date_contributed = isset($_POST['date_contributed']) ? $_POST['date_contributed'] : '';


// Update existing achievement
if(!empty($contribution_id)){
    $sql = "UPDATE `alumni_contributions` 
            SET `alumni_id`='$alumni_id', 
                `contribution_type`='$type', 
                `contribution_desc`='$description', 
                `date_contributed`='$date_contributed' 
            WHERE contribution_id='$contribution_id'";
    

    if($conn->query($sql) === TRUE){
        echo "2".$sql;
    } else {
       echo "0";
    }
} 
// Insert new achievement
else {
    $sql = "INSERT INTO `alumni_contributions`(`alumni_id`, `contribution_type`, `contribution_desc`, `date_contributed`) 
            VALUES ('$alumni_id','$type','$description','$date_contributed')";
    
    if($conn->query($sql) === TRUE){
      
        echo "1";
    } else {
        echo "0";
    }
}

$conn->close();
?>
