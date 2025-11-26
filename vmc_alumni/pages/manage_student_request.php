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

    .status-approved {
        color: #198754;
        font-weight: 600;
    }

    .status-rejected {
        color: #dc3545;
        font-weight: 600;
    }

    .status-pending {
        color: #ffc107;
        font-weight: 600;
    }

    .btn-approve {
        background: #198754;
        color: white;
    }

    .btn-approve:hover {
        background: #146c43;
    }

    .btn-reject {
        background: #dc3545;
        color: white;
    }

    .btn-reject:hover {
        background: #a71d2a;
    }
</style>

<body>
<div class="content-box">
    <h3 class="fw-bold text-primary mb-4">Manage Student Graduation Requests</h3>
    <div class="filter-box row g-3">
        <div class="col-md-3">
            <label class="fw-semibold">Filter by Status</label>
            <select id="filterStatus" class="form-control">
                <option value="">All</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
                <option value="Pending">Pending</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="fw-semibold">Schedule Date From</label>
            <input type="date" id="dateFrom" value="<?= date("Y-m-d") ?>" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="fw-semibold">Schedule Date To</label>
            <input type="date" id="dateTo"  value="<?= date("Y-m-d") ?>"  class="form-control">
        </div>
        

    </div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="manageRequestTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Requested Date</th>
                       <th>Schedule Date</th>
                    <th>Status</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $request_list = $conn->query("SELECT * FROM `graduation_requests` g join users u on g.student_id=u.user_id left join st_course c on u.user_id=c.student_id order by g.request_date desc");
                while($row = $request_list->fetch_assoc()):
                ?>
             <tr data-id="<?php echo $row['request_id'] ?>"  >
                <td><?php echo $row['request_id'] ?></td>
                <td><?php echo $row['firstname'].' '.$row['lastname'] ?></td>
                <td><?php echo $row['course'] ?></td>
                  <td><?php echo date("M d, Y", strtotime($row['created_at'])) ?></td>
                <td><?php echo date("M d, Y", strtotime($row['request_date'])) ?></td>
           <td class="s-status 
    <?php 
        if ($row['status'] == 'approved') {
            echo 'status-approved';
        } elseif ($row['status'] == 'rejected') {
            echo 'status-rejected';
        } else {
            echo 'status-pending';
        }
    ?>
">
    <span class="badge 
        <?php 
            if ($row['status'] == 'approved') {
                echo 'status-approved';
            } elseif ($row['status'] == 'rejected') {
                echo 'status-rejected';
            } else {
                echo 'status-pending';
            }
        ?>
    ">
        <?php echo $row['status']; ?>
    </span>
</td>

                <td>
                    <button class="btn btn-approve btn-sm btnApprove" <?php echo ($row['status'] != 'pending') ? 'disabled' : '' ?>>Approve</button>
                    <button class="btn btn-reject btn-sm btnReject" <?php echo ($row['status'] != 'pending') ? 'disabled' : '' ?>>Reject</button>
                </td>
                </tr>                                    
                <?php endwhile; ?>
                      
            </tbody>
        </table>
    </div>
</div>

<script>
    var table = $("#manageRequestTable").DataTable({
        responsive: true,
        pageLength: 10,
        lengthChange: false
    });

    $("#filterStatus").on("change", function () {
    table.column(5).search(this.value).draw();
});

$.fn.dataTable.ext.search.push(function (settings, data) {
    let min = $('#dateFrom').val();
    let max = $('#dateTo').val();

    // Table date (ex: "Feb 03, 2025")
    let scheduleText = data[4];

    // Convert table date → YYYY-MM-DD
    let scheduleDate = new Date(scheduleText);
    let schedule = scheduleDate.toISOString().split('T')[0];

    // If no filters → show all
    if (!min && !max) return true;

    // Compare
    if ((min && schedule < min) || (max && schedule > max)) {
        return false;
    }
    return true;
});

$("#dateFrom, #dateTo").on("change", function () {
    table.draw();
});

    $(document).on("click", ".btnApprove", function(){
        let row = $(this).closest("tr");
let requestId = row.data("id");
$.ajax({
    url: 'query/validate_request.php',
    method: 'POST',
    data: {
        request_id: requestId,
        status: 'Approved'
    },
    success: function(response) {
        if (response.trim() === "1") {
          alert("Request approved successfully.");
         window.location.reload();
        } else {
            alert("Failed to update request status.");
                  window.location.reload();
        }
    },
    });
});

   
    $(document).on("click", ".btnReject", function() {
        let row = $(this).closest("tr");       
let requestId = row.data("id");
alert(requestId);
$.ajax({
    url: 'query/validate_request.php',
    method: 'POST',
    data: {
        request_id: requestId,
        status: 'Rejected'
    },
    success: function(response) {
        if (response.trim() === "1") {
          alert("Request Rejected.");
         window.location.reload();
        } else {
            alert("Failed to update request status.");
                  window.location.reload();
        }
    },
    });
    });
</script>
