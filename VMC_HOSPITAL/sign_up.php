<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HosCheck Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        :root {
            --primary: #4361ee;
            --card-bg: #ffffff;
            --content-bg: #f5f7fb;
            --text-primary: #2c3e50;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--content-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-card {
            background: var(--card-bg);
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        .register-card h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--text-primary);
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px;
            height: 45px;
            margin-bottom: 15px;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #3a56d4;
        }

        .register-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .register-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <h2>Create Account</h2>
        <form id="frm_signup" >
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="middlename" class="form-control" placeholder="Middle Name">
                </div>
                <div class="col-md-4">
                    <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
                </div>
            </div>

            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <input type="text" name="contact_number" class="form-control" placeholder="Contact Number" required>

            <select name="role" class="form-select" required>
                <option value="">Select Role</option>
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>

            </select>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <div class="register-footer">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>

<script>
    $("#frm_signup").submit(function(e){
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'query/save_user.php',
            type: 'POST',
            data: formData,
            success: function(response){
                if(response.trim() == "1"){
                    alert('Registration successful! You can now log in.');
                    window.location.href = 'login.php';
                } else {
                    alert(response);
                }
            },
            error: function(){
                alert('An error occurred while processing your request.');
            }
        });
    });
</script>