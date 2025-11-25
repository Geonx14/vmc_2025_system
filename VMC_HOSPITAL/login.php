<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HosCheck Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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

        .login-card {
            background: var(--card-bg);
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-card h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--text-primary);
        }

        .form-control {
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

        .login-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>HosCheck Login</h2>
        <form id ="frm_login">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="login-footer">
            <p>Hav'nt account ? <a href="sign_up.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
<script>
    $("#frm_login").submit(function(e){
        e.preventDefault();
   
        $.ajax({
            url:'query/login.php',
            method:'POST',
            data:$(this).serialize(),
            error:err=>{
                console.log(err)
                alert("An error occurred");
            },
            success:function(resp){    
                if(resp == 1){
                    location.href ='index.php';
                }else{
                    alert("Invalid username or password.");
                }
            }
        })
    });
</script>