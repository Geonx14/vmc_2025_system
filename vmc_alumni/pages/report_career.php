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

input.search-box, select.filter-course, select.filter-year {
    border-radius: 10px;
    padding: 5px 10px;
    margin-right: 10px;
}
</style>

<div class="content-box">
    <h3 class="fw-bold text-primary mb-4">Career & Achievement Report</h3>

    <div class="d-flex mb-3">
        <input type="text" id="searchCareer" class="search-box" placeholder="Search alumni...">
        <select id="filterCourse" class="filter-course">
            <option value="">All Courses</option>
            <option value="BSIT">BS Information Technology</option>
            <option value="BSCS">BS Computer Science</option>
            <option value="BSA">BS Accountancy</option>
            <option value="BSTM">BS Tourism Management</option>
        </select>
        <select id="filterYear" class="filter-year">
            <option value="">All Graduation Years</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
        </select>
        <button id="btnFilterCareer" class="btn btn-outline-secondary">Filter</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="careerTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Course</th>
                    <th>Graduation Year</th>
                    <th>Current Job/Position</th>
                    <th>Company</th>
                    <th>Achievements</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Juan Santos Cruz</td>
                    <td>BSIT</td>
                    <td>2020</td>
                    <td>Software Engineer</td>
                    <td>ABC Tech</td>
                    <td>Employee of the Year 2022</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Maria Lopez Reyes</td>
                    <td>BSA</td>
                    <td>2021</td>
                    <td>Accountant</td>
                    <td>XYZ Accounting</td>
                    <td>Certified Public Accountant</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Pedro Ramos Diaz</td>
                    <td>BSCS</td>
                    <td>2022</td>
                    <td>IT Consultant</td>
                    <td>Tech Solutions</td>
                    <td>Developed Award-Winning App</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Ana Santos Cruz</td>
                    <td>BSTM</td>
                    <td>2023</td>
                    <td>Tourism Manager</td>
                    <td>TravelCo</td>
                    <td>Best Tourism Innovator Award</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
// Search function
document.getElementById("searchCareer").addEventListener("keyup", function(){
    let query = this.value.toLowerCase();
    document.querySelectorAll("#careerTable tbody tr").forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(query) ? "" : "none";
    });
});

// Filter function
document.getElementById("btnFilterCareer").addEventListener("click", function(){
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
