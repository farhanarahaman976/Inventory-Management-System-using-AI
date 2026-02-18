<?php
include 'includes/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Welcome, <?php echo $_SESSION['admin']; ?></h2>

<a href="logout.php">Logout</a>

<h3>Dashboard Coming Soon...</h3>

</body>
</html>
