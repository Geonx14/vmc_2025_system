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
        <h3 class="fw-bold text-primary m-0">Manage Events</h3>
        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#eventModal" id="btnAddEvent">
            + Create Event
        </button>
    </div>

    <!-- Events Table -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="eventsTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Event Title</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
<?php 
$events = $conn->query("SELECT * FROM events ORDER BY event_date_start DESC");

 while($row = $events->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['event_id']; ?></td>
            <td class="e-title"><?php echo htmlspecialchars($row['event_title']); ?></td>
            <td class="e-desc"><?php echo htmlspecialchars($row['event_desc']); ?></td>
            <td class="e-start"><?php echo $row['event_date_start']; ?></td>
            <td class="e-end"><?php echo $row['event_date_end']; ?></td>
            <td>
                <button class="btn btn-sm btn-primary btnEditEvent">Edit</button>
                <button class="btn btn-sm btn-danger btnDeleteEvent">Delete</button>
            </td>
        </tr>
    <?php endwhile;

?>
    
             
            </tbody>
        </table>
    </div>
</div>

<!-- NEW / EDIT EVENT MODAL -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="btnSaveEvent">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="eventModalTitle">Create New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <input type="hidden" name="event_id" id="event_id">
            <div class="modal-body">
                <div class="mb-3">
                    <label>Event Title</label>
                    <input type="text" class="form-control" id="event_title" name="event_title">
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea class="form-control" id="event_desc" name="event_desc" rows="3"></textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Start Date</label>
                        <input type="date" class="form-control" id="event_start" name="event_date_start">
                    </div>
                    <div class="col-md-6">
                        <label>End Date</label>
                        <input type="date" class="form-control" id="event_end" name="event_date_end">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Event</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    let editingEvent = null;
$table = $("#eventsTable").dataTable() ;
    // Add new event
    $("#btnAddEvent").on("click", function() {
        $("#eventModalTitle").text("Create New Event");
        $("#event_title, #event_desc, #event_start, #event_end").val('');
        editingEvent = null;
    });


    $("#btnSaveEvent").on("submit", function(e) {
        e.preventDefault();
$.ajax({
    url: 'query/manage_event.php',
    method: 'POST',
    data:$(this).serialize(),
    success:function(resp){
        if(resp == 1){
            alert("Event successfully added.");
            location.reload();
        }else if(resp == 2){
            alert("Event successfully updated.");
            location.reload();
        }
    }
})
    });  
    // // Save event
    // $("#btnSaveEvent").on("click", function() {
    //     let title = $("#event_title").val();
    //     let desc = $("#event_desc").val();
    //     let start = $("#event_start").val();
    //     let end = $("#event_end").val();

    //     if (!title || !desc || !start || !end) {
    //         alert("Please fill in all fields.");
    //         return;
    //     }

    // });

    // Edit event
    $(document).on("click", ".btnEditEvent", function() {
        editingEvent = $(this).closest("tr");
        $("#event_id").val(editingEvent.find("td:first").text());
        $("#event_title").val(editingEvent.find(".e-title").text());
        $("#event_desc").val(editingEvent.find(".e-desc").text());
        $("#event_start").val(editingEvent.find(".e-start").text());
        $("#event_end").val(editingEvent.find(".e-end").text());
        $("#eventModalTitle").text("Edit Event");
        $("#eventModal").modal("show");
    });

    // Delete event
    $(document).on("click", ".btnDeleteEvent", function() {
        if (confirm("Are you sure you want to delete this event?")) {   

            var event_id = $(this).closest("tr").find("td:first").text();
            $.ajax({
                url: 'query/delete_event.php',
                method: 'POST',
                data: { event_id: event_id },
                success: function(resp) {         
                    if (resp == 1) {
                        alert("Event successfully deleted.");
                        location.reload();
                    } else {
                        alert("Failed to delete event.");
                    }
                }
            });
        }
    });
</script>
