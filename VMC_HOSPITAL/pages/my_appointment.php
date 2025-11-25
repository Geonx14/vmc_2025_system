<?php
include 'connection.php';
?>
<div class="patient-booking-page">
    <h4 class="mb-4">My Appointments</h4>

    <!-- Filter options -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
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
                <th>Created At</th>
                <th>Schedule Date & Time</th>
                <th>Doctor</th>
                <th>Status</th>
                <th>Notes / Fee</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $appointments = $conn->query("
                    SELECT a.*, 
                           CONCAT(d.firstname,' ',d.lastname) AS doctor_name,                        
                           professional_fee 
                    FROM appointments a 
                    INNER JOIN users d ON a.doctor_id = d.user_id
                  where a.patient_id = {$_SESSION['user_id']}                   
                    ORDER BY a.schedule_date DESC, a.schedule_time DESC
                ");

                while ($row = $appointments->fetch_assoc()) {

                    $badge = ($row['status'] == "pending") ? "bg-warning" :
                            (($row['status'] == "approved") ? "bg-success" :
                            (($row['status'] == "declined") ? "bg-danger" : "bg-secondary"));
                    ?>

                    <tr>
                        <td><?= $row['appointment_id'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td><?= $row['schedule_date']." ". $row['schedule_time'] ?></td>
                        <td><?= $row['doctor_name'] ?></td>
                        <td><span class="badge <?= $badge ?>"><?= strtoupper($row['status']) ?></span></td>
                        <td><?=  $row['rejection_reason']." | ".$row['professional_fee'];?></td>                       
                    </tr>

                <?php } ?>

        </tbody>
    </table>
</div>

<style>
.patient-booking-page h4 { font-weight: 600; }
</style>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#appointmentsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false
    });
    var today = $("#filterDate").val();
    table.column(2).search(today).draw();

    // Filter by status
    $('#filterStatus').on('change', function() {
        var status = $(this).val();
        table.column(4).search(status).draw();
    });

    // Filter by date
    $('#filterDate').on('change', function() {
        var date = $(this).val();
        table.column(2).search(date).draw();
    });
});
</script>
