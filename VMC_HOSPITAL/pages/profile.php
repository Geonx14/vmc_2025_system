<?php
include 'connection.php';
$user = $conn->query("SELECT * FROM users WHERE user_id = ".$_SESSION['user_id'])->fetch_assoc();

?>
<div class="container-fluid py-4">
    <h3 class="mb-4">My Profile</h3>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">Profile Information</h5>
                <form id="frm_profile">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?? '' ?>">

                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="firstname" class="form-control" value="<?= $user['firstname'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middlename" class="form-control" value="<?= $user['middlename'] ?? '' ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lastname" class="form-control" value="<?= $user['lastname'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $user['username'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="<?= $user['contact_number'] ?? '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password <small>(leave blank to keep current password)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>

<script>
$(document).ready(function(){
    $('#frm_profile').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'query/update_profile.php', // backend PHP to handle profile update
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp){              
                if(resp == '1'){
                    alert('Profile updated successfully.');
                  location.reload(); // reload to refresh session data
                }
               else if(resp == '2'){
                    alert('Username already taken. Please choose another.');
                }
                else {
                    alert(resp || 'Error updating profile.');
                }
            },
            error: function(err){
                console.log(err);
                alert('An error occurred.');
            }
        });
    });
});
</script>
