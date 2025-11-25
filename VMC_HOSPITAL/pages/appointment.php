<?php
include 'connection.php';

$app_date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

$doctor_id = isset($_GET['doctor_id']) && $_GET['doctor_id'] != '' ? " AND doctor_id=" . $_GET['doctor_id'] : '';
$status = isset($_GET['status']) && $_GET['status'] != '' ? " AND status = '{$_GET['status']}'" : '';

$where = " WHERE a.schedule_date = '{$app_date}' $doctor_id $status";

$d_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
?>
<!-- APPROVE MODAL -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approveForm">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>                
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="approve_appointment_id">
                     <input type="hidden" name="status" id="status" value="approved">
                    <div class="mb-3">
                        <label class="form-label">Professional Fee</label>
                        <input type="number" class="form-control" name="professional_fee" id="professional_fee" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- DECLINE MODAL -->
<div class="modal fade" id="declineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="declineForm">
                <div class="modal-header">
                    <h5 class="modal-title">Decline Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="decline_appointment_id">
         <input type="hidden" name="status" id="status" value="declined">
                    <div class="mb-3">
                        <label class="form-label">Reason for Decline</label>
                        <textarea class="form-control" name="decline_reason" id="decline_reason" rows="3" required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Decline</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="container-fluid py-4">
    <h3 class="mb-4">Appointment Requests</h3>

    <!-- Filters -->
    <div class="card mb-4 p-3">
        <div class="row g-3 align-items-center">

            <!-- Date -->
            <div class="col-md-3">
                <label class="form-label">Appointment Date</label>
                <input type="date" value="<?= $app_date ?>" id="filter-date" class="form-control">
            </div>

            <!-- Doctor -->
            <div class="col-md-3">
                <label class="form-label">Doctor</label>
                <select id="filter-doctor" class="form-select">
                    <option value="">All Doctors</option>

                    <?php
                    $doctors = $conn->query("SELECT user_id, firstname, lastname, specialization FROM users WHERE role = 'doctor'");
                    while ($doc = $doctors->fetch_assoc()) {
                        $selected = ($d_id == $doc['user_id']) ? 'selected' : '';
                        ?>
                        <option value="<?= $doc['user_id'] ?>" <?= $selected ?>>
                            Dr. <?= htmlspecialchars($doc['firstname'] . ' ' . $doc['lastname'] . ' - ' . $doc['specialization']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Status -->
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select id="filter-status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending"  <?= $status_filter == 'pending'  ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $status_filter == 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="declined" <?= $status_filter == 'declined' ? 'selected' : '' ?>>Declined</option>
                    <option value="completed"<?= $status_filter == 'completed'? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button id="filter-btn" class="btn btn-primary w-100">Apply Filters</button>
            </div>

        </div>
    </div>

    <!-- Appointment Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Appointment List</h5>
        </div>
        <div class="card-body">
            <table id="appointments-table" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Note / Fee</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $appointments = $conn->query("
                    SELECT a.*, 
                           CONCAT(d.firstname,' ',d.lastname) AS doctor_name,
                           CONCAT(p.firstname,' ',p.lastname) AS patient_name,
                           professional_fee 
                    FROM appointments a 
                    INNER JOIN users d ON a.doctor_id = d.user_id
                    INNER JOIN users p ON a.patient_id = p.user_id
                    $where
                    ORDER BY a.schedule_date DESC, a.schedule_time DESC
                ");

                while ($row = $appointments->fetch_assoc()) {

                    $badge = ($row['status'] == "pending") ? "bg-warning" :
                            (($row['status'] == "approved") ? "bg-success" :
                            (($row['status'] == "declined") ? "bg-danger" : "bg-secondary"));
                    ?>

                    <tr>
                        <td><?= $row['appointment_id'] ?></td>
                        <td><?= $row['patient_name'] ?></td>
                        <td><?= $row['doctor_name'] ?></td>
                        <td><?= $row['schedule_date'] ?></td>
                        <td><?= $row['schedule_time'] ?></td>
                        <td><span class="badge <?= $badge ?>"><?= strtoupper($row['status']) ?></span></td>
                        <td><?=  ($row['status'] != 'approved')? $row['rejection_reason']:$row['professional_fee'] ;?></td>
                        <td>
                            <button class="btn btn-sm btn-success btn-approve" >Approve</button>
                            <button class="btn btn-sm btn-danger btn-decline">Decline</button>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {

    // Initialize DataTable and store instance
    var table = $('#appointments-table').DataTable();

    // Apply Filters
    $("#filter-btn").click(function () {
        var date = $("#filter-date").val();
        var doctor_id = $("#filter-doctor").val();
        var status = $("#filter-status").val();

        var query = "?page=appointment&date=" + date;
        if (doctor_id) query += "&doctor_id=" + doctor_id;
        if (status) query += "&status=" + status;

        window.location.href = query;
    });

    // OPEN APPROVE MODAL
    $('#appointments-table').on('click', '.btn-approve', function () {
        var row = table.row($(this).closest('tr')).data();
        $("#approve_appointment_id").val(row[0]); // appointment_id
        $("#professional_fee").val(""); // reset input
        $("#approveModal").modal("show");
    });

    // OPEN DECLINE MODAL
    $('#appointments-table').on('click', '.btn-decline', function () {
        var row = table.row($(this).closest('tr')).data();
        $("#decline_appointment_id").val(row[0]); // appointment_id
        $("#decline_reason").val(""); // reset input
        $("#declineModal").modal("show");
    });

    // Submit Approve Form via AJAX
    $("#approveForm").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: "query/manage_booking.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                alert("Appointment Approved!"+response);
                location.reload();
            }
        });
    });

    // Submit Decline Form via AJAX
    $("#declineForm").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: "query/manage_booking.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                alert("Appointment Declined!"+response);
                location.reload();
            }
        });
    });

});

</script>

