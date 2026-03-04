<h2 class="page-title">🔑 Change Password</h2>
<div class="settings-wrapper">

<div class="settings-card">
    <form method="POST" class="settings-form">

        <label>Old Password</label>
        <input type="password" name="old_pass" required>

        <label>New Password</label>
        <input type="password" name="new_pass" required>

        <button name="update_pass" class="btn-primary">
            Update Password
        </button>
    </form>

<?php
if(isset($_POST['update_pass'])){

    $old = $_POST['old_pass'];
    $new = $_POST['new_pass'];
    $user = $_SESSION['username'];

    $check = $conn->query("SELECT * FROM admin WHERE username='$user' AND password='$old'");

    if($check->num_rows > 0){
        $conn->query("UPDATE admin SET password='$new' WHERE username='$user'");
        echo "<p class='success-msg'>Password Updated Successfully</p>";
    } else {
        echo "<p class='error-msg'>Wrong Old Password</p>";
    }
}
?>
</div>