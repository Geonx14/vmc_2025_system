

    <style>
        body {
            background: #f5f6fa;
            font-family: "Segoe UI", sans-serif;
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

        .btn-add {
            background: #1a73e8;
            color: white;
            border-radius: 8px;
            padding: 8px 14px;
        }

        .btn-add:hover {
            background: #0f56b3;
        }

        .search-box {
            border-radius: 10px;
        }

        .badge-active {
            background: #28a745;
        }

        .badge-inactive {
            background: #dc3545;
        }

        /* Mobile adjustments */
        @media(max-width: 768px) {
            .table-responsive {
                border-radius: 10px;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body class="p-4">

    <div class="content-box">

        <!-- Header + Add User Button -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary m-0">User Management</h3>

            <button class="btn btn-add">
                + Add User
            </button>
        </div>

        <!-- Search Bar -->
        <div class="input-group mb-4">
            <input type="text" class="form-control search-box" placeholder="Search users...">
            <button class="btn btn-outline-secondary">Search</button>
        </div>

        <!-- User Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Juan Dela Cruz</td>
                        <td>juan@example.com</td>
                        <td>Admin</td>
                        <td><span class="badge badge-active">Active</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Maria Santos</td>
                        <td>maria@example.com</td>
                        <td>Staff</td>
                        <td><span class="badge badge-inactive">Inactive</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>John Mark</td>
                        <td>johnmark@example.com</td>
                        <td>Viewer</td>
                        <td><span class="badge badge-active">Active</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</body>