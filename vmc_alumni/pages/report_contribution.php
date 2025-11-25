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
</style>

<div class="content-box">
    <h3 class="fw-bold text-primary mb-4">Alumni Contribution Summary Report</h3>

    <!-- Filters -->
    <div class="filter-group">
        <select id="filterYear">
            <option value="">All Years</option>
            <option value="2025">2025</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
        </select>

        <select id="filterCourse">
            <option value="">All Courses</option>
            <option value="BSIT">BSIT</option>
            <option value="BSCS">BSCS</option>
            <option value="BSA">BSA</option>
            <option value="BSTM">BSTM</option>
        </select>

        <select id="filterType">
            <option value="">All Contribution Types</option>
            <option value="Monetary">Monetary</option>
            <option value="In-Kind">In-Kind</option>
        </select>

        <button id="btnFilter" class="btn btn-add">Filter</button>
    </div>

    <!-- Contributions Table -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="contributionTable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Alumni Name</th>
                    <th>Course</th>
                    <th>Year Graduated</th>
                    <th>Contribution Type</th>
                    <th>Amount / Description</th>
                    <th>Date Contributed</th>
                </tr>
            </thead>
            <tbody>
                <tr data-year="2025" data-course="BSIT" data-type="Monetary">
                    <td>1</td>
                    <td>Juan Santos Cruz</td>
                    <td>BSIT</td>
                    <td>2025</td>
                    <td>Monetary</td>
                    <td>$500</td>
                    <td>2025-11-01</td>
                </tr>
                <tr data-year="2024" data-course="BSCS" data-type="In-Kind">
                    <td>2</td>
                    <td>Maria Lopez</td>
                    <td>BSCS</td>
                    <td>2024</td>
                    <td>In-Kind</td>
                    <td>Books Donation</td>
                    <td>2024-10-15</td>
                </tr>
                <tr data-year="2023" data-course="BSA" data-type="Monetary">
                    <td>3</td>
                    <td>Carlos Dela Cruz</td>
                    <td>BSA</td>
                    <td>2023</td>
                    <td>Monetary</td>
                    <td>$1000</td>
                    <td>2023-09-20</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div class="total-summary" id="totalSummary">
        Total Contributions: $1500
    </div>
</div>

<script>
// Filter functionality
document.getElementById("btnFilter").onclick = () => {
    let year = document.getElementById("filterYear").value;
    let course = document.getElementById("filterCourse").value;
    let type = document.getElementById("filterType").value;

    let total = 0;

    document.querySelectorAll("#contributionTable tbody tr").forEach(row => {
        let matchYear = year === "" || row.dataset.year === year;
        let matchCourse = course === "" || row.dataset.course === course;
        let matchType = type === "" || row.dataset.type === type;

        let visible = matchYear && matchCourse && matchType;
        row.style.display = visible ? "" : "none";

        if (visible && row.dataset.type === "Monetary") {
            total += parseFloat(row.children[5].textContent.replace(/\$/,''));
        }
    });

    document.getElementById("totalSummary").textContent = `Total Contributions: $${total}`;
};
</script>
