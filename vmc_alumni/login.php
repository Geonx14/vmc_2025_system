<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Login</title>

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        /* SCHOOL BACKGROUND */
        body {
            min-height: 100vh;
            background:
                linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)),
                url("https://scontent.fcgy2-4.fna.fbcdn.net/v/t39.30808-6/486610166_1121733799966799_1840476624845404482_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=cc71e4&_nc_eui2=AeGWMHhYIG6xpeIQuryc0Pf6EEsKUUDu7PkQSwpRQO7s-Sx1WT1qjaU9FsEZfaROqm-fk-rPz_2ksxf1blsN50a2&_nc_ohc=FXXXwdHSgokQ7kNvwEm10gA&_nc_oc=AdmgO85tY9iGvfQX5cm3XINdE_CwwdN7PSmz4AxZTsZSiu_Dw0RPdj8zkkdfTpVEqJ4&_nc_zt=23&_nc_ht=scontent.fcgy2-4.fna&_nc_gid=19LDQoP2-v0haR6eyVAjdA&oh=00_AfphbYPoxxiMyrtvf0Yksl0ZgHfs_tqNYAL7kdlHW2LcNQ&oe=69702F8F") center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* LOGIN CARD */
        .login-card {
            width: 100%;
            max-width: 400px;
            background: rgba(30, 41, 59, 0.9);
            backdrop-filter: blur(10px);
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            color: #fff;
        }

        /* LOGO */
        .logo {
            width: 90px;
            display: block;
            margin: 0 auto 15px;
        }

        /* TITLE */
        .login-card h3 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* INPUTS */
        .form-label {
            font-size: 14px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            border-radius: 10px;
        }

        .form-control::placeholder {
            color: #cbd5e1;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #3b82f6;
            box-shadow: none;
            color: #fff;
        }

        /* BUTTON */
        .btn-login {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.5);
        }

        /* ERROR */
        .error-msg {
            color: #fca5a5;
            font-size: 13px;
            margin-top: 4px;
        }

        /* FOOTER */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .login-footer a {
            color: #60a5fa;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <img src="your-logo.png" class="logo" alt="School Logo">
        <h3>Alumni Login</h3>

        <form id="loginForm">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
                <div class="error-msg" id="usernameError"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                <div class="error-msg" id="passwordError"></div>
            </div>

            <button type="submit" class="btn btn-login w-100">Login</button>

            <div class="login-footer">
                Don’t have an account?
                <a href="sign_up.php">Sign up</a>
            </div>
        </form>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                $('#usernameError').text('');
                $('#passwordError').text('');

                $.ajax({
                    url: 'query/login.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        response = response.trim();

                        if (response === '1') {
                            window.location.href = 'index.php';
                        } else if (response === 'username') {
                            $('#usernameError').text('Username not found.');
                        } else if (response === 'password') {
                            $('#passwordError').text('Incorrect password.');
                        } else if (response === '2') {
                            alert('Your account is not approved yet. Please contact the administrator.');
                        } else {
                            alert('Invalid login. Please try again.');
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again later.');
                    }
                });
            });
        });
    </script>

</body>

</html>