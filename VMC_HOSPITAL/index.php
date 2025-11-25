<?php 
include 'connection.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
if($_SESSION['role'] == 'admin'){
$appointments = $conn->query("
    SELECT a.*, 
           CONCAT(d.firstname,' ',d.lastname) AS doctor_name,
           CONCAT(p.firstname,' ',p.lastname) AS patient_name,
           professional_fee 
    FROM appointments a 
    INNER JOIN users d ON a.doctor_id = d.user_id
    INNER JOIN users p ON a.patient_id = p.user_id
    WHERE is_read = 0
    ORDER BY a.schedule_date DESC, a.schedule_time DESC");
}
if($_SESSION['role'] == 'doctor'){
$appointments = $conn->query("
    SELECT a.*, 
           CONCAT(p.firstname,' ',p.lastname) AS patient_name,
           professional_fee 
    FROM appointments a 
    INNER JOIN users p ON a.patient_id = p.user_id
    WHERE a.doctor_id = {$_SESSION['user_id']} and is_read = 0
    ORDER BY a.schedule_date DESC, a.schedule_time DESC");
}
if($_SESSION['role'] == 'patient'){
$appointments = $conn->query("
    SELECT a.*, 
           CONCAT(d.firstname,' ',d.lastname) AS doctor_name,
           professional_fee 
    FROM appointments a 
    INNER JOIN users d ON a.doctor_id = d.user_id
    WHERE a.patient_id = {$_SESSION['user_id']} and status != 'pending' and is_read = 0
    ORDER BY a.schedule_date DESC, a.schedule_time DESC


");
}

$page_title = isset($_GET['page']) ? $_GET['page']  :"dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hospital Appointment System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
:root {
    --primary: #4361ee;
    --sidebar-bg: #1a1d29;
    --sidebar-hover: #2d3246;
    --content-bg: #f5f7fb;
    --card-bg: #ffffff;
    --text-primary: #2c3e50;
}

body { font-family:'Inter', sans-serif; background: var(--content-bg); color: var(--text-primary); margin:0; }
.sidebar { position: fixed; top:0; left:0; height:100vh; width:280px; background: var(--sidebar-bg); transition: all 0.3s; overflow-y:auto; z-index:1000; }
.sidebar.collapsed { width:80px; }
.sidebar-header { padding:24px 20px; display:flex; align-items:center; gap:12px; border-bottom:1px solid rgba(255,255,255,0.1);}
.sidebar-header img { width:40px; height:40px; border-radius:8px; transition: all 0.3s ease;}
.sidebar.collapsed .sidebar-header img { width:35px; height:35px;}
.logo-text { color:white; font-weight:700; font-size:1.3rem; transition:opacity 0.3s; }
.sidebar.collapsed .logo-text { opacity:0; width:0; }
.sidebar-menu { padding:20px 0; list-style:none; }
.sidebar-menu li { margin-bottom:4px; }
.sidebar-menu a { display:flex; align-items:center; padding:14px 20px; color: rgba(255,255,255,0.8); text-decoration:none; border-radius:8px; margin:0 12px; font-weight:500; transition: all 0.3s;}
.sidebar-menu a:hover { background: var(--sidebar-hover); color:white; transform: translateX(5px);}
.sidebar-menu i { font-size:1.2rem; margin-right:12px; min-width:24px; text-align:center; transition:margin 0.3s;}
.sidebar.collapsed .sidebar-menu i { margin-right:0;}
.menu-text { transition:opacity 0.3s;}
.sidebar.collapsed .menu-text { opacity:0; width:0;}
.main-content { margin-left:280px; transition: all 0.3s; min-height:100vh; background: var(--content-bg);}
.main-content.expanded { margin-left:80px;}
.active{ background: var(--sidebar-hover) !important; color:white !important; }
.top-nav { background: var(--card-bg); padding:8px 20px; height:60px; box-shadow:0 2px 10px rgba(0,0,0,0.06); position:sticky; top:0; z-index:99; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
.nav-left h5 { font-size:1rem; font-weight:600; margin:0; }
.menu-toggle { background:none; border:none; font-size:1.3rem; cursor:pointer; padding:6px; border-radius:6px; transition: all 0.3s; }
.menu-toggle:hover { background: #f8f9fa; }
.user-avatar { width:34px; height:34px; border-radius:50%; background:var(--primary); color:white; display:flex; align-items:center; justify-content:center; font-size:0.9rem; }
.user-info small { font-size:0.75rem; }
.main-area { padding:30px; }
.content-card { background: var(--card-bg); border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); border:none; overflow:hidden; padding:20px; }

/* Notifications */
.notification-wrapper { position: relative; }
.notification-dropdown { position: absolute; top:120%; right:0; width:300px; max-height:400px; overflow-y:auto; z-index:999; background:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.15); border-radius:8px; display:none; }
.notification-dropdown.show { display:block; }
.notification-item { cursor:pointer; }

@media(max-width:992px){
    .sidebar { transform: translateX(-100%);}
    .sidebar.mobile-open { transform: translateX(0);}
    .main-content { margin-left:0 !important;}
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="hospital-logo.png" alt="Logo">
        <span class="logo-text">HosCheck</span>
    </div>
    <ul class="sidebar-menu">
        <li class="<?php echo ($page_title == "dashboard")? 'active' :'' ?>"><a href="?page=dashboard"><i class="fas fa-home"></i><span class="menu-text">Dashboard</span></a></li>
                 <?php 
if($_SESSION['role'] == 'admin'):
?>
            <li class="<?php echo ($page_title == "appointment")? 'active' :'' ?>"><a href="?page=appointment"><i class="fas fa-calendar-check"></i><span class="menu-text">Appointments</span></a></li>
      <?php endif; ?>
      <?php 
        if($_SESSION['role'] == 'patient'):
        ?>
         <li class="<?php echo ($page_title == "book")? 'active' :'' ?>"><a href="?page=book"><i class="fas fa-calendar-check"></i><span class="menu-text">Book Appointments</span></a></li>

        <li class="<?php echo ($page_title == "my_appointment")? 'active' :'' ?>"><a href="?page=my_appointment"><i class="fas fa-calendar-check"></i><span class="menu-text">My Appointments</span></a></li>        
        <?php endif; ?>
         <?php 
        if($_SESSION['role'] == 'doctor'):
        ?>
        <li class="<?php echo ($page_title == "doctor_appointment")? 'active' :'' ?>"><a href="?page=doctor_appointment"><i class="fas fa-calendar-check"></i><span class="menu-text">My Appointments</span></a></li>        
        <?php endif; ?>
        
<?php 
if($_SESSION['role'] == 'admin'):
?>
        <li class="<?php echo ($page_title == "doctor")? 'active' :'' ?>"><a href="?page=doctor"><i class="fas fa-user-md"></i><span class="menu-text">Doctors</span></a></li>
        <li class="<?php echo ($page_title == "patient")? 'active' :'' ?>"><a href="?page=patient"><i class="fas fa-users"></i><span class="menu-text">Patients</span></a></li>
        <?php endif; ?>
        <li class="<?php echo ($page_title == "profile")? 'active' :'' ?>"><a href="?page=profile"><i class="fas fa-user"></i><span class="menu-text">Profile</span></a></li>
        <li><a href="query/logout.php"><i class="fas fa-right-from-bracket"></i><span class="menu-text">Logout</span></a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
<nav class="top-nav d-flex justify-content-between align-items-center px-3 py-2">
    <div class="nav-left d-flex align-items-center gap-3">
        <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
        <h5 class="mb-0"><?php echo $page_title ?></h5>
    </div>
    <div class="nav-right d-flex align-items-center gap-3">
        <div class="notification-wrapper <?= ($_SESSION['role'] != 'patient')? 'd-none' :''?>">
            <div class="notification-bell position-relative" style="cursor:pointer;">
                <i class="fas fa-bell fa-lg"></i>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle"><?= $appointments->num_rows; ?></span>
            </div>
            <div class="notification-dropdown">
<?php


$hasNotification = false; // flag

function timeAgo($timestamp) {
    $time = time() - strtotime($timestamp);

    if ($time < 60) {
        return $time . "s ago"; // seconds
    } elseif ($time < 3600) {
        return floor($time / 60) . "m ago"; // minutes
    } elseif ($time < 86400) {
        return floor($time / 3600) . "h ago"; // hours
    } elseif ($time < 604800) {
        return floor($time / 86400) . "d ago"; // days
    } elseif ($time < 2592000) {
        return floor($time / 604800) . "w ago"; // weeks
    } elseif ($time < 31536000) {
        return floor($time / 2592000) . "mo ago"; // months
    } else {
        return floor($time / 31536000) . "y ago"; // years
    }
}

?>

<?php

while ($row = $appointments->fetch_assoc()): ?>

    <?php
    $ago_time = time() - strtotime($row['updated_at']);

    if ($row['status'] == 'declined'): ?>
        <?php $hasNotification = true; ?>
        <a href="?page=app_det&id=<?= $row['appointment_id'] ?>" class="notification-item d-flex align-items-center p-2 unread text-muted" style="text-decoration: none;">
            <i class="fas fa-calendar-times me-2 text-danger"></i>
            <div class="message small text-truncate">Your appointment was rejected.</div>
            <div class="time ms-auto text-muted small"><?= timeAgo($row['updated_at']) ?>
 </div>
        </a>
    <?php endif; ?>

    <?php if ($row['status'] == 'approved'): ?>
        <?php $hasNotification = true; ?>
        <a href="?page=app_det&id=<?= $row['appointment_id'] ?>" class="notification-item d-flex align-items-center p-2 border-bottom unread text-muted" style="text-decoration: none;">
            <i class="fas fa-calendar-check me-2 text-primary"></i>
            <div class="message small text-truncate">Your appointment has been approved.</div>
            <div class="time ms-auto text-muted small"><?= timeAgo($row['updated_at']) ?>
 </div>
        </a>
    <?php endif; ?>

<?php endwhile; ?>

<?php 
// If no notifications at all
if (!$hasNotification): 
?>
    <div class="p-3 text-center text-muted small">
        <i class="fas fa-bell-slash fa-lg d-block mb-2"></i>
        No notifications available
    </div>
<?php endif; ?>
<div class="p-3 text-center text-muted small">       
       <a href="?page=notification">See all notifications</a>
    </div>
            </div>
        </div>

        <div class="user-avatar">A</div>
        <div class="user-info text-end">
            <small><?php echo strtoupper($_SESSION['role']) ?></small>
            <div><?php echo strtoupper($_SESSION['fullname'])?></div>
        </div>
    </div>
</nav>

<div class="main-area">
    <div class="content-card">
        <?php include 'pages/'.strtolower($page_title).".php"; ?>
    </div>
</div>
</div>

<script>
const sidebar = document.getElementById('sidebar');
const menuToggle = document.getElementById('menuToggle');

menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    document.getElementById('mainContent').classList.toggle('expanded');
});

// Notification toggle
$('.notification-bell').on('click', function(e) {
    e.stopPropagation();
    $(this).siblings('.notification-dropdown').toggleClass('show');
});

// Click outside to close
$(document).click(function(event) { 
    if(!$(event.target).closest('.notification-wrapper').length) {
        $('.notification-dropdown').removeClass('show');
    }        
});


</script>

</body>
</html>
