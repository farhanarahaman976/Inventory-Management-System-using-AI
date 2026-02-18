<?php
// login.php

session_start();

// Hardcoded credentials (porer jonno DB use kora better)
$valid_username = "adiba";
$valid_password = "5722"; // simple example, normally hashed password use korte hobe

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username; // session start
        header("Location: index.php"); // login successful -> redirect
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #dfe6e9;
      padding: 40px;
    }
    .login-box {
      background-color: white;
      padding: 30px;
      max-width: 400px;
      margin: auto;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #bdc3c7;
      border-radius: 6px;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #3498db;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover {
      background-color: #2980b9;
    }
    .error {
      color: red;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Login</h2>
    <?php if($error) { echo '<p class="error">'.$error.'</p>'; } ?>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>

</body>
</html>
