<?php
session_start();
include "db.php";

$error="";

if(isset($_POST['login'])){

$username=$_POST['username'];
$password=$_POST['password'];

$query="SELECT * FROM users WHERE username='$username' AND password='$password'";
$result=$conn->query($query);

if($result->num_rows>0){

$_SESSION['user']=$username;

header("Location:user/user_dashboard.php");
exit();

}else{

$error="Invalid Username or Password";

}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Login</title>

<style>

body{
margin:0;
font-family:Arial;
height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:linear-gradient(135deg,#3742fa,#6c5ce7);
}

.login-box{
background:white;
padding:40px;
width:360px;
border-radius:12px;
box-shadow:0 15px 35px rgba(0,0,0,0.2);
text-align:center;
}

.login-box h2{
margin-bottom:25px;
color:#3742fa;
}

input{
width:100%;
padding:12px;
margin:10px 0;
border:1px solid #ddd;
border-radius:8px;
}

button{
width:100%;
padding:12px;
background:#3742fa;
border:none;
color:white;
border-radius:8px;
cursor:pointer;
font-size:16px;
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

<div class="login-box">

<h2>User Login</h2>

<?php
if($error!=""){
echo "<p class='error'>$error</p>";
}
?>

<form method="POST">

<input type="text" name="username" placeholder="Username" required>

<input type="password" name="password" placeholder="Password" required>

<button name="login">Login</button>

</form>

<a href="#">Create Account</a>

</div>

</body>
</html>