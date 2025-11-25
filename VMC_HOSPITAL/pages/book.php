<?php             
include 'connection.php';?>
<div class="container-fluid py-4">
    <h3 class="mb-4">Book Appointment</h3>

    <!-- Booking Form -->
    <div class="card mb-4 p-4">
        <h5 class="mb-3">New Appointment</h5>
        <form id="frm_appointment">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Select Doctor</label>
                    <input type="hidden" name="appointment_id" id="appointment_id" class="form-control" >
                    <select name="doctor_id" id="doctor_id" class="form-select" required>
                        <option value="">-- Choose Doctor --</option>
                        <?php
            
                     $doctors = $conn->query("SELECT user_id, firstname, lastname, specialization FROM users WHERE role = 'doctor' ");
                     while($doc = $doctors->fetch_assoc()){
                        ?>
                           <option value="<?php echo $doc['user_id'] ?>">
                               Dr. <?php echo htmlspecialchars($doc['firstname'] . ' ' . $doc['lastname'] . ' - ' . $doc['specialization']) ?>
                        <?php
                     }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Schedule Date</label>
                    <input type="date" name="schedule_date" id="schedule_date" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Schedule Time</label>
                    <input type="time" name="schedule_time" id="schedule_time" value="<?= date("H:i") ?>" class="form-control" >
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Book Appointment</button>
            </div>
        </form>
    </div>

    <!-- Appointment List -->
    <div class="card p-4">
        <h5 class="mb-3">My Appointments</h5>
        <div class="table-responsive">
<table id="appointments_table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Doctor</th>
            <th>Created At</th>
            <th>Schedule At</th>
            <th>Status</th>
            <th>Professional Fee</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $date = date("Y-m-d");
        $appointments = $conn->query("
            SELECT a.*, 
                   CONCAT(d.firstname,' ',d.lastname) AS doctor_name, 
                   professional_fee 
            FROM appointments a 
            INNER JOIN users d ON a.doctor_id = d.user_id 
            WHERE a.patient_id = '{$_SESSION['user_id']}' and DATE(a.created_at) = '$date'
            ORDER BY a.schedule_date DESC, a.schedule_time DESC
        ");

      
        while($row = $appointments->fetch_assoc()){
$badge = ($row['status'] == "pending") 
            ? "bg-warning" 
            : (($row['status'] == "approved") 
                ? "bg-success"
                : (($row['status'] == "declined")
                    ? "bg-danger"
                    : "bg-secondary"));
                    $is_disabled = ($row['status'] != "pending") ? "disabled" : "";
?>
 
        <tr>
            <td><?= $row['appointment_id'] ?></td>
            <td data-doctor-id="<?= $row['doctor_id'] ?>"><?= $row['doctor_name'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td><?= $row['schedule_date']." ".$row['schedule_time'] ?></td>
            <td><span class="badge <?= $badge  ?> "><?=  strtoupper($row['status'])?></span></td>
            <td><?=  ($row['status'] != 'approved')? $row['rejection_reason']:$row['professional_fee'] ;?></td>
            <td>
                <button class="btn btn-sm btn-secondary btn-edit" <?=     $is_disabled  ?>>Edit</button>
                <button class="btn btn-sm btn-danger btn-delete" <?=     $is_disabled  ?>>Delete</button>
            </td>
        </tr>
        <?php }  ?>
    </tbody>
</table>

        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.table th, .table td {
    vertical-align: middle;
}
</style>

<script>
$(document).ready(function(){
    // Initialize datatable
    var table = $('#appointments_table').DataTable();

$('#appointments_table').on('click', '.btn-edit', function () {
    var row = $(this).closest('tr');
    var data = table.row(row).data();

    // Correct data index mapping
    var appointment_id   = data[0];
    var doctor_name      = data[1];
    var datetime = data[3]; // "2025-11-21 15:03:10"

var parts = datetime.split(" "); // ["2025-11-21", "15:03:10"]

        var schedule_date = parts[0];  // "2025-11-21"
        var schedule_time = parts[1];  // "15:03:10"   
    var status           = data[4];
    var professional_fee = data[5];


    // Get doctor_id from hidden attribute
    var doctor_id = row.find("td:eq(1)").data("doctor-id");

    // Fill modal fields
    $("#appointment_id").val(appointment_id);
    $("#doctor_id").val(doctor_id);
    $("#schedule_date").val(schedule_date);
    $("#schedule_time").val(schedule_time.substring(0, 5));
    $("#status").val(status);

    // Show modal
    $("#editAppointmentModal").modal("show");
});
    // Handle appointment booking
    $('#frm_appointment').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'query/book_appointment.php', // backend PHP to handle booking
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp){
         
                if(resp == '1'){
                    alert('Appointment booked successfully.');
                    location.reload();
                } else {
                    alert(resp || 'Failed to book appointment.');
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
