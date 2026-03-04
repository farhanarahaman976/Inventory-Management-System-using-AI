<?php
include "db.php";

if(isset($_POST['reset'])){
    $user = $_POST['username'];

    $check = $conn->query("SELECT * FROM admin WHERE username='$user'");

    if($check->num_rows > 0){
        $conn->query("UPDATE admin SET password='1234' WHERE username='$user'");
        $success = "Password Reset to 1234";
    } else {
        $error = "User Not Found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<style>

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:linear-gradient(135deg,#3742fa,#6c5ce7);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.auth-card{
    background:white;
    padding:40px;
    width:350px;
    border-radius:15px;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
    text-align:center;
}

.auth-card h2{
    margin-bottom:25px;
}

.auth-card input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:8px;
    border:1px solid #ddd;
    outline:none;
}

.auth-card input:focus{
    border-color:#3742fa;
}

.auth-card button{
    width:100%;
    padding:12px;
    background:#3742fa;
    border:none;
    color:white;
    font-size:15px;
    border-radius:8px;
    cursor:pointer;
}

.auth-card button:hover{
    background:#2f3542;
}

.auth-card a{
    display:block;
    margin-top:15px;
    text-decoration:none;
    color:#3742fa;
    font-size:14px;
}

.success{
    color:green;
    margin-bottom:10px;
}

.error{
    color:red;
    margin-bottom:10px;
}

</style>
</head>

<body>

<div class="auth-card">
    <h2>🔑 Forgot Password</h2>

    <?php 
    if(isset($success)) echo "<div class='success'>$success</div>";
    if(isset($error)) echo "<div class='error'>$error</div>";
    ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <button name="reset">Reset Password</button>
    </form>

    <a href="login.php">⬅ Back to Login</a>
</div>

</body>
</html>