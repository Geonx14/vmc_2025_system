<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alumni Login</title>

<!-- BOOTSTRAP 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<style>
    body {
        background: #0f172a; /* dark slate */
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
        color: #fff;
    }
    .login-card {
        width: 100%;
        max-width: 380px;
        background: #1e293b;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }
    .login-card h3 {
        text-align: center;
        margin-bottom: 20px;
        font-weight: 600;
    }
    .form-control {
        background: #334155;
        border: none;
        color: #fff;
    }
    .form-control:focus {
        background: #475569;
        border: 1px solid #64748b;
        box-shadow: none;
        color: #fff;
    }
    .btn-login {
        background: #3b82f6;
        border: none;
    }
    .btn-login:hover {
        background: #2563eb;
    }
    .logo {
        width: 80px;
        display: block;
        margin: 0 auto 15px;
    }
    .error-msg {
        color: #f87171;
        font-size: 14px;
        margin-top: 5px;
    }
</style>
</head>
<body>

<div class="login-card">
    <img src="your-logo.png" class="logo" alt="Logo">
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

        <button type="submit" class="btn btn-primary w-100 btn-login">Login</button>

        <div class="text-center mt-3">
        <span> Don't have account? </span>  <a href="sign_up.php" class="text-light" style="font-size: 14px;"> Sign up.</a>
        </div>
    </form>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){
    $('#loginForm').on('submit', function(e){
        e.preventDefault();

        // Clear previous errors
        $('#usernameError').text('');
        $('#passwordError').text('');

        $.ajax({
            url: 'query/login.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                response = response.trim();

                if(response == '1'){
                    // Successful login
                    window.location.href = 'index.php';
                } else if(response == 'username'){
                    $('#usernameError').text('Username not found.');
                } else if(response == 'password'){
                    $('#passwordError').text('Incorrect password.');
                } else {
                    alert('Invalid login. Please try again.');
                }
            },
            error: function(){
                alert('An error occurred. Please try again later.');
            }
        });
    });
});
</script>

</body>
</html>
