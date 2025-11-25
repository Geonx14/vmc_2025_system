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
        <h3 class="fw-bold text-primary m-0">Manage Staff</h3>

        <button class="btn btn-add" id="btnAddStaff">+ New Staff</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="staffTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Birthday</th>
                    <th>Mobile</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $staff = $conn->query("
                    SELECT * FROM users 
                    WHERE user_type='staff'
                    ORDER BY lastname ASC
                ");

                while ($row = $staff->fetch_assoc()):
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
                    <td class="s-contact"><?= $row['mobile'] ?></td>
                    <td class="s-username"><?= $row['username'] ?></td>
                    <td class="s-password"><?= $row['password'] ?></td>

                    <td>
                        <button class="btn btn-primary btn-sm btnEdit">Edit</button>
                        <button class="btn btn-danger btn-sm btnDelete" data-id="<?= $row['user_id'] ?>">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- STAFF MODAL -->
<div class="modal fade" id="staffModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="staffForm" enctype="multipart/form-data">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTitle">Add New Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="user_id" name="user_id">
                    <input type="hidden" id="user_type" name="user_type" value="staff">

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
                    <button class="btn btn-primary" id="saveBtn" type="submit">Save Staff</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>

// Initialize DataTable
$(document).ready(function(){
    $('#staffTable').DataTable({
        "pageLength": 10
    });
});

// Add Staff
$("#btnAddStaff").on("click", function(){
    $("#staffForm")[0].reset();
    $("#modalTitle").text("Add New Staff");
    $("#user_id").val("");
    new bootstrap.Modal(document.getElementById("staffModal")).show();
});

// Edit Staff
$(".btnEdit").on("click", function(){
    let row = $(this).closest("tr");

    $("#modalTitle").text("Edit Staff");

    $("#user_id").val(row.find(".s-id").text());
    $("#firstname").val(row.find(".s-firstname").text());
    $("#middlename").val(row.find(".s-middlename").text());
    $("#lastname").val(row.find(".s-lastname").text());
    $("#birthday").val(row.find(".s-birthday").text());
    $("#mobile").val(row.find(".s-contact").text());
    $("#username").val(row.find(".s-username").text());
    $("#password").val(row.find(".s-password").text());

    new bootstrap.Modal(document.getElementById("staffModal")).show();
});

// SAVE (AJAX WITH FILE UPLOAD)
$("#staffForm").on("submit", function(e){
    e.preventDefault();

    $.ajax({
        url: 'query/save_user.php',
        type: 'POST',
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function(response){

            alert("Staff saved successfully!");

            location.reload();
        }
    });
});

// DELETE STAFF
$(".btnDelete").on("click", function(){
    let delete_id = $(this).data('id');

    if(confirm("Are you sure you want to delete this staff?")){
        $.ajax({
            url: 'query/delete_user.php',
            type: 'POST',
            data: { delete_id: delete_id },
            success: function(response){
                alert('Staff deleted successfully!');
                location.reload();
            },
        });
    }
});

</script>
