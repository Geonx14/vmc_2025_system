<?php
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Alumni Records</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DATATABLES -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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

        .filter-course {
            border-radius: 10px;
            padding: 5px 10px;
            margin-right: 10px;
        }

        /* PRINT STYLES */
        .print-title {
            display: none;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .print-title,
            #alumniTable,
            #alumniTable * {
                visibility: visible;
            }

            .print-title {
                display: block;
                text-align: center;
                margin-bottom: 15px;
            }

            #alumniTable {
                position: absolute;
                top: 50px;
                left: 0;
                width: 100%;
            }

            img {
                display: none;
            }

            th,
            td {
                font-size: 12px;
                padding: 6px;
            }
        }
    </style>
</head>

<body>

    <div class="content-box">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary m-0">Alumni Records</h3>
            <button onclick="printTable()" class="btn btn-outline-primary">
                🖨️ Print
            </button>
        </div>

        <!-- FILTER -->
        <div class="mb-3">
            <select id="filterCourse" class="filter-course">
                <option value="">All Courses</option>

                <optgroup label="Information Technology & Computing">
                    <option value="BSIT">BSIT</option>
                    <option value="BSCS">BSCS</option>
                    <option value="BSCpE">BSCpE</option>
                    <option value="BSIS">BSIS</option>
                </optgroup>

                <optgroup label="Business & Management">
                    <option value="BSBA">BSBA</option>
                    <option value="BSA">BSA</option>
                    <option value="BSMA">BSMA</option>
                    <option value="BSTM">BSTM</option>
                    <option value="BHM">BHM</option>
                </optgroup>

                <optgroup label="Education">
                    <option value="BEEd">BEEd</option>
                    <option value="BSEd-English">BSEd English</option>
                    <option value="BSEd-Math">BSEd Math</option>
                    <option value="BPEd">BPEd</option>
                </optgroup>
            </select>
        </div>

        <!-- PRINT TITLE -->
        <h4 class="print-title">Alumni Records</h4>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="alumniTable">
                <thead>
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
                            <td><?= $row['user_id'] ?></td>
                            <td>
                                <?php if ($row['avatar']): ?>
                                    <img src="query/uploads/avatars/<?= $row['avatar'] ?>" width="40" height="40" style="border-radius:50%;">
                                <?php endif; ?>
                            </td>
                            <td><?= $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'] ?></td>
                            <td><?= $row['birthday'] ?></td>
                            <td><?= $row['course'] ?></td>
                            <td><?= $row['year_graduated'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function printTable() {
            let table = document.getElementById("alumniTable").cloneNode(true);

            // Remove avatar column images
            table.querySelectorAll("img").forEach(img => img.remove());

            let printWindow = window.open("", "", "width=900,height=600");

            printWindow.document.write(`
        <html>
        <head>
            <title>Print Alumni Records</title>
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
            <h2>Alumni Records</h2>
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


</body>

</html>