<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni | Staff | Student Sign Up</title>

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        body {
            background: #0f172a;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            padding: 30px;
        }

        .signup-card {
            width: 100%;
            max-width: 650px;
            background: #1e293b;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .form-control,
        .form-select {
            background: #334155;
            border: none;
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            background: #475569;
            color: #fff;
            border: 1px solid #64748b;
            box-shadow: none;
        }

        .btn-submit {
            background: #3b82f6;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
        }

        .btn-submit:hover {
            background: #2563eb;
        }
    </style>
</head>

<body>

    <div class="signup-card">
        <h3 class="text-center fw-bold mb-4">Create Your Account</h3>

        <form id="signupForm" enctype="multipart/form-data">

            <!-- USER TYPE -->
            <div class="mb-3">
                <label class="form-label">User Type</label>
                <select class="form-select" id="user_type" name="user_type" required>
                    <option value="">Select user type</option>
                    <option value="student">Student</option>
                    <option value="alumni">Alumni</option>
                    <option value="staff">Registar</option>
                </select>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label>First Name</label>
                    <input type="text" name="firstname" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Middle Name</label>
                    <input type="text" name="middlename" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Last Name</label>
                    <input type="text" name="lastname" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Birthday</label>
                    <input type="date" name="birthday" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Mobile</label>
                    <input type="text" name="mobile" class="form-control" required>
                </div>

                <!-- COURSE (SHOW ONLY IF STUDENT OR ALUMNI) -->
                <div class="col-md-6" id="courseDiv" style="display:none;">
                    <label>Course</label>
                    <select class="form-select" name="course">
                        <option value="">Select Course</option>
                        <option>Bachelor of Science in Information Technology</option>
                        <option>Bachelor of Science in Computer Science</option>
                        <option>Bachelor of Science in Business Administration</option>
                        <option>Bachelor of Science in Criminology</option>
                        <option>Bachelor of Elementary Education</option>
                        <option>Bachelor of Secondary Education</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="col-md-12">
                    <label>Avatar</label>
                    <input type="file" name="avatar" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-submit w-100 mt-4">Create Account</button>

            <p class="text-center mt-3">
                Already have an account?
                <a href="login.php" class="text-info">Login</a>
            </p>
        </form>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // SHOW COURSE ONLY FOR STUDENT & ALUMNI
        $("#user_type").change(function() {
            let type = $(this).val();

            if (type == "student" || type == "alumni") {
                $("#courseDiv").slideDown();
            } else {
                $("#courseDiv").slideUp();
            }
        });

        // SUBMIT FORM
        $("#signupForm").on("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "query/save_user.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.trim() == "1") {
                        alert("Account created successfully!");
                        window.location.href = "login.php";
                    } else if (response.trim() == "3") {
                        alert("Username already exists. Please choose another.");
                    } else if (response.trim() == "0") {
                        alert("Error creating account. Please try again.");
                    } else {
                        alert("Error: " + response);
                    }
                }
            });
        });
    </script>

</body>

</html>