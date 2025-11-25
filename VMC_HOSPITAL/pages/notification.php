   <?php 
   include 'connection.php';
   if($_SESSION['role'] == 'patient'){
         $appointment_query = "
   SELECT a.*, 
           CONCAT(d.firstname,' ',d.lastname) AS doctor_name,                        
           a.professional_fee,
           a.rejection_reason
    FROM appointments a 
    INNER JOIN users d ON a.doctor_id = d.user_id
    WHERE a.patient_id = {$_SESSION['user_id']}
    ORDER BY a.updated_at DESC
";

   }
   if($_SESSION['role'] == 'doctor'){
               $appointment_query = "
   SELECT a.*, 
           CONCAT(d.firstname,' ',d.lastname) AS doctor_name,                        
           a.professional_fee,
           a.rejection_reason
    FROM appointments a 
    INNER JOIN users d ON a.patient_id = d.user_id
    WHERE a.doctor_id = {$_SESSION['user_id']}
    ORDER BY a.updated_at DESC
";
   }
   if($_SESSION['role'] == 'admin'){
            $appointment_query = "
   SELECT a.*, 
           CONCAT(d.firstname,' ',d.lastname) AS doctor_name,                        
           a.professional_fee,
           a.rejection_reason
    FROM appointments a 
    INNER JOIN users d ON a.doctor_id = d.user_id
    ORDER BY a.updated_at DESC
";
   }
        ?>
<div class="notification-page">
    <h4 class="mb-4">Notifications</h4>

    <!-- Filter options -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <button class="btn btn-primary btn-sm" id="markAllRead">Mark All as Read</button>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="filterType">
                <option value="">All Types</option>
                <option value="appointment-approved">Appointment Approved</option>
                <option value="appointment-rejected">Appointment Rejected</option>
                <option value="new-doctor">New Doctor Added</option>
            </select>
            <select class="form-select form-select-sm" id="filterStatus">
                <option value="">All Status</option>
                <option value="unread">Unread</option>
                <option value="read">Read</option>
            </select>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="list-group" id="notificationList">
     <?php
     $list = $conn->query($appointment_query);
     while($row = $list->fetch_assoc()){
        $is_read = $row['is_read']=="0" ? "unread" : "";
        if($row['status'] == 'approved'){
            $type = 'appointment-approved';
            $icon = 'fa-calendar-check text-primary';
            $message = 'Your appointment has been approved.';
        } elseif($row['status'] == 'declined'){
            $type = 'appointment-rejected';
            $icon = 'fa-calendar-times text-danger';
            $message = 'Your appointment was rejected.';
        } else {
            continue; // Skip other statuses
        }
     ?>
        <div class="list-group-item d-flex align-items-center justify-content-between border mb-1 <?= $is_read ?>" data-type="appointment-approved">
            <div class="d-flex align-items-center gap-3">
                <i class="fas <?= $icon  ?>"></i>
                <div>
                    <div class="message"> <?=  $message ?></div>
                   
                    <small class="text-muted"><?=  timeAgo($row['updated_at']); ?></small>
                </div>
            </div>
            <a href="?page=app_det&id=<?=  $row['appointment_id'] ?>" class="btn btn-sm btn-outline-secondary mark-read">Mark as Read</a>
        </div>       
<?php
}
?>
     

    </div>
</div>

<style>
.notification-page h4 { font-weight:600; }
.list-group-item.unread { background: #eaf1ff; }
.list-group-item.read { background: #fff; }
.mark-read { font-size:0.8rem; }
</style>

<script>
$(document).ready(function() {
    // Toggle read/unread
    $('.mark-read').on('click', function() {
        var item = $(this).closest('.list-group-item');
        if(item.hasClass('unread')){
            item.removeClass('unread').addClass('read');
            $(this).text('Mark as Unread');
        } else {
            item.removeClass('read').addClass('unread');
            $(this).text('Mark as Read');
        }
    });

    // Mark all as read
    $('#markAllRead').on('click', function() {
        $('.list-group-item.unread').removeClass('unread').addClass('read');
        $('.mark-read').text('Mark as Unread');
    });

    // Filter notifications
    $('#filterType, #filterStatus').on('change', function() {
        var type = $('#filterType').val();
        var status = $('#filterStatus').val();
        $('.list-group-item').show().filter(function() {
            return (type && $(this).data('type') !== type) || 
                   (status && !$(this).hasClass(status));
        }).hide();
    });
});
</script>
