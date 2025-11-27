<?php
include 'connection.php';
$event_id=$_GET['event_id'];
$event_info = $conn->query("select * from events where event_id=$event_id")->fetch_assoc();
$participant_list = $conn->query("select *,u.user_id as uid, ifnull(e.status,'JOINING') as stat from users u left join st_course st on u.user_id=st.student_id LEFT JOIN event_participants e on u.user_id=e.user_id and event_id = $event_id  where user_type in ('alumni', 'student')")
?>
<div class="card mb-4">
    <div class="card-body">
        <h4 class="card-title fw-bold">Event Information</h4>
        <p class="mb-1"><strong>Event Name: </strong><?= ucwords($event_info['event_title']) ?></p>
        <p class="mb-1"><strong>Date:</strong>  <?= date('M d, Y', strtotime($event_info['event_date_start'])) ?> - <?= date('M d, Y', strtotime($event_info['event_date_end'])) ?></p>

    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">User Type</label>
                <select class="form-select" id="filterType">
                 
                    <option value="">All</option>
                    <option value="student">Student</option>
                    <option value="alumni">Alumni</option>
     
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Course</label>
                <select class="form-select" id="filterCourse">
                     <option value="" selected>All</option>
                     
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
            </div>
        </div>
    </div>
</div>

<!-- Participants Table -->
<div class="card">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Participants List</h5>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Name</th>
                        <th>User Type</th>
                        <th>Course</th>
                     
                        <th style="width: 120px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row = $participant_list->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['uid'] ?></td>
                        <td><?= $row['firstname']." ". $row['middlename']." ". $row['lastname'] ?></td>
                        <td><?= $row['user_type'] ?></td>
                        <td><?= $row['course'] ?></td>
                        <td>
                          <button class="btn <?= ($row['stat'] == "JOINING")?'btn-warning':'btn-success' ?> btn-sm btn-join" data-id="<?=  $row['uid'] ?>" ><?= $row['stat'] ?></button>
                        </td>
                    </tr>
                   <?php endwhile;?>
                </tbody>
            </table>
        </div>
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

        $("#filterType").change(function(){
  table.column(2).search(this.value).draw();
    })
    $(".btn-join").click(function(){
        var user_id = $(this).data('id');
        var status  = $(this).html() == "JOINING"?"JOINED":"JOINING";
        var event_id = <?=  $event_id; ?>;

$.ajax({
    url:'query/participant_join.php',
    method:'post',
    data:{user_id:user_id, event_id:event_id, status:status},
    success:function(data){
  
if(status == 'JOINED')    {
$(this).html("JOINED");
$(this).removeClass("btn-warning");
$(this).addClass("btn-success");
    }
    else{


$(this).html("JOINING");
$(this).removeClass("btn-success");
$(this).addClass("btn-warning");

    }
       
    }
})
    

    })
</script>