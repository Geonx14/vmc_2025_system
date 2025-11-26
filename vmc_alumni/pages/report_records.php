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

input.search-box, select.filter-course {
    border-radius: 10px;
    padding: 5px 10px;
    margin-right: 10px;
}
</style>

<div class="content-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary m-0">Alumni Records</h3>
    </div>

    <div class="d-flex mb-3">
       
        <select id="filterCourse" class="filter-course">
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
    
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="alumniTable">
             <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Full Name</th>                   
                    <th>Birthday</th>
                    <th>Course</th>
                    <th>Year Graduated</th>
                  
                    
                </tr>
            </thead>

            <tbody>
                <?php
                $students = $conn->query("
                    SELECT * FROM users u 
                    LEFT JOIN st_course c ON u.user_id = c.student_id 
                    WHERE user_type='alumni'
                    ORDER BY lastname ASC
                ");

                while ($row = $students->fetch_assoc()):
                ?>
                <tr>
                    <td class="s-id"><?= $row['user_id'] ?></td>

                    <td class="s-avatar">
                        <?php if ($row['avatar']): ?>
                            <img src="query/uploads/avatars/<?= $row['avatar'] ?>" width="40" height="40" style="border-radius:50%;">
                        <?php endif; ?>
                    </td>

                    <td class="s-firstname"><?= $row['firstname']." ".$row['middlename']." ". $row['lastname'] ?></td>
        
                    <td class="s-birthday"><?= $row['birthday'] ?></td>

                    <td class="s-course"><?= $row['course'] ?></td>
                    <td class="s-year_graduated"><?= $row['year_graduated'] ?></td>
                  
                    
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
  table.column(4).search(this.value).draw();
    })
// Search function
document.getElementById("searchAlumni").addEventListener("keyup", function(){
    let query = this.value.toLowerCase();
    document.querySelectorAll("#alumniTable tbody tr").forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(query) ? "" : "none";
    });
});

// Filter by course
document.getElementById("btnFilter").addEventListener("click", function(){
    let courseFilter = document.getElementById("filterCourse").value;
    document.querySelectorAll("#alumniTable tbody tr").forEach(tr => {
        let course = tr.cells[2].textContent;
        tr.style.display = (courseFilter === "" || course === courseFilter) ? "" : "none";
    });
});
</script>
