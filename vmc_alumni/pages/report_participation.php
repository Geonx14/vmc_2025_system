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
        <select id="filterCourse">
            <option value="">All Courses</option>
            <option value="BSIT">BSIT</option>
            <option value="BSCS">BSCS</option>
            <option value="BSA">BSA</option>
            <option value="BSTM">BSTM</option>
        </select>

        <select id="filterYear">
            <option value="">All Years</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
            <option value="2021">2021</option>
            <option value="2020">2020</option>
        </select>

        <select id="filterEvent">
            <option value="">All Events</option>
            <option value="Graduation Pictorial">Graduation Pictorial</option>
            <option value="Alumni Homecoming">Alumni Homecoming</option>
            <option value="Career Webinar">Career Webinar</option>
        </select>

        <button id="btnFilter" class="btn btn-add">Filter</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="eventTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Avatar</th>
                    <th>Full Name</th>
                    <th>Course</th>
                    <th>Year Graduated</th>
                    <th>Event Name</th>
                    <th>Date Participated</th>
                </tr>
            </thead>
            <tbody>
                <tr data-course="BSIT" data-year="2023" data-event="Graduation Pictorial">
                    <td>1</td>
                    <td><img src="https://via.placeholder.com/40" alt="Avatar"></td>
                    <td>Juan Santos Cruz</td>
                    <td>BSIT</td>
                    <td>2023</td>
                    <td>Graduation Pictorial</td>
                    <td>2023-12-10</td>
                </tr>
                <tr data-course="BSCS" data-year="2022" data-event="Career Webinar">
                    <td>2</td>
                    <td><img src="https://via.placeholder.com/40" alt="Avatar"></td>
                    <td>Maria Lopez</td>
                    <td>BSCS</td>
                    <td>2022</td>
                    <td>Career Webinar</td>
                    <td>2022-11-15</td>
                </tr>
                <tr data-course="BSA" data-year="2021" data-event="Alumni Homecoming">
                    <td>3</td>
                    <td><img src="https://via.placeholder.com/40" alt="Avatar"></td>
                    <td>Carlos Dela Cruz</td>
                    <td>BSA</td>
                    <td>2021</td>
                    <td>Alumni Homecoming</td>
                    <td>2021-08-20</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
// Filter functionality
document.getElementById("btnFilter").onclick = () => {
    let course = document.getElementById("filterCourse").value;
    let year = document.getElementById("filterYear").value;
    let eventName = document.getElementById("filterEvent").value;

    document.querySelectorAll("#eventTable tbody tr").forEach(row => {
        let matchCourse = course === "" || row.dataset.course === course;
        let matchYear = year === "" || row.dataset.year === year;
        let matchEvent = eventName === "" || row.dataset.event === eventName;

        row.style.display = (matchCourse && matchYear && matchEvent) ? "" : "none";
    });
};
</script>
