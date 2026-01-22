<?php
include 'connection.php';
?>
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approveForm">
                <div class="modal-header">
                    <h5 class="modal-title">Complete Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="approve_appointment_id">
                    <input type="hidden" name="status" id="status" value="completed">
                    <div class="mb-3">
                        Want to mark this appointment as completed?
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Completed</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="patient-booking-page">
    <h4 class="mb-4">My Appointments</h4>

    <!-- Filter options -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 d-none">
        <input type="date" id="filterDate" value="<?php echo date("Y-m-d") ?>" class="form-control form-control-sm" style="max-width:200px;" placeholder="Filter by Date">
        <select id="filterStatus" class="form-select form-select-sm" style="max-width:200px;">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="declined">Declined</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <!-- Appointment Table -->
    <table class="table table-striped table-bordered" id="appointmentsTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Schedule Date & Time</th>
                <th>Patient Name</th>
                <th>Status</th>

                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $appointments = $conn->query("
                    SELECT a.*, 
                           CONCAT(d.firstname,' ',d.lastname) AS patient_name,                        
                           professional_fee 
                    FROM appointments a 
                    INNER JOIN users d ON a.patient_id = d.user_id                                  
                    ORDER BY a.schedule_date DESC, a.schedule_time DESC
                ");
            while ($row = $appointments->fetch_assoc()) {
                $is_completed = ($row['status'] == 'approved') ? '' : 'disabled';
                $badge = ($row['status'] == "pending") ? "bg-warning" : (($row['status'] == "approved") ? "bg-success" : (($row['status'] == "declined") ? "bg-danger" : "bg-secondary"));
            ?>
                <tr>
                    <td><?= $row['appointment_id'] ?></td>
                    <td><?= $row['schedule_date'] . " " . $row['schedule_time'] ?></td>
                    <td><?= $row['patient_name'] ?></td>
                    <td><span class="badge <?= $badge ?>"><?= strtoupper($row['status']) ?></span></td>

                    <td><button class="btn btn-primary btn-sm btn-approve" <?= $is_completed ?>> Complete</button></td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
</div>

<style>
    .patient-booking-page h4 {
        font-weight: 600;
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#appointmentsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthChange: false
        });


        $('#appointmentsTable').on('click', '.btn-approve', function() {
            var row = table.row($(this).closest('tr')).data();
            $("#approve_appointment_id").val(row[0]); // appointment_id
            $("#approveModal").modal("show");
        });

        $("#approveForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "query/manage_booking.php",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert("Appointment Approved!" + response);
                    location.reload();
                }
            });
        });

        // Filter by status
        $('#filterStatus').on('change', function() {
            var status = $(this).val();
            table.column(4).search(status).draw();
        });

        // Filter by date
        $('#filterDate').on('change', function() {
            var date = $(this).val();
            table.column(1).search(date).draw();
        });
    });
</script>