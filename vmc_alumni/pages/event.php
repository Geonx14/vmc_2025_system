<?php
include 'connection.php';
$currentDate = date('Y-m-d');
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

<body>
<div class="content-box">
    <h3 class="fw-bold text-primary mb-4">Events</h3>

    <!-- Current Events -->
    <h3 class="section-title">Current Events</h3>
    <div class="row" id="currentEvents">
<?php

$eventsQuery = $conn->query("SELECT * FROM events WHERE event_date_start <= '$currentDate' AND event_date_end >= '$currentDate' ORDER BY event_date_start DESC");
if($eventsQuery->num_rows == 0):
?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                No current events available.
            </div>
        </div>      
<?php
else:
while($event = $eventsQuery->fetch_assoc()):

?>
        <a style="text-decoration: none;cursor: pointer;"  <?php if ($_SESSION['role']=='admin' || $_SESSION['role']=='staff') { ?>
       href="?page=participant_list&event_id=<?= $event['event_id'] ?>"
   <?php } ?> class="col-md-4">
            <div class="card status-current">
                <div class="card-body">
                    <h5 class="card-title"><?=  $event['event_title'] ?></h5>
                    <span class="badge-status badge-current">Current</span>
                    <p class="card-text"><?=  $event['event_desc'] ?></p>
                     <p class="mb-1 text-muted"><i class="bi bi-calendar-event"></i> <?= date('M d, Y', strtotime($event['event_date_start'])) ?> - <?= date('M d, Y', strtotime($event['event_date_end'])) ?></p>
                
                </div>
            </div>
</a>
<?php endwhile; 
endif;
?>

    </div>

    <!-- Upcoming Events -->
    <h3 class="section-title">Upcoming Events</h3>
    <div class="row" id="upcomingEvents">

<?php
$upcomingEventsQuery = $conn->query("SELECT * FROM events WHERE event_date_start > '$currentDate' ORDER BY event_date_start ASC");
if($upcomingEventsQuery->num_rows == 0):
?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                No upcoming events available.
            </div>  
        </div>
<?php
else:
while($event = $upcomingEventsQuery->fetch_assoc()):
?>
 <div class="col-md-4">
            <div class="card status-upcoming">
                <div class="card-body">
                    <h5 class="card-title"><?=  $event['event_title'] ?></h5>
                    <span class="badge-status badge-upcoming">Upcoming</span>
                  <p class="card-text"><?=  $event['event_desc'] ?></p>
                     <p class="mb-1 text-muted"><i class="bi bi-calendar-event"></i> <?= date('M d, Y', strtotime($event['event_date_start'])) ?> - <?= date('M d, Y', strtotime($event['event_date_end'])) ?></p>
           
                </div>
            </div>
        </div>
<?php endwhile; ?>

<?php endif; ?>
    </div>  
</div>
