<?php 
session_start();
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IATMS Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
body {
    background: #f5f7fa;
    font-family: "Segoe UI", sans-serif;
}

.top-header {
    background: #ffffff;
    border-bottom: 1px solid #e4e4e4;
    padding: 12px 20px;
}

#sidebar {
    min-height: 100vh;
    background: #ffffff;
    border-right: 1px solid #e4e4e4;
    transition: all 0.3s;
}

#sidebar .nav-link {
    color: #4a4a4a;
    padding: 12px 18px;
    border-radius: 8px;
    margin: 5px 10px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

#sidebar .nav-link:hover,
#sidebar .nav-link.active {
    background: #e8efff;
    color: #1a73e8;
}

#sidebar .accordion-button {
    background: none;
    color: #4a4a4a;
    font-weight: 500;
    padding: 12px 18px;
    border-radius: 8px;
    margin: 5px 10px;
}

#sidebar .accordion-button:not(.collapsed) {
    color: #1a73e8;
    background: #e8efff;
}

#main-content {
    padding: 25px;
    flex-grow: 1;
}

/* Sidebar toggle for small screens */
@media(max-width: 992px) {
    #sidebar {
        position: fixed;
        width: 250px;
        left: -300px;
        top: 0;
        z-index: 999;
        height: 100%;
        overflow-y: auto;
        box-shadow: 2px 0 8px rgba(0,0,0,0.1);
    }

    #sidebar.show {
        left: 0;
    }

    #main-content {
        padding: 20px;
    }
}

/* Smooth accordion */
.accordion-button::after {
    transition: transform 0.3s;
}
</style>
</head>

<body>

<!-- HEADER -->
<nav class="navbar top-header shadow-sm">
    <div class="container-fluid d-flex align-items-center">
        <button class="btn btn-outline-primary d-lg-none me-2" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
        <span class="navbar-brand fw-bold text-primary">IATMS Dashboard</span>
        <div class="ms-auto d-flex align-items-center">
            <span class="me-3 fw-semibold">Hello, <?= $_SESSION['fullname'] ?></span>
            <a href="query/logout.php" class="btn btn-primary btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="d-flex">
    <!-- SIDEBAR -->
    <div class="col-lg-2 p-0" id="sidebar">
        <h6 class="p-3 text-secondary">Navigation</h6>
        <nav class="nav flex-column px-2">

            <a class="nav-link <?= $page=='dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
 <?php if($_SESSION['role']=='admin'): ?>
            <div class="accordion" id="sidebarAccordion">

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#usersMenu">
                            <i class="bi bi-people"></i> Users
                        </button>
                    </h2>
                    <div id="usersMenu" class="accordion-collapse collapse <?= in_array($page,['students','alumni','staff']) ? 'show' : '' ?>">
                        <div class="accordion-body py-2 px-3">
                            <a class="nav-link <?= $page=='students' ? 'active' : '' ?>" href="?page=students"><i class="bi bi-person-fill"></i> Students</a>
                            <a class="nav-link <?= $page=='alumni' ? 'active' : '' ?>" href="?page=alumni"><i class="bi bi-mortarboard"></i> Alumni</a>
                            <a class="nav-link <?= $page=='staff' ? 'active' : '' ?>" href="?page=staff"><i class="bi bi-person-gear"></i> Staff</a>
                        </div>
                    </div>
                </div>

            </div>
<?php endif; ?>
            <?php if($_SESSION['role']=='alumni' ): ?>
            <a class="nav-link <?= $page=='manage_alumni_info' ? 'active' : '' ?>" href="?page=manage_alumni_info">
                <i class="bi bi-journal-text"></i> Manage Alumni Career
            </a>
<?php endif; ?>
<?php if($_SESSION['role'] == 'student'): ?>
                <a class="nav-link <?= $page=='student_request' ? 'active' : '' ?>" href="?page=student_request">
                <i class="bi bi-envelope-check"></i> Pictorial Request
            </a>
    <?php endif; ?>
<?php if($_SESSION['role']=='admin' || $_SESSION['role']=='staff' ): ?>


            <a class="nav-link <?= $page=='manage_student_request' ? 'active' : '' ?>" href="?page=manage_student_request">
                <i class="bi bi-envelope-check"></i> Manage Student Requests
            </a>

            <a class="nav-link <?= $page=='manage_event' ? 'active' : '' ?>" href="?page=manage_event">
                <i class="bi bi-calendar-event"></i> Manage Events
            </a>
<?php endif; ?>
            <a class="nav-link <?= $page=='event' || $page=='participant_list' ? 'active' : '' ?>" href="?page=event">
                <i class="bi bi-calendar-event"></i> Events
            </a>
            <?php if($_SESSION['role']=='admin'): ?>
            <div class="accordion-item border-0 mt-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#reportsMenu">
                        <i class="bi bi-file-earmark-bar-graph"></i> Reports
                    </button>
                </h2>
                <div id="reportsMenu" class="accordion-collapse collapse <?= in_array($page,['report_records','report_career','report_participation','report_contribution','report_student']) ? 'show' : '' ?>">
                    <div class="accordion-body py-2 px-3">
                        <a class="nav-link  <?= $page=='report_records' ? 'active' : '' ?>" href="?page=report_records"><i class="bi bi-list-ul"></i> List of Alumni Records</a>
                        <a class="nav-link <?= $page=='report_career' ? 'active' : '' ?>" href="?page=report_career"><i class="bi bi-bar-chart-line"></i> Career & Achievement Reports</a>
                        <a class="nav-link <?= $page=='report_participation' ? 'active' : '' ?>" href="?page=report_participation"><i class="bi bi-people-fill"></i> Event Participation Reports</a>
                        <a class="nav-link <?= $page=='report_contribution' ? 'active' : '' ?>" href="?page=report_contribution"><i class="bi bi-heart-pulse"></i> Contribution Summary</a>

                        <div class="accordion mt-2" id="studentDetailsAccordion">
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#studentDetailsMenu">
                                        <i class="bi bi-person-lines-fill"></i> Student Details
                                    </button>
                                </h2>
                                <div id="studentDetailsMenu" class="accordion-collapse collapse <?= in_array($page,['report_student']) ? 'show' : '' ?>">
                                    <div class="accordion-body py-2 px-3">
                                        <a class="nav-link <?= $page=='report_student' ? 'active' : '' ?>" href="?page=report_student"><i class="bi bi-book"></i> Courses</a>
                                        <a class="nav-link  <?= $page=='report_student_request' ? 'active' : '' ?>" href="?page=report_student_request"><i class="bi bi-book"></i> Pictorial Requests</a>
                         
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
<?php endif; ?>
            <a class="nav-link mt-2" href="?page=profile">
                <i class="bi bi-person"></i> <?= ucwords($_SESSION['role']) ?> Profile
            </a>

        </nav>
    </div>

    <!-- MAIN CONTENT -->
    <div id="main-content" class="flex-grow-1 p-4">
        <?php include_once("./pages/{$page}.php"); ?>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("show");
}
</script>

</body>
</html>
