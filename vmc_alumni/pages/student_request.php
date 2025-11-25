<?php include 'connection.php'; ?>
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

    .btn-add {
        background: #1a73e8;
        color: white;
        border-radius: 8px;
        padding: 8px 14px;
    }

    .btn-add:hover {
        background: #0f56b3;
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

    .modal-content {
        border-radius: 14px;
    }

    label {
        font-weight: 500;
    }
</style>

<body>

<div class="content-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary m-0">Graduation Pictorial Requests</h3>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#requestModal" id="btnAddRequest">
            + Request Schedule
        </button>
    </div>

    <!-- Requests Table -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="requestTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $request = $conn->query("select * from graduation_requests where student_id  = '{$_SESSION['user_id']}' order by request_date desc");
                while($row=$request->fetch_assoc()):
                ?>
                <tr data-id="<?= $row['request_id'] ?>">
                    <td><?= $row['request_id'] ?></td>
                    <td class="r-date"><?= date("Y-m-d", strtotime($row['request_date'])) ?></td>
                    <td class="r-status 
                        <?php 
                            if($row['status'] == 'Approved'){
                                echo 'status-approved';
                            } elseif($row['status'] == 'Rejected'){
                                echo 'status-rejected';
                            } else {
                                echo 'status-pending';
                            }
                        ?>
                    ">
                        <?= $row['status'] ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary btnEditRequest" 
                            <?= $row['status'] != 'pending' ? 'disabled' : '' ?>
                        >
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger btnDeleteRequest" 
                            <?= $row['status'] != 'pending' ? 'disabled' : '' ?>
                        >
                            Delete
                        </button>
                    </td>
                <?php endwhile; ?>
                
            </tbody>
        </table>
    </div>
</div>

<!-- NEW / EDIT REQUEST MODAL -->
<div class="modal fade" id="requestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="requestModalTitle">Request Graduation Pictorial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label>Select Desired Date</label>
                    <input type="date" class="form-control" id="r_date_input">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="btnSubmitRequest">Save Request</button>
            </div>
        </div>
    </div>
</div>

<script>
    let editingRow = null;
var table = $("#requestTable").dataTable();
    // Add new request
    $("#btnAddRequest").on("click", function() {
        $("#requestModalTitle").text("Request Graduation Pictorial");
        $("#r_date_input").val("");
        editingRow = null;
    });

    $("#btnSubmitRequest").on("click", function() {
        let date = $("#r_date_input").val();
        if (!date) {
            alert("Please select a date for your pictorial request.");
            return;
        }
        $.ajax({
            url: "query/manage_request.php",
            type: "POST",
            data: {
                request_id: editingRow ? editingRow.data("id") : '',
                user_id: '<?= $_SESSION['user_id'] ?>',
                schedule: date
            },
            success:function(response){
      
                if(response.trim() == "1"){
                    // New request added
                alert("Request submitted successfully!"+response);     
                
             window.location.reload();
            }
        }
        })
            });

    // Edit request
    $(document).on("click", ".btnEditRequest", function() {
        editingRow = $(this).closest("tr");
        let date = editingRow.find(".r-date").text();
        $("#r_date_input").val(date);
        $("#requestModalTitle").text("Edit Graduation Request");
        $("#requestModal").modal("show");
    });

    // Delete request
    $(document).on("click", ".btnDeleteRequest", function() {
        if (confirm("Are you sure you want to delete this request?")) {
            $(this).closest("tr").remove();
var request_id = $(this).closest("tr").data("id");
            $.ajax({
                url: "query/delete_request.php",
                type: "POST",
                data: { request_id: request_id },
                success:function(response){
                    if(response.trim() == "1"){
                        alert("Request deleted successfully!");
                        window.location.reload();
                    }
                }
            });
        }
    });
</script>
