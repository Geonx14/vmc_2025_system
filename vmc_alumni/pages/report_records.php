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
        <input type="text" id="searchAlumni" class="search-box" placeholder="Search alumni...">
        <select id="filterCourse" class="filter-course">
            <option value="">All Courses</option>
            <option value="BSIT">BS Information Technology</option>
            <option value="BSCS">BS Computer Science</option>
            <option value="BSA">BS Accountancy</option>
            <option value="BSTM">BS Tourism Management</option>
        </select>
        <button id="btnFilter" class="btn btn-outline-secondary">Filter</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="alumniTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Course</th>
                    <th>Email</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Juan Santos Cruz</td>
                    <td>BSIT</td>
                    <td>juan@example.com</td>
                    <td>09123456789</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Maria Lopez Reyes</td>
                    <td>BSA</td>
                    <td>maria@example.com</td>
                    <td>09234567890</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Pedro Ramos Diaz</td>
                    <td>BSCS</td>
                    <td>pedro@example.com</td>
                    <td>09345678901</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Ana Santos Cruz</td>
                    <td>BSTM</td>
                    <td>ana@example.com</td>
                    <td>09456789012</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
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
