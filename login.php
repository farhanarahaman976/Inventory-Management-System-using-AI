<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<style>
body{
    margin:0;
    font-family:Arial;
    background:linear-gradient(135deg,#3742fa,#6c5ce7);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.card{
    background:white;
    padding:40px;
    width:350px;
    border-radius:15px;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
    text-align:center;
}
input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:8px;
    border:1px solid #ddd;
}
button{
    width:100%;
    padding:12px;
    background:#3742fa;
    border:none;
    color:white;
    border-radius:8px;
    cursor:pointer;
}
button:hover{
    background:#2f3542;
}
.error{
    color:red;
    margin-bottom:10px;
}
a{
    display:block;
    margin-top:15px;
    text-decoration:none;
    color:#3742fa;
}
</style>
</head>

<body>

<div class="card">
    <h2>Admin Login</h2>

    <?php if($error!="") echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <a href="forgot_password.php">Forgot Password?</a>
</div>

</body>
</html>