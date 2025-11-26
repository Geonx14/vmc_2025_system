<?php
include 'connection.php';

$alumni_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];
$alumni_info = $conn->query("SELECT * FROM users WHERE user_id='$alumni_id'")->fetch_assoc();
?>
<style>
body { background: #f5f6fa; font-family: "Segoe UI", sans-serif; padding: 20px; }
.content-box { background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); max-width: 1000px; margin: auto; }
.section-title { font-weight: 600; font-size: 1.2rem; color: #1a73e8; margin-bottom: 15px; border-bottom: 1px solid #e0e0e0; padding-bottom: 5px; }
label { font-weight: 500; }
.form-control[readonly] { background-color: #f8f9fa; border: 1px solid #ddd; }
.btn-save, .btn-row-save { background: #1a73e8; color: white; border-radius: 8px; padding: 10px 20px; }
.btn-save:hover, .btn-row-save:hover { background: #0f56b3; }
.btn-delete, .btnRemoveRow { background: #dc3545; color: white; border-radius: 8px; }
.btn-delete:hover, .btnRemoveRow:hover { background: #b02a37; }
.btn-add-row { margin-top: 10px; }
.card { margin-bottom: 15px; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.05); }
.card-body { padding: 15px; }
</style>

<div class="content-box">
    <h3 class="fw-bold text-primary mb-4">Manage Alumni Information</h3>

<?php
if($_SESSION['role'] == "admin"):
?>
    <div class="mb-4">
        <h5 class="section-title">Personal Information</h5>
        <div class="row g-3 mt-2 text-center">
            <div class="col-md-12">
                <img src="../query/uploads/avatars/<?= $alumni_info['avatar'] ?>" 
                     style="width:120px; height:120px; border-radius:50%; object-fit:cover;">
            </div>
            <div class="col-md-4"><label>First Name</label><input type="text" class="form-control" value="<?= $alumni_info['firstname'] ?>" readonly></div>
            <div class="col-md-4"><label>Middle Name</label><input type="text" class="form-control" value="<?= $alumni_info['middlename'] ?>" readonly></div>
            <div class="col-md-4"><label>Last Name</label><input type="text" class="form-control" value="<?= $alumni_info['lastname'] ?>" readonly></div>
            <div class="col-md-6"><label>Birthday</label><input type="date" class="form-control" value="<?= $alumni_info['birthday'] ?>" readonly></div>
            <div class="col-md-6"><label>Contact Number</label><input type="text" class="form-control" value="<?= $alumni_info['mobile'] ?>" readonly></div>
            <div class="col-md-6"><label>Username</label><input type="text" class="form-control" value="<?= $alumni_info['username'] ?>" readonly></div>
            <div class="col-md-6"><label>Password</label><input type="password" class="form-control" value="<?= $alumni_info['password'] ?>" readonly></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Career Paths -->
    <div class="mb-4">
        <h5 class="section-title">Career Paths</h5>
        <div id="careerPathsContainer">
            <?php
            $list_career = $conn->query("SELECT * FROM alumni_career_paths WHERE alumni_id='$alumni_id'");
            while($row = $list_career->fetch_assoc()):
            ?>
            <div class="card" data-id="<?= $row['career_id'] ?>">
                <div class="card-body">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4"><label>Company Name</label><input type="text" class="form-control company_name" value="<?= $row['company_name'] ?>"></div>
                        <div class="col-md-4"><label>Position Title</label><input type="text" class="form-control position_title" value="<?= $row['position_title'] ?>"></div>
                        <div class="col-md-2"><label>Start Year</label><input type="number" class="form-control start_year" value="<?= $row['start_year'] ?>"></div>
                        <div class="col-md-2"><label>End Year</label><input type="number" class="form-control end_year" value="<?= $row['end_year'] ?>"></div>
                        <div class="col-12 text-end mt-2">
                            <button class="btn btn-row-save btn-sm">Save</button>
                            <button class="btn btn-delete btn-sm btnRemoveRow btn-primary"  data-table="alumni_career_paths" data-column="career_id" data-id="<?=  $row['career_id'] ?>">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <button class="btn btn-sm btn-primary btn-add-row" id="addCareerPath">Add Career Path</button>
    </div>

    <!-- Achievements -->
    <div class="mb-4">
        <h5 class="section-title">Achievements</h5>
        <div id="achievementsContainer">
            <?php
            $list_achievements = $conn->query("SELECT * FROM alumni_achievements WHERE alumni_id='$alumni_id'");
            while($row = $list_achievements->fetch_assoc()):
            ?>
            <div class="card" data-id="<?= $row['achievement_id'] ?>">
                <div class="card-body">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4"><label>Title</label><input type="text" class="form-control achievement_title" value="<?= $row['achievement_title'] ?>"></div>
                        <div class="col-md-6"><label>Description</label><textarea class="form-control achievement_desc"><?= $row['achievement_desc'] ?></textarea></div>
                        <div class="col-md-2"><label>Year</label><input type="number" class="form-control achievement_year" value="<?= $row['year'] ?>"></div>
                        <div class="col-12 text-end mt-2">
                            <button class="btn btn-row-saveAchievement btn-sm  btn-primary">Save</button>
                            <button class="btn btn-delete btn-sm btnRemoveRow" data-table="alumni_achievements" data-column="achievement_id" data-id="<?=  $row['achievement_id'] ?>">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <button class="btn btn-sm btn-primary btn-add-row" id="addAchievement">Add Achievement</button>
    </div>

    <!-- Contributions -->
    <div class="mb-4">
        <h5 class="section-title">Contributions</h5>
        <div id="contributionsContainer">
            <?php
            $list_contributions = $conn->query("SELECT * FROM alumni_contributions WHERE alumni_id='$alumni_id'");
            while($row = $list_contributions->fetch_assoc()):
            ?>
            <div class="card" data-id="<?= $row['contribution_id'] ?>">
                <div class="card-body">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4"><label>Type</label><input type="text" class="form-control contribution_type" value="<?= $row['contribution_type'] ?>"></div>
                        <div class="col-md-6"><label>Description</label><textarea class="form-control contribution_desc"><?= $row['contribution_desc'] ?></textarea></div>
                        <div class="col-md-2"><label>Date</label><input type="date" class="form-control date_contributed" value="<?= $row['date_contributed'] ?>"></div>
                        <div class="col-12 text-end mt-2">
                            <button class="btn btn-row-saveContribution btn-sm btn-primary">Save</button>
                            <button class="btn btn-delete btn-sm btnRemoveRow " data-table="alumni_contributions" data-column="contribution_id" data-id="<?=  $row['contribution_id'] ?>" >Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <button class="btn btn-sm btn-primary btn-add-row" id="addContribution">Add Contribution</button>
    </div>

    <div class="text-end">
        <button class="btn btn-save" id="btnSaveAlumni">Save All</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let alumniid = "<?= $alumni_id ?>";

// Add rows
$("#addCareerPath").click(()=> $("#careerPathsContainer").append(createCareerPathRow()));
$("#addAchievement").click(()=> $("#achievementsContainer").append(createAchievementRow()));
$("#addContribution").click(()=> $("#contributionsContainer").append(createContributionRow()));

// Remove row
$(document).on("click", ".btnRemoveRow", function(){     
    $(this).closest(".card").remove();
let table = $(this).data('table');
let column = $(this).data('column');
let id = $(this).data('id');
$.post("query/delete_type.php", {
        table:table,
        column: column,
      id: id
    }, function(response){

        alert("Data has been deleted!");
    });
});

// Save career row
$(document).on("click", ".btn-row-save", function(){
    let card = $(this).closest(".card");
    $.post("query/manage_career.php", {
        career_id: card.data("id"),
        alumni_id: alumniid,
        company_name: card.find(".company_name").val(),
        position_title: card.find(".position_title").val(),
        start_year: card.find(".start_year").val(),
        end_year: card.find(".end_year").val()
    }, function(response){
        alert("Career saved! Response: " + response);
    });
});

// Save achievement row
$(document).on("click", ".btn-row-saveAchievement", function(){
    let card = $(this).closest(".card");

    $.post("query/manage_achievements.php", {
        achievement_id: card.data("id"),
        alumni_id: alumniid,
        title: card.find(".achievement_title").val(),
        description: card.find(".achievement_desc").val(),
        year: card.find(".achievement_year").val()
    }, function(response){
        alert("Achievement saved! Response: " + response);
    });
});


$(document).on("click", ".btn-row-saveContribution", function(){
    let card = $(this).closest(".card");
    $.post("query/manage_contribution.php", {
        contribution_id: card.data("id"),
        alumni_id: alumniid,
        type: card.find(".contribution_type").val(),
        description: card.find(".contribution_desc").val(),
        date_contributed: card.find(".date_contributed").val()
    }, function(response){
        alert("Contribution saved! Response: " + response);
    });
});

// Save contribution row


// Save all button
$("#btnSaveAlumni").click(function(){
    alert("All alumni info saved successfully!");
});

// Row creation functions
function createCareerPathRow(data = {}) { let id = Date.now(); return `<div class="card" ><div class="card-body"><div class="row g-2 align-items-end"><div class="col-md-4"><label>Company Name</label><input type="text" class="form-control company_name" value="${data.company_name || ''}"></div><div class="col-md-4"><label>Position Title</label><input type="text" class="form-control position_title" value="${data.position_title || ''}"></div><div class="col-md-2"><label>Start Year</label><input type="number" class="form-control start_year" value="${data.start_year || ''}"></div><div class="col-md-2"><label>End Year</label><input type="number" class="form-control end_year" value="${data.end_year || ''}"></div><div class="col-12 text-end mt-2"><button class="btn btn-row-save btn-sm  btn-primary">Save</button><button class="btn btn-delete btn-sm btnRemoveRow">Delete</button></div></div></div></div>`;}
function createAchievementRow(data = {}) { let id = Date.now(); return `<div class="card" ><div class="card-body"><div class="row g-2 align-items-end"><div class="col-md-4"><label>Title</label><input type="text" class="form-control achievement_title" value="${data.title || ''}"></div><div class="col-md-6"><label>Description</label><textarea class="form-control achievement_desc">${data.description || ''}</textarea></div><div class="col-md-2"><label>Year</label><input type="number" class="form-control achievement_year" value="${data.year || ''}"></div><div class="col-12 text-end mt-2"><button class="btn btn-row-saveAchievement btn-sm  btn-primary">Save</button><button class="btn btn-delete btn-sm btnRemoveRow">Delete</button></div></div></div></div>`;}
function createContributionRow(data = {}) { let id = Date.now(); return `<div class="card" ><div class="card-body"><div class="row g-2 align-items-end"><div class="col-md-4"><label>Type</label><input type="text" class="form-control contribution_type" value="${data.type || ''}"></div><div class="col-md-6"><label>Description</label><textarea class="form-control contribution_desc">${data.description || ''}</textarea></div><div class="col-md-2"><label>Date</label><input type="date" class="form-control date_contributed" value="${data.date_contributed || ''}"></div><div class="col-12 text-end mt-2"><button class="btn btn-row-saveContribution btn-sm  btn-primary">Save</button><button class="btn btn-delete btn-sm btnRemoveRow">Delete</button></div></div></div></div>`;}
</script>
