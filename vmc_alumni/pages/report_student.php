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
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }

    .table thead {
        background: #f0f2f5;
    }

    .table thead th {
        font-weight: 600;
        color: #555;
    }

    input.search-box,
    select.filter-course,
    select.filter-year {
        border-radius: 10px;
        padding: 5px 10px;
        margin-right: 10px;
    }

    .career-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    .career-list li {
        padding: 6px 0;
        border-bottom: 1px solid #f1f1f1;
        font-size: 0.95rem;
    }

    .career-title {
        font-size: 0.85rem;
        margin-right: 4px;
    }

    .career-company {
        font-weight: bold;
    }

    .career-years {
        color: #555;
    }

    .career-list li:last-child {
        border-bottom: none;
    }

    .table-hover tbody tr:hover {
        background-color: #f9f9f9;
    }
</style>

<div class="content-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">Student Report</h3>
        <button onclick="printStudentReport()" class="btn btn-outline-primary">
            🖨️ Print
        </button>
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
        <table class="table table-bordered table-hover" id="studentTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Fullname</th>
                    <th>Birthday</th>
                    <th>Course</th>

                </tr>
            </thead>

            <tbody>
                <?php
                $students = $conn->query("
                    SELECT * FROM users u 
                    LEFT JOIN st_course c ON u.user_id = c.student_id 
                    WHERE user_type='Student'
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

                        <td class="s-firstname"><?= $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'] ?></td>
                        <td class="s-course"><?= $row['birthday'] ?></td>
                        <td class="s-course"><?= $row['course'] ?></td>


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

    $("#filterCourse").change(function() {
        table.column(3).search(this.value).draw();
    })

    $("#filterYear").change(function() {
        table.column(4).search(this.value).draw();
    })

    // Search function
    document.getElementById("searchCareer").addEventListener("keyup", function() {
        let query = this.value.toLowerCase();
        document.querySelectorAll("#careerTable tbody tr").forEach(tr => {
            tr.style.display = tr.textContent.toLowerCase().includes(query) ? "" : "none";
        });
    });

    // Filter function
    document.getElementById("btnFilterCareer").addEventListener("click", function() {
        let courseFilter = document.getElementById("filterCourse").value;
        let yearFilter = document.getElementById("filterYear").value;

        document.querySelectorAll("#careerTable tbody tr").forEach(tr => {
            let course = tr.cells[2].textContent;
            let year = tr.cells[3].textContent;
            let show = (courseFilter === "" || course === courseFilter) &&
                (yearFilter === "" || year === yearFilter);
            tr.style.display = show ? "" : "none";
        });
    });
</script>

<script>
    function printStudentReport() {

        // Clone the table
        let table = document.getElementById("studentTable").cloneNode(true);

        // Remove avatar column for print
        table.querySelectorAll("tr").forEach(row => {
            if (row.cells.length > 1) {
                row.deleteCell(1); // remove avatar column
            }
        });

        let printWindow = window.open("", "", "width=1000,height=700");

        printWindow.document.write(`
        <html>
        <head>
            <title>Student Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    color: #000;
                }
                h2 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 6px;
                    font-size: 12px;
                    text-align: left;
                }
                th {
                    background: #f2f2f2;
                }
            </style>
        </head>
        <body>
            <h2>Student Report</h2>
            ${table.outerHTML}
        </body>
        </html>
    `);

        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script>