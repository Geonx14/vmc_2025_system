<div class="container-fluid py-4">
    <h3 class="mb-4">Doctors Management</h3>

    <!-- Button to open modal -->
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDoctorModal">
            <i class="fas fa-user-md"></i> Create Doctor
        </button>
    </div>

    <!-- Doctors Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Doctors List</h5>
        </div>
        <div class="card-body">
            <table id="doctors-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Contact</th>
                        <th>Specialization</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

            <?php
            include 'connection.php';
$doctor = $conn->query("SELECT * FROM users WHERE role = 'doctor' ");
while($row = $doctor->fetch_assoc()){
    ?>
    <tr>
        <td><?= $row['user_id'] ?></td>
        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['password']) ?></td>
        <td><?= htmlspecialchars($row['contact_number']) ?></td>
        <td><?= htmlspecialchars($row['specialization']) ?></td>
        <td>
            <!-- Future action buttons can be added here -->
            <button class="btn btn-sm btn-secondary btn-edit">Edit</button>
            <button class="btn btn-sm btn-danger btn-delete" onclick="deleteDoctor(<?php echo $row['user_id'] ?>)">Delete</button>
        </td>
    </tr>
    <?php
}            
            ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Doctor Modal -->
<div class="modal fade" id="createDoctorModal" tabindex="-1" aria-labelledby="createDoctorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form  method="POST" id="frm_save">
          <div class="modal-header">
            <h5 class="modal-title" id="createDoctorModalLabel">Create Doctor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                 <input type="hidden" name="role" class="form-control" value="doctor" required>
                <input type="text" name="user_id" class="form-control" >

              <div class="mb-3">
                  <label class="form-label">First Name</label>
                  <input type="text" name="firstname" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Last Name</label>
                  <input type="text" name="lastname" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Username</label>
                  <input type="text" name="username" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Contact Number</label>
                  <input type="text" name="contact_number" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Specialization</label>
                  <input type="text" name="specialization" class="form-control" required>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Doctor</button>
          </div>
      </form>
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
    // Initialize Doctors DataTable
    var table = $('#doctors-table').DataTable();
        $('#doctors-table').on('click', '.btn-edit', function(){
        var row = $(this).closest('tr');
        var data = table.row(row).data();

        // Fill the modal fields
        $('#createDoctorModalLabel').text('Edit Doctor');
        $('#frm_save [name="user_id"]').val(data[0]); // ID
        $('#frm_save [name="firstname"]').val(data[1].split(' ')[0]);
        $('#frm_save [name="lastname"]').val(data[1].split(' ')[1] || '');
        $('#frm_save [name="username"]').val(data[2]);
        $('#frm_save [name="password"]').val(data[3]); // blank for editing
        $('#frm_save [name="contact_number"]').val(data[4]);
        $('#frm_save [name="specialization"]').val(data[5]);
        
        // Show the modal
        $('#createDoctorModal').modal('show');
    });

    $("#frm_save").submit(function(e){
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'query/save_user.php',
            type: 'POST',
            data: formData,
            success: function(response){
                if(response.trim() == "1"){
                    alert('Doctor created successfully.');
                    window.location.reload();
                } else {
                    alert(response);
                }
            },
            error: function(){
                alert('An error occurred while processing your request.');
            }
        });
    });




});
function deleteDoctor(id) {
    if(confirm("Are you sure you want to delete this doctor?")) {
        $.ajax({
            url: 'query/delete_user.php',
            type: 'POST',
            data: { user_id: id },
            success: function(response) {
                if(response.trim() == "1"){
                    alert("Doctor deleted successfully.");
                    location.reload();
                } else {
                    alert("Error deleting doctor: " + response);
                }
            },
            error: function() {
                alert("An error occurred while processing the request.");
            }
        });
    } else {
        console.log("Deletion canceled");
    }
}
</script>
