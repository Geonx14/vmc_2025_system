<?php
include '../connection.php';

// Get POST values safely
$career_id = isset($_POST['career_id']) ? intval($_POST['career_id']) : 0;
$alumni_id = isset($_POST['alumni_id']) ? intval($_POST['alumni_id']) : 0;
$companyName = isset($_POST['company_name']) ? $_POST['company_name'] : '';
$positionTitle = isset($_POST['position_title']) ? $_POST['position_title'] : '';
$startYear = isset($_POST['start_year']) ? $_POST['start_year'] : '';
$endYear = isset($_POST['end_year']) ? $_POST['end_year'] : '';

// Update existing career path
if($career_id > 0){
    $stmt = $conn->prepare("UPDATE alumni_career_paths 
                            SET company_name=?, position_title=?, start_year=?, end_year=? 
                            WHERE career_id=? AND alumni_id=?");
    $stmt->bind_param("ssssii", $companyName, $positionTitle, $startYear, $endYear, $career_id, $alumni_id);
    $stmt->execute();
    echo "2".$startYear." ".$endYear;
    $stmt->close();
} 
// Insert new career path
else {
    $stmt = $conn->prepare("INSERT INTO alumni_career_paths (alumni_id, company_name, position_title, start_year, end_year) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $alumni_id, $companyName, $positionTitle, $startYear, $endYear);
    $stmt->execute();
    $new_id = $stmt->insert_id; // Get the new career_id
        echo "1";
    $stmt->close();
}

$conn->close();
?>
