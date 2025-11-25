<?php
include 'connection.php';

$appointment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "update `appointments` set is_read = 1
         WHERE appointment_id=$appointment_id";
         $conn->query($sql);

$appointment_query = "
   SELECT a.*, 
           CONCAT(p.firstname,' ',p.lastname) AS patient_name,
           CONCAT(d.firstname,' ',d.lastname) AS doctor_name,                        
           a.professional_fee,
           a.rejection_reason
    FROM appointments a 
    INNER JOIN users d ON a.doctor_id = d.user_id
    INNER join users p ON a.patient_id = p.user_id
    WHERE a.appointment_id = $appointment_id
";

$appointment = $conn->query($appointment_query)->fetch_assoc();

// Protect if no record found
if (!$appointment) {
    echo "<div class='alert alert-danger'>Appointment not found.</div>";
    exit;
}

// Status badge logic
$badge = match($appointment['status']) {
    "pending" => "bg-warning",
    "approved" => "bg-success",
    "declined" => "bg-danger",
    "completed" => "bg-primary",
    default => "bg-secondary"
};
?>

<div class="appointment-details-page container py-4">
    <h3 class="mb-4">Appointment Details</h3>

    <div class="card shadow-sm border-0 p-3">
        <div class="row g-3">

            <!-- Patient -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-user fa-2x text-primary me-3"></i>
                <div>
                    <h6 class="mb-1">Patient</h6>
                    <p class="mb-0"><?= $appointment["patient_name"] ?></p>
                </div>
            </div>

            <!-- Doctor -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-user-md fa-2x text-success me-3"></i>
                <div>
                    <h6 class="mb-1">Doctor</h6>
                    <p class="mb-0"><?= $appointment["doctor_name"] ?></p>
                </div>
            </div>

            <!-- Schedule -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-calendar-alt fa-2x text-warning me-3"></i>
                <div>
                    <h6 class="mb-1">Scheduled Date & Time</h6>
                    <p class="mb-0">
                        <?= $appointment["schedule_date"] . " " . $appointment["schedule_time"] ?>
                    </p>
                </div>
            </div>

            <!-- Created At -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-clock fa-2x text-info me-3"></i>
                <div>
                    <h6 class="mb-1">Created At</h6>
                    <p class="mb-0"><?= $appointment["created_at"] ?></p>
                </div>
            </div>

            <!-- Status -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x text-secondary me-3"></i>
                <div>
                    <h6 class="mb-1">Status</h6>
                    <p class="mb-0"><span class="badge <?= $badge ?>"><?= strtoupper($appointment["status"]) ?></span></p>
                </div>
            </div>

            <!-- Notes / Fee -->
            <div class="col-md-6 d-flex align-items-center">
                <i class="fas fa-sticky-note fa-2x text-danger me-3"></i>
                <div>
                    <h6 class="mb-1">Notes / Fee</h6>
                    <p class="mb-0">
                        <?php if ($appointment["status"] == "declined"): ?>
                            Reason: <?= $appointment["rejection_reason"] ?: "No reason provided" ?>
                        <?php elseif ($appointment["status"] == "approved" || $appointment["status"] == "completed"): ?>
                            Fee: ₱<?= number_format($appointment["professional_fee"], 2) ?>
                        <?php else: ?>
                            Waiting for update...
                        <?php endif; ?>
                    </p>
                </div>
            </div>

        </div>

        <div class="mt-4 text-end">
            <a href="?page=notification" class="btn btn-outline-secondary">Back to Notification</a>
        </div>
    </div>
</div>

<style>
.appointment-details-page h3 { font-weight: 600; }
.card h6 { font-weight: 500; color: #495057; }
.card p { font-size: 0.95rem; color: #6c757d; }
.fas { min-width: 40px; text-align: center; }
</style>
