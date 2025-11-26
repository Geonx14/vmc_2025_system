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

.total-summary {
    margin-top: 20px;
    font-weight: 600;
    color: #1a73e8;
}
.contribution-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    .contribution-list li {
        padding: 6px 0;
        border-bottom: 1px solid #f1f1f1;
        font-size: 0.95rem;
    }

    .contrib-date {
        font-weight: bold;
        color: #555;
    }

    .contrib-type {
        margin-left: 5px;
        margin-right: 5px;
        font-size: 0.85rem;
    }

    .contribution-list li:last-child {
        border-bottom: none;
    }

    .table-hover tbody tr:hover {
        background-color: #f9f9f9;
    }
</style>

<div class="content-box">
    <h3 class="fw-bold text-primary mb-4">Alumni Contribution Summary Report</h3>

    <!-- Filters -->
    <div class="filter-group">
        <select id="filterYear">
            <option value="">All Years</option>
          <?php
        $startYear = 1970;
        $endYear = 2025;

        for ($year = $startYear; $year <= $endYear; $year++) {
            echo "<option value=\"$year\">$year</option>";
        }
    ?>
        </select>

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

        <select id="filterType">
            <option value="">All Contribution Types</option>
      <?php
      $contribution_list  = $conn->query("SELECT contribution_type FROM `alumni_contributions` group by contribution_type ");
      while($row = $contribution_list->fetch_assoc()){
        ?>
         <option value="<?=  $row['contribution_type'] ?>"><?=  $row['contribution_type'] ?></option>
        <?php
      }
      ?>
        </select>

       
    </div>

    <!-- Contributions Table -->
    <div class="table-responsive">
     <table class="table table-hover table-bordered align-middle" id="contributionTable">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Avatar</th>
            <th>Full Name</th>                              
            <th>Course</th>
            <th>Year Graduated</th>
            <th>Contribution Info</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $students = $conn->query("
            SELECT * FROM users u 
            LEFT JOIN st_course c ON u.user_id = c.student_id 
            WHERE user_type='alumni'
            ORDER BY year_graduated DESC, lastname ASC
        ");

        while ($row = $students->fetch_assoc()):
        ?>
        <tr>
            <td class="s-id"><?= $row['user_id'] ?></td>

            <td class="s-avatar">
                <?php if ($row['avatar']): ?>
                    <img src="query/uploads/avatars/<?= $row['avatar'] ?>" width="40" height="40" style="border-radius:50%;">
                <?php else: ?>
                    <span class="text-muted">No Avatar</span>
                <?php endif; ?>
            </td>

            <td class="s-fullname"><?= htmlspecialchars($row['firstname'] . " " . ($row['middlename'] ? $row['middlename'] . " " : "") . $row['lastname']) ?></td>

            <td class="s-course"><?= htmlspecialchars($row['course']) ?></td>
            <td class="s-year_graduated"><?= $row['year_graduated'] ?></td>

            <td class="s-contributions">
                <?php
                $list_contrib = $conn->query("
                    SELECT `contribution_type`, `contribution_desc`, `date_contributed` 
                    FROM `alumni_contributions`
                    WHERE alumni_id={$row['user_id']}
                    ORDER BY date_contributed DESC
                ");

                if ($list_contrib->num_rows > 0) {
                    echo '<ul class="contribution-list">';
                    while ($contrib = $list_contrib->fetch_assoc()) {
                        echo '<li>
                                <span class="contrib-date">' . date('M d Y', strtotime($contrib['date_contributed'])) . '</span> - 
                                <span class="contrib-type badge bg-primary">' . htmlspecialchars($contrib['contribution_type']) . '</span>: 
                                ' . htmlspecialchars($contrib['contribution_desc']) . '
                              </li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<span class="text-muted">No contributions yet</span>';
                }
                ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

    </div>

    <!-- Summary -->
    <div class="total-summary" id="totalSummary">
        Total Contributions: $1500
    </div>
</div>

<script>
var table = $(".table").DataTable({
            responsive: true,
        pageLength: 10,
        lengthChange: false
})

    $("#filterCourse").change(function(){
  table.column(3).search(this.value).draw();
    })
        $("#filterYear").change(function(){
  table.column(4).search(this.value).draw();
    })
$("#filterType").change(function(){
  table.column(5).search(this.value).draw();
})
</script>
