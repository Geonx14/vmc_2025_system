<?php
include 'connection.php';


$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// Fetch current & ongoing events
$events = $conn->query("SELECT * FROM events WHERE '$today' BETWEEN event_date_start AND event_date_end ORDER BY event_date_start ASC");




// Fetch student's graduation requests

$where = ($_SESSION['role'] =='student')?" where student_id=$user_id":"";
$requests = $conn->query("SELECT * FROM graduation_requests  g join users u on g.student_id=u.user_id $where  ORDER BY request_date DESC");


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
    }

    h3.section-title {
        color: #1a73e8;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    .card-body {
        padding: 20px;
    }

    .card-title {
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .card-text {
        color: #555;
        margin-bottom: 10px;
    }

    .status-current {
        border-left: 5px solid #1a73e8;
    }

    .status-upcoming {
        border-left: 5px solid #198754;
    }

    .badge-status {
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 0.8rem;
        color: white;
        font-weight: 500;
    }

    .badge-current { background-color: #1a73e8; }
    .badge-upcoming { background-color: #198754; }

</style>


<div class="content-box">


<?php 
if($_SESSION['role'] == 'student'):
?>
    <h3 class="fw-bold text-primary mb-4">Student Dashboard</h3>

    <!-- Current & Ongoing Events -->
    <div class="card">
        <div class="card-body">
            <h5>Current & Ongoing Events</h5>
            <?php if($events->num_rows > 0): ?>
                <div class="row g-3 mt-2">
                    <?php while($event = $events->fetch_assoc()): ?>
                       <div class="col-md-4">
            <div class="card status-current">
                <div class="card-body">
                    <h5 class="card-title"><?= $event['event_title'] ?></h5>
                    <span class="badge-status badge-current">Current</span>
                    <p class="card-text"><?= $event['event_desc'] ?></p>
                    <p class="mb-1 text-muted"><i class="bi bi-calendar-event"></i> <?= date('M d, Y', strtotime($event['event_date_start'])) ?> - <?= date('M d, Y', strtotime($event['event_date_end'])) ?></p>
                     
                </div>
            </div>
        </div>
                    
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-muted mt-2">No current events.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Student Requests Table -->
    <div class="card">
        <div class="card-body">
            <h5>My Pictorial Requests</h5>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Request Date</th>              
                            <th>Status</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($requests->num_rows > 0): ?>
                            <?php $i=1; while($req = $requests->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= date('M d, Y', strtotime($req['request_date'])) ?></td>                            
                                    <td class="fw-bold
                                        <?= $req['status']=='Approved' ? 'status-approved' : ($req['status']=='Rejected' ? 'status-rejected' : 'status-pending') ?>">
                                        <?= $req['status'] ?>
                                    </td>
                                 
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No requests submitted yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif;
?>


<?php 
if($_SESSION['role'] == 'alumni'):
?>
    <h3 class="fw-bold text-primary mb-4">Alumni Dashboard</h3>

    <!-- Current & Ongoing Events -->
    <div class="card">
        <div class="card-body">
            <h5>Current & Ongoing Events</h5>
            <?php if($events->num_rows > 0): ?>
                <div class="row g-3 mt-2">
                    <?php while($event = $events->fetch_assoc()): ?>
                       <div class="col-md-4">
            <div class="card status-current">
                <div class="card-body">
                    <h5 class="card-title"><?= $event['event_title'] ?></h5>
                    <span class="badge-status badge-current">Current</span>
                    <p class="card-text"><?= $event['event_desc'] ?></p>
                    <p class="mb-1 text-muted"><i class="bi bi-calendar-event"></i> <?= date('M d, Y', strtotime($event['event_date_start'])) ?> - <?= date('M d, Y', strtotime($event['event_date_end'])) ?></p>
                     
                </div>
            </div>
        </div>
                    
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-muted mt-2">No current events.</p>
            <?php endif; ?>
        </div>
    </div>

<?php endif;
?>


<?php 
if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'staff'):
?>
    <h3 class="fw-bold text-primary mb-4">Admin Dashboard</h3>

    <!-- Current & Ongoing Events -->
    <div class="card">
        <div class="card-body">
            <h5>Current & Ongoing Events</h5>
            <?php if($events->num_rows > 0): ?>
                <div class="row g-3 mt-2">
                    <?php while($event = $events->fetch_assoc()): ?>
                       <div class="col-md-4">
            <div class="card status-current">
                <div class="card-body">
                    <h5 class="card-title"><?= $event['event_title'] ?></h5>
                    <span class="badge-status badge-current">Current</span>
                    <p class="card-text"><?= $event['event_desc'] ?></p>
                    <p class="mb-1 text-muted"><i class="bi bi-calendar-event"></i> <?= date('M d, Y', strtotime($event['event_date_start'])) ?> - <?= date('M d, Y', strtotime($event['event_date_end'])) ?></p>
                     
                </div>
            </div>
        </div>
                    
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-muted mt-2">No current events.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Student Requests Table -->
    <div class="card">
        <div class="card-body">
            <h5>Students Pictorial Requests</h5>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Schedule Date</th>                          
                            <th>Status</th>
     
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($requests->num_rows > 0): ?>
                            <?php $i=1; while($req = $requests->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?=$req['firstname']." ".$req['middlename']." ".$req['lastname']?></td>
                                    <td><?= date('M d, Y', strtotime($req['request_date'])) ?></td>
                                    
                                    <td class="fw-bold
                                        <?= $req['status']=='Approved' ? 'status-approved' : ($req['status']=='Rejected' ? 'status-rejected' : 'status-pending') ?>">
                                        <?= $req['status'] ?>
                                    </td>
                           
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No requests submitted yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif;
?>

</div>
<script>
    $(".table").DataTable()
</script>