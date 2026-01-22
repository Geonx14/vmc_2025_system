<div class="container-fluid py-4">
    <h3 class="mb-4">Patients Management</h3>

    <!-- Button to open modal -->
    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPatientModal">
            <i class="fas fa-user-md"></i> Create Patient
        </button>
    </div>

    <!-- Patients Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Patients List</h5>
        </div>
        <div class="card-body">
            <table id="Patients-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Birthday</th>
                        <th>Email</th>
                        <th>Full Address</th>
                        <th>Gurdian's Name</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    include 'connection.php';
                    $Patient = $conn->query("SELECT * FROM users WHERE role = 'patient' ");
                    while ($row = $Patient->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?= $row['user_id'] ?></td>
                            <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['contact_number']) ?></td>
                            <td><?= htmlspecialchars($row['birthdate']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['guardians_name']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['password']) ?></td>
                            <td>
                                <!-- Future action buttons can be added here -->
                                <button class="btn btn-sm btn-secondary btn-edit">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deletePtient(<?php echo $row['user_id'] ?>)">Delete</button>
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

<!-- Create Patient Modal -->
<div class="modal fade" id="createPatientModal" tabindex="-1" aria-labelledby="createPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="frm_save">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPatientModalLabel">Create Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="role" class="form-control" value="Patient" required>
                    <input type="hidden" name="user_id" class="form-control">

                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Birthday</label>
                        <input type="date" name="birthdate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Guardian's Name</label>
                        <input type="text" name="guardians_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize Patients DataTable
        var table = $('#Patients-table').DataTable();
        $('#Patients-table').on('click', '.btn-edit', function() {
            var row = $(this).closest('tr');
            var data = table.row(row).data();

            // Fill the modal fields
            $('#createPatientModalLabel').text('Edit Patient');
            $('#frm_save [name="user_id"]').val(data[0]); // ID
            $('#frm_save [name="firstname"]').val(data[1].split(' ')[0]);
            $('#frm_save [name="lastname"]').val(data[1].split(' ')[1] || '');
            $('#frm_save [name="username"]').val(data[7]);
            $('#frm_save [name="password"]').val(data[8]); // blank for editing
            $('#frm_save [name="contact_number"]').val(data[2]);
            $('#frm_save [name="birthdate"]').val(data[3]);
            $('#frm_save [name="email"]').val(data[4]);
            $('#frm_save [name="address"]').val(data[5]);
            $('#frm_save [name="guardians_name"]').val(data[6]);


            // Show the modal
            $('#createPatientModal').modal('show');
        });

        $("#frm_save").submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'query/save_user.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.trim() == "1") {
                        alert('Patient created successfully.');
                        window.location.reload();
                    } else {
                        alert(response);
                    }
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                }
            });
        });
    });

    function deletePtient(id) {
        if (confirm("Are you sure you want to delete this patient?")) {
            $.ajax({
                url: 'query/delete_user.php',
                type: 'POST',
                data: {
                    user_id: id
                },
                success: function(response) {
                    if (response.trim() == "1") {
                        alert("Patient deleted successfully.");
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