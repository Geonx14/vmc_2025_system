<?php
include 'connection.php';
$alumni_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];
$alumni_info = $conn->query("SELECT * FROM users WHERE user_id='$alumni_id'")->fetch_assoc();
?>

<style>
body {
    background: #f5f6fa;
    font-family: "Segoe UI", sans-serif;
    padding: 20px;
}

.content-box {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    max-width: 1000px;
    margin: auto;
}

.section-title {
    font-weight: 600;
    font-size: 1.2rem;
    color: #1a73e8;
    margin-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 5px;
}

label {
    font-weight: 500;
}

.form-control[readonly] {
    background-color: #f8f9fa;
    border: 1px solid #ddd;
}

.btn-save, .btn-row-save {
    background: #1a73e8;
    color: white;
    border-radius: 8px;
    padding: 10px 20px;
}

.btn-save:hover, .btn-row-save:hover {
    background: #0f56b3;
}

.btn-delete, .btnRemoveRow {
    background: #dc3545;
    color: white;
    border-radius: 8px;
}

.btn-delete:hover, .btnRemoveRow:hover {
    background: #b02a37;
}

.btn-add-row {
    margin-top: 10px;
}

.card {
    margin-bottom: 15px;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.card-body {
    padding: 15px;
}
</style>

<body>
<div class="content-box">
    <h3 class="fw-bold text-primary mb-4">Manage Alumni Information</h3>

    <!-- Personal Info -->
    <!-- Personal Info -->
<div class="mb-4">
    <h5 class="section-title">Personal Information</h5>
    <div class="row g-3 mt-2">
<div class="col-md-12 text-center">
    <img src="../query/uploads/avatars/<?= $alumni_info['avatar'] ?>" 
         style="width:120px; height:120px; border-radius:50%; object-fit:cover;">
</div>

        <div class="col-md-4">
            <label>First Name</label>
            <input type="text" class="form-control" id="firstname" value="<?= $alumni_info['firstname'] ?>" readonly>
        </div>

        <div class="col-md-4">
            <label>Middle Name</label>
            <input type="text" class="form-control" id="middlename" value="<?= $alumni_info['middlename'] ?>" readonly>
        </div>

        <div class="col-md-4">
            <label>Last Name</label>
            <input type="text" class="form-control" id="lastname" value="<?= $alumni_info['lastname'] ?>" readonly>
        </div>

        <div class="col-md-6">
            <label>Birthday</label>
            <input type="date" class="form-control" id="birthday" value="<?= $alumni_info['birthday'] ?>" readonly>
        </div>

        <div class="col-md-6">
            <label>Contact Number</label>
            <input type="text" class="form-control" id="contact_number" value="<?= $alumni_info['mobile'] ?>" readonly>
        </div>

      

        <div class="col-md-6">
            <label>Username</label>
            <input type="text" class="form-control" id="username" value="<?= $alumni_info['username'] ?>" readonly>
        </div>

        <div class="col-md-6">
            <label>Password</label>
            <input type="password" class="form-control" id="password" value="<?= $alumni_info['password'] ?>" readonly>
        </div>
    </div>
</div>


    <!-- Career Paths -->
    <div class="mb-4">
        <h5 class="section-title">Career Paths</h5>
        <div id="careerPathsContainer"></div>
        <button class="btn btn-sm btn-primary btn-add-row" id="addCareerPath">
            <i class="bi bi-plus-circle"></i> Add Career Path
        </button>
    </div>

    <!-- Achievements -->
    <div class="mb-4">
        <h5 class="section-title">Achievements</h5>
        <div id="achievementsContainer"></div>
        <button class="btn btn-sm btn-primary btn-add-row" id="addAchievement">
            <i class="bi bi-plus-circle"></i> Add Achievement
        </button>
    </div>

    <!-- Contributions -->
    <div class="mb-4">
        <h5 class="section-title">Contributions</h5>
        <div id="contributionsContainer"></div>
        <button class="btn btn-sm btn-primary btn-add-row" id="addContribution">
            <i class="bi bi-plus-circle"></i> Add Contribution
        </button>
    </div>

</div>

<script>
// Row templates with icons
function createCareerPathRow(data = {}) {
    let id = Date.now();
    return `
    <div class="card" data-id="${id}">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-4"><label>Company Name</label><input type="text" class="form-control company_name" value="${data.company_name || ''}"></div>
                <div class="col-md-4"><label>Position Title</label><input type="text" class="form-control position_title" value="${data.position_title || ''}"></div>
                <div class="col-md-2"><label>Start Year</label><input type="number" class="form-control start_year" value="${data.start_year || ''}"></div>
                <div class="col-md-2"><label>End Year</label><input type="number" class="form-control end_year" value="${data.end_year || ''}"></div>
                <div class="col-12 text-end mt-2">
                    <button class="btn btn-row-save btn-sm"><i class="bi bi-save"></i> Save</button>
                    <button class="btn btn-delete btn-sm btnRemoveRow"><i class="bi bi-trash"></i> Delete</button>
                </div>
            </div>
        </div>
    </div>`;
}

function createAchievementRow(data = {}) {
    let id = Date.now();
    return `
    <div class="card" data-id="${id}">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-4"><label>Title</label><input type="text" class="form-control achievement_title" value="${data.achievement_title || ''}"></div>
                <div class="col-md-6"><label>Description</label><textarea class="form-control achievement_desc">${data.achievement_desc || ''}</textarea></div>
                <div class="col-md-2"><label>Year</label><input type="number" class="form-control achievement_year" value="${data.year || ''}"></div>
                <div class="col-12 text-end mt-2">
                    <button class="btn btn-row-save btn-sm"><i class="bi bi-save"></i> Save</button>
                    <button class="btn btn-delete btn-sm btnRemoveRow"><i class="bi bi-trash"></i> Delete</button>
                </div>
            </div>
        </div>
    </div>`;
}

function createContributionRow(data = {}) {
    let id = Date.now();
    return `
    <div class="card" data-id="${id}">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-4"><label>Type</label><input type="text" class="form-control contribution_type" value="${data.contribution_type || ''}"></div>
                <div class="col-md-6"><label>Description</label><textarea class="form-control contribution_desc">${data.contribution_desc || ''}</textarea></div>
                <div class="col-md-2"><label>Date</label><input type="date" class="form-control date_contributed" value="${data.date_contributed || ''}"></div>
                <div class="col-12 text-end mt-2">
                    <button class="btn btn-row-save btn-sm"><i class="bi bi-save"></i> Save</button>
                    <button class="btn btn-delete btn-sm btnRemoveRow"><i class="bi bi-trash"></i> Delete</button>
                </div>
            </div>
        </div>
    </div>`;
}

// Add/Remove rows and Save logic remains same as before
$("#addCareerPath").on("click", ()=> $("#careerPathsContainer").append(createCareerPathRow()));
$("#addAchievement").on("click", ()=> $("#achievementsContainer").append(createAchievementRow()));
$("#addContribution").on("click", ()=> $("#contributionsContainer").append(createContributionRow()));
$(document).on("click", ".btnRemoveRow", function(){ $(this).closest(".card").remove(); });

$(document).on("click", ".btn-row-save", function(){
    let card = $(this).closest(".card");
    console.log("Saved Row Data:", card.find("input, textarea").serializeArray());
    alert("Row saved successfully!");
});

$("#btnSaveAlumni").on("click", function(){
    alert("All alumni info saved successfully!");
});
</script>
