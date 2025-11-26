<?php
include 'connection.php';
$id = $_SESSION['user_id'];
// Fetch user info
$alumni_info = $conn->query("SELECT * FROM users u left join st_course st on u.user_id=st.student_id WHERE user_id='$id'")->fetch_assoc();
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

.btn-update {
    background: #1a73e8;
    color: white;
    border-radius: 8px;
    padding: 10px 20px;
}

.btn-update:hover {
    background: #0f56b3;
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

<div class="content-box">
    <h3 class="fw-bold text-primary mb-4">Manage <?=  ucfirst($_SESSION['role'])?> Information</h3>

    <!-- Personal Info -->
    <div class="mb-4">
        <h5 class="section-title">Personal Information</h5>
        <div class="row g-3 mt-2 text-center mb-3">
            <div class="col-md-12">
              
                <img src="<?= "query/uploads/avatars/".$alumni_info['avatar'] ?>" style="height: 200px; width: 200px; border-radius: 100px;" alt="">
            </div>
        </div>
        <?php
        if($_SESSION['role']=='student' ||$_SESSION['role']=='alumni' ):
        ?>
<div class="row">
     <div class="col-md-4">
                <label>Course</label>
                <input type="text" class="form-control" value="<?= $alumni_info['course'] ?>" readonly>
            </div>
</div>
<?php endif;?>
        <div class="row g-3 mt-2">
            
            <div class="col-md-4">
                <label>First Name</label>
                <input type="text" class="form-control" value="<?= $alumni_info['firstname'] ?>" readonly>
            </div>

            <div class="col-md-4">
                <label>Middle Name</label>
                <input type="text" class="form-control" value="<?= $alumni_info['middlename'] ?>" readonly>
            </div>

            <div class="col-md-4">
                <label>Last Name</label>
                <input type="text" class="form-control" value="<?= $alumni_info['lastname'] ?>" readonly>
            </div>

            <div class="col-md-6">
                <label>Birthday</label>
                <input type="date" class="form-control" value="<?= $alumni_info['birthday'] ?>" readonly>
            </div>

            <div class="col-md-6">
                <label>Contact Number</label>
                <input type="text" class="form-control" value="<?= $alumni_info['mobile'] ?>" readonly>
            </div>

            <div class="col-md-6">
                <label>Username</label>
                <input type="text" class="form-control" value="<?= $alumni_info['username'] ?>" readonly>
            </div>

            <div class="col-md-6">
                <label>Password</label>
                <input type="password" class="form-control" value="<?= $alumni_info['password'] ?>" readonly>
            </div>
        </div>

<div class="text-end mt-4">
            <button class="btn btn-update" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                <i class="bi bi-pencil-square"></i> Update Profile
            </button>
        </div>
    </div>
</div>

<!-- UPDATE PROFILE MODAL -->
<div class="modal fade" id="updateProfileModal">
    <div class="modal-dialog modal-lg">
        <form id="frm_update" class="modal-content">
            <input type="hidden" name="user_id" value="<?= $id ?>">
            <input type="hidden" name="user_type" value="<?= $_SESSION['role'] ?>">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Update Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" name="firstname" class="form-control" value="<?= $alumni_info['firstname'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middlename" class="form-control" value="<?= $alumni_info['middlename'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lastname" class="form-control" value="<?= $alumni_info['lastname'] ?>" required>
                    </div>
                </div>
                    <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Birthday</label>
                        <input type="date" name="birthday" class="form-control" value="<?= $alumni_info['birthday'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="mobile" class="form-control" value="<?= $alumni_info['mobile'] ?>" required>
                    </div>
                </div>
                <?php

                $is_disabled = "";
                   if($_SESSION['role']=='admin' || $_SESSION['role']=='staff' ){
     $is_disabled = "disabled";
                   }
    
                ?>

                <div class="row mb-3">
                  <div class="col-md-6">
                            <label>Course</label>
                            <select id="course"  <?= $is_disabled  ?> name="course" class="form-control">
                                  <option value="<?=  isset($alumni_info['course'])?$alumni_info['course']:"" ?>"><?=  isset($alumni_info['course'])?$alumni_info['course']:"" ?></option>
                                <option value="" disabled>Select Course</option>
                                <optgroup label="Information Technology & Computing">
                                    <option value="BSIT">BS Information Technology (BSIT)</option>
                                    <option value="BSCS">BS Computer Science (BSCS)</option>
                                    <option value="BSCpE">BS Computer Engineering (BSCpE)</option>
                                    <option value="BSIS">BS Information Systems (BSIS)</option>
                                </optgroup>

                                <optgroup label="Business & Management">
                                    <option value="BSBA">BS Business Administration (BSBA)</option>
                                    <option value="BSA">BS Accountancy (BSA)</option>
                                    <option value="BSMA">BS Management Accounting (BSMA)</option>
                                    <option value="BSTM">BS Tourism Management (BSTM)</option>
                                    <option value="BHM">BS Hospitality Management (BHM)</option>
                                </optgroup>

                                <optgroup label="Education">
                                    <option value="BEEd">Bachelor of Elementary Education (BEEd)</option>
                                    <option value="BSEd-English">BSEd Major in English</option>
                                    <option value="BSEd-Math">BSEd Major in Mathematics</option>
                                    <option value="BPEd">Bachelor of Physical Education (BPEd)</option>
                                </optgroup>

                                <optgroup label="Engineering">
                                    <option value="BSCE">BS Civil Engineering (BSCE)</option>
                                    <option value="BSEE">BS Electrical Engineering (BSEE)</option>
                                    <option value="BSME">BS Mechanical Engineering (BSME)</option>
                                    <option value="BSECE">BS Electronics Engineering (BSECE)</option>
                                </optgroup>

                                <optgroup label="Health & Allied Programs">
                                    <option value="BSN">BS Nursing (BSN)</option>
                                    <option value="BSP">BS Pharmacy (BSP)</option>
                                    <option value="BSMT">BS Medical Technology (BSMT)</option>
                                    <option value="BSPsych">BS Psychology (BS Psych)</option>
                                </optgroup>

                                <optgroup label="Public Safety">
                                    <option value="BSCrim">BS Criminology (BSCrim)</option>
                                </optgroup>

                                <optgroup label="Arts & Sciences">
                                    <option value="BSPA">BS Public Administration</option>
                                    <option value="ABComm">AB Communication</option>
                                    <option value="ABPolSci">AB Political Science</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Avatar</label>
                            <input type="file" id="avatar" name="avatar" class="form-control">
                        </div>
                        </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $alumni_info['username'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" value="<?= $alumni_info['username'] ?>" required>
                    </div>                    
                </div>

              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-update">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<script>
$("#frm_update").on("submit", function(e){
    e.preventDefault();

 $.ajax({
    url: 'query/save_user.php',
    type: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    success: function(response){        
        alert('Profile saved successfully!'+response);
        location.reload(); // Reload the page to see changes
    },
 })

    // Hide modal properly

});
</script>