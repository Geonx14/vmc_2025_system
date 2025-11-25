<?php
$condiotion = $_SESSION['role'] == 'admin' ? "" : " and (a.patient_id = {$_SESSION['user_id']} or a.doctor_id = {$_SESSION['user_id']}) ";
//$list_appointments = $conn->query("SELECT * FROM appointments WHERE  status='approved' and patient_id = {$_SESSION['user_id']} and DATE(schedule_date) = CURDATE()");
$appointment_query = "
   SELECT a.*, 
           CONCAT(p.firstname,' ',p.lastname) AS patient_name,
           CONCAT(d.firstname,' ',d.lastname) AS doctor_name,                        
           a.professional_fee,
           a.rejection_reason
    FROM appointments a 
    INNER JOIN users d ON a.doctor_id = d.user_id
    INNER join users p ON a.patient_id = p.user_id
    WHERE DATE(a.schedule_date) = CURDATE() and a.status='approved' $condiotion
";
$list_appointments = $conn->query($appointment_query);
$total_appointments = $list_appointments->num_rows;
$total_patients = $conn->query("SELECT * FROM users WHERE role='patient'")->num_rows;
$total_doctors = $conn->query("SELECT * FROM users WHERE role='doctor'")->num_rows;

?> 
 <style>
        body {
            background: #f5f7fb;
            font-family: 'Inter', sans-serif;
        }
        .card-icon {
            font-size: 2.5rem;
        }
        .dashboard-card {
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
<div class="container-fluid p-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- Dashboard Summary Cards -->
    <div class="row g-4 mb-4">
        <?php  
        if($_SESSION['role'] == 'admin'):
        ?>
        <div class="col-md-4">
            <div class="card dashboard-card p-3 text-center text-white bg-primary">
                <div class="card-body">
                    <i class="fas fa-user-injured card-icon mb-2"></i>
                    <h5 class="card-title">Total Patients</h5>
                    <h3 class="card-text" id="total-patients"><?= $total_patients ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card p-3 text-center text-white bg-success">
                <div class="card-body">
                    <i class="fas fa-user-doctor card-icon mb-2"></i>
                    <h5 class="card-title">Total Doctors</h5>
                    <h3 class="card-text" id="total-doctors"><?=    $total_doctors ?></h3>
                </div>
            </div>
        </div>
<?php endif; ?>
        <div class="col-md-4">
            <div class="card dashboard-card p-3 text-center text-white bg-dark">
                <div class="card-body">
                    <i class="fas fa-calendar-day card-icon mb-2"></i>
                    <h5 class="card-title">Today's Appointments</h5>
                    <h3 class="card-text" id="total-today"><?= $total_appointments; ?></h3>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-3">
            <div class="card dashboard-card p-3 text-center text-white bg-info">
                <div class="card-body">
                    <i class="fas fa-check-circle card-icon mb-2"></i>
                    <h5 class="card-title">Today's Approved</h5>
                    <h3 class="card-text" id="total-approved">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3">
            <div class="card dashboard-card p-3 text-center text-white bg-danger">
                <div class="card-body">
                    <i class="fas fa-times-circle card-icon mb-2"></i>
                    <h5 class="card-title">Today's Rejected</h5>
                    <h3 class="card-text" id="total-rejected">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3">
            <div class="card dashboard-card p-3 text-center text-white bg-secondary">
                <div class="card-body">
                    <i class="fas fa-clock card-icon mb-2"></i>
                    <h5 class="card-title">Pending Appointments</h5>
                    <h3 class="card-text" id="total-pending">0</h3>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Today's Appointments Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Today's Appointments</h5>
        </div>
        <div class="card-body">
            <table id="appointments-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Doctor Name</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Fee</th>
                    </tr>
                </thead>
                <tbody>
                 <?php
                 while($row = $list_appointments->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= $row['patient_name'] ?></td>
                            <td><?= $row['doctor_name'] ?></td>
                            <td><?= date("h:i A", strtotime($row['schedule_time'])) ?></td>
                            <td>
                                <?php
                                $status_badge = match($row['status']) {
                                    'approved' => 'success',
                                    'declined' => 'danger',
                                    'pending' => 'warning',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?= $status_badge ?>"><?= ucfirst($row['status']) ?></span>
                            </td>
                            <td>P<?= number_format($row['professional_fee'], 2) ?></td>
                            <?php endwhile; ?>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

    // Initialize DataTable
    $('#appointments-table').DataTable();

    
</script>
</body>