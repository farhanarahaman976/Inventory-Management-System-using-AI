<h2 class="page-title">🏪 Store Settings</h2>

<div class="settings-wrapper">
<div class="settings-card">
    <form method="POST" class="settings-form">

        <label>Store Name</label>
        <input type="text" name="store_name" placeholder="Enter Store Name">

        <label>Store Email</label>
        <input type="email" name="store_email" placeholder="Enter Store Email">

        <label>Store Phone</label>
        <input type="text" name="store_phone" placeholder="Enter Phone Number">

        <button name="save_store" class="btn-primary">
            Save Settings
        </button>
    </form>

<?php
if(isset($_POST['save_store'])){
    echo "<p class='success-msg'>Settings Saved Successfully</p>";
}
?>
</div>