<?php
include 'connection.php';
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

.table thead {
    background: #f0f2f5;
}

.table thead th {
    font-weight: 600;
    color: #555;
}

.filter-group {
    margin-bottom: 20px;
}

.filter-group select {
    margin-right: 10px;
    padding: 5px 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.table img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
</style>

<div class="content-box">
    <h3 class="fw-bold text-primary mb-4">Alumni Event Participation Report</h3>
       

    <div class="filter-group">

     <select id="filterType">
            <option value="">All Types</option>
            <option value="alumni">Alumni</option>
            <option value="student">Student</option>
         
        </select>
        <select id="filterCourse">
            <option value="">All Courses</option>
           
                                <optgroup label="Information Technology & Computing">
                                    <option value="BSIT">BS Information Technology (BSIT)</option>
                                    <option value="BSCS">BS Computer Science (BSCS)</option>
                                    <option value="BSCpE">BS Computer Engineering (BSCpE)</option>
                                    <option value="BSIS">BS Information Systems (BSIS)</option>
                                </optgroup>

                                <optgroup label="Business & Management">
                                    <option value="BSBA">BS Business Administration (BSBA)</option>
                                    <option value="BSA">BS Accountancy (BSA)</option>
                                    <option value="BSMA">BS Management Accounting (BSMA)</option>
                                    <option value="BSTM">BS Tourism Management (BSTM)</option>
                                    <option value="BHM">BS Hospitality Management (BHM)</option>
                                </optgroup>

                                <optgroup label="Education">
                                    <option value="BEEd">Bachelor of Elementary Education (BEEd)</option>
                                    <option value="BSEd-English">BSEd Major in English</option>
                                    <option value="BSEd-Math">BSEd Major in Mathematics</option>
                                    <option value="BPEd">Bachelor of Physical Education (BPEd)</option>
                                </optgroup>

                                <optgroup label="Engineering">
                                    <option value="BSCE">BS Civil Engineering (BSCE)</option>
                                    <option value="BSEE">BS Electrical Engineering (BSEE)</option>
                                    <option value="BSME">BS Mechanical Engineering (BSME)</option>
                                    <option value="BSECE">BS Electronics Engineering (BSECE)</option>
                                </optgroup>

                                <optgroup label="Health & Allied Programs">
                                    <option value="BSN">BS Nursing (BSN)</option>
                                    <option value="BSP">BS Pharmacy (BSP)</option>
                                    <option value="BSMT">BS Medical Technology (BSMT)</option>
                                    <option value="BSPsych">BS Psychology (BS Psych)</option>
                                </optgroup>

                                <optgroup label="Public Safety">
                                    <option value="BSCrim">BS Criminology (BSCrim)</option>
                                </optgroup>

                                <optgroup label="Arts & Sciences">
                                    <option value="BSPA">BS Public Administration</option>
                                    <option value="ABComm">AB Communication</option>
                                    <option value="ABPolSci">AB Political Science</option>
                                </optgroup>
        </select>


        <select id="filterEvent">
            <option value="">All Events</option>
            <?php
            $event_list_group = $conn->query("select event_title from events group by event_title");
            while($row = $event_list_group->fetch_assoc()):
            ?>
            <option value="<?= $row['event_title'] ?>"><?= $row['event_title'] ?></option>
            <?php endwhile; ?>
       
        </select>

    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="eventTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Avatar</th>
                    <th>Full Name</th>
                    <th>Course</th>
                    <th>User Type</th>
                    <th>Event Name</th>
                    <th>Date Participated</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $list_participant = $conn->query("SELECT * FROM `users` u left join st_course st on u.user_id=st.student_id JOIN event_participants e on u.user_id = e.user_id join events ev on e.event_id=ev.event_id");
                while($row = $list_participant->fetch_assoc()):
                                ?>
                <tr data-course="BSIT" data-year="2023" data-event="Graduation Pictorial">
                    <td><?= $row['user_id'] ?></td>
                    <td>  <img src="query/uploads/avatars/<?= $row['avatar'] ?>" width="40" height="40" style="border-radius:50%;">
                    </td>
                    <td><?= $row['firstname']." ". $row['middlename']." ". $row['lastname'] ?></td>
                    <td><?= $row['course'] ?></td>
                    <td><?= $row['user_type'] ?></td>
                    <td><?= ucwords($row['event_title']) ?></td>
                    <td><?= $row['date_participated'] ?></td>
                </tr>
                <?php endwhile; ?>

            </tbody>
        </table>
    </div>
</div>

<script>
    
 var table = $(".table").DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false
    });

    $("#filterCourse").change(function(){
  table.column(3).search(this.value).draw();
    })
    
    $("#filterEvent").change(function(){
  table.column(5).search(this.value).draw();
    }) 
    
    $("#filterType").change(function(){
  table.column(4).search(this.value).draw();
    })
  
</script>
