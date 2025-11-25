<?php include 'connection.php'; ?>

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
    }

    .btn-add {
        background: #1a73e8;
        color: white;
        border-radius: 8px;
        padding: 8px 14px;
    }

    .btn-add:hover { background: #0f56b3; }

    .table thead {
        background: #f0f2f5;
    }

    .table thead th {
        font-weight: 600;
        color: #555;
    }

    .modal-content { border-radius: 14px; }

    label { font-weight: 500; }
</style>

<div class="content-box">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary m-0">Manage Alumni</h3>

        <button class="btn btn-add" id="btnAddStudent">+ New Alumni</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="studentTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Birthday</th>
                    <th>Course</th>
                    <th>Year Graduated</th>
                    <th>Mobile</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $students = $conn->query("
                    SELECT * FROM users u 
                    LEFT JOIN st_course c ON u.user_id = c.student_id 
                    WHERE user_type='alumni'
                    ORDER BY lastname ASC
                ");

                while ($row = $students->fetch_assoc()):
                ?>
                <tr>
                    <td class="s-id"><?= $row['user_id'] ?></td>

                    <td class="s-avatar">
                        <?php if ($row['avatar']): ?>
                            <img src="uploads/<?= $row['avatar'] ?>" width="40" height="40" style="border-radius:50%;">
                        <?php endif; ?>
                    </td>

                    <td class="s-firstname"><?= $row['firstname'] ?></td>
                    <td class="s-middlename"><?= $row['middlename'] ?></td>
                    <td class="s-lastname"><?= $row['lastname'] ?></td>
                    <td class="s-birthday"><?= $row['birthday'] ?></td>

                    <td class="s-course"><?= $row['course'] ?></td>
                    <td class="s-year_graduated"><?= $row['year_graduated'] ?></td>
                    <td class="s-contact"><?= $row['mobile'] ?></td>
                    <td class="s-username"><?= $row['username'] ?></td>
                    <td class="s-password"><?= $row['password'] ?></td>

                    <td>
                        <button class="btn btn-primary btn-sm btnEdit">Edit</button>
                        <a href="?page=manage_alumni_info&id=<?= $row['user_id'] ?>" class="btn btn-info btn-sm btnUpdate" >Update Profile</a>
                        <button class="btn btn-danger btn-sm btnDelete" data-id="<?= $row['user_id'] ?>">Delete</button>
                         
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- STUDENT MODAL -->
<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="studentForm" enctype="multipart/form-data">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTitle">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="user_id" name="user_id">

                      <input type="hidden" id="user_type" name="user_type" value="alumni">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label>First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Middle Name</label>
                            <input type="text" id="middlename" name="middlename" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Birthday</label>
                            <input type="date" id="birthday" name="birthday" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Course</label>
                            <select id="course" name="course" class="form-control">
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
                            <label>Year Graduated</label>
                            <input type="number" id="year_graduated" name="year_graduated" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Mobile</label>
                            <input type="text" id="mobile" name="mobile" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Username</label>
                            <input type="text" id="username" name="username" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Avatar</label>
                            <input type="file" id="avatar" name="avatar" class="form-control">
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="saveBtn" type="submit">Save Alumni</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>

// Initialize DataTable safely
$(document).ready(function(){
    $('#studentTable').DataTable({
        "pageLength": 10
    });
});

// Add Student
$("#btnAddStudent").on("click", function(){
    $("#studentForm")[0].reset();
    $("#modalTitle").text("Add New Student");
    $("#user_id").val("");
    new bootstrap.Modal(document.getElementById("studentModal")).show();
});

// Edit Student
$(".btnEdit").on("click", function(){
    let row = $(this).closest("tr");

    $("#modalTitle").text("Edit Student");

    $("#user_id").val(row.find(".s-id").text());
    $("#firstname").val(row.find(".s-firstname").text());
    $("#middlename").val(row.find(".s-middlename").text());
    $("#lastname").val(row.find(".s-lastname").text());
    $("#birthday").val(row.find(".s-birthday").text());
    $("#course").val(row.find(".s-course").text());
    $("#mobile").val(row.find(".s-contact").text());
    $("#username").val(row.find(".s-username").text());
    $("#password").val(row.find(".s-password").text());
 $("#year_graduated").val(row.find(".s-year_graduated").text());
    new bootstrap.Modal(document.getElementById("studentModal")).show();
});

// Submit form (SAMPLE ONLY – NO AJAX)
$("#studentForm").on("submit", function(e){
    e.preventDefault();

 $.ajax({
    url: 'query/save_user.php',
    type: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    success: function(response){
        
        alert('Student saved successfully!'+response);
        location.reload(); // Reload the page to see changes
    },
 })

    // Hide modal properly

});
$(".btnDelete").on("click", function(){
    let delete_id = $(this).data('id');
    if(confirm("Are you sure you want to delete this student?")){
        $.ajax({
            url: 'query/delete_user.php',
            type: 'POST',
            data: { delete_id: delete_id },
            success: function(response){
                alert('Student deleted successfully!');
                location.reload(); // Reload the page to see changes
            },
        });
    }
});

</script>
