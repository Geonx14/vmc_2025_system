<?php
include '../connection.php';

// Get POST values
$alumni_id = isset($_POST['alumni_id']) ? $_POST['alumni_id'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$year = isset($_POST['year']) ? $_POST['year'] : '';
$achievement_id = isset($_POST['achievement_id']) ? $_POST['achievement_id'] : '';

// Update existing achievement
if(!empty($achievement_id)){
    $sql = "UPDATE `alumni_achievements` 
            SET `alumni_id`='$alumni_id', 
                `achievement_title`='$title', 
                `achievement_desc`='$description', 
                `year`='$year' 
            WHERE achievement_id='$achievement_id'";
    

    if($conn->query($sql) === TRUE){
        echo "2".$sq;
    } else {
       echo "0";
    }
} 
// Insert new achievement
else {
    $sql = "INSERT INTO `alumni_achievements` (`alumni_id`,`achievement_title`,`achievement_desc`,`year`) 
            VALUES ('$alumni_id','$title','$description','$year')";
    
    if($conn->query($sql) === TRUE){
      
        echo "1";
    } else {
        echo "0";
    }
}

$conn->close();
?>
