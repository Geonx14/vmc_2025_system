<?php
include 'connection.php';
$id = $_SESSION['user_id'];
// Fetch user info
$alumni_info = $conn->query("SELECT * FROM users WHERE user_id='$id'")->fetch_assoc();
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
    <h3 class="fw-bold text-primary mb-4">Manage Alumni Information</h3>

    <!-- Personal Info -->
    <div class="mb-4">
        <h5 class="section-title">Personal Information</h5>
        <div class="row g-3 mt-2 text-center mb-3">
            <div class="col-md-12">
                <img src="../query/uploads/avatars/<?= $alumni_info['avatar'] ?>" 
                     style="width:120px; height:120px; border-radius:50%; object-fit:cover;">
            </div>
        </div>

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

        <!-- Update Profile Button for Student & Staff -->
        <?php if(in_array($_SESSION['role'], ['student','staff'])): ?>
        <div class="text-end mt-4">
            <button class="btn btn-update" data-bs-toggle="modal" data-bs-target="#updateProfileModal">
                <i class="bi bi-pencil-square"></i> Update Profile
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- UPDATE PROFILE MODAL -->
<div class="modal fade" id="updateProfileModal">
    <div class="modal-dialog modal-lg">
        <form action="query/update_profile.php" method="POST" class="modal-content">
            <input type="hidden" name="user_id" value="<?= $id ?>">
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
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $alumni_info['username'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="mobile" class="form-control" value="<?= $alumni_info['mobile'] ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Birthday</label>
                        <input type="date" name="birthday" class="form-control" value="<?= $alumni_info['birthday'] ?>" required>
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
