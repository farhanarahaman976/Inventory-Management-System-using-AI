<h2 class="page-title">👤 Admin Profile</h2>
<div class="settings-wrapper">

<div class="profile-card">

    <div class="profile-header">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($_SESSION['username'],0,1)); ?>
        </div>

        <div class="profile-name">
            <h3><?php echo $_SESSION['username']; ?></h3>
            <p>Administrator</p>
        </div>
    </div>

    <div class="profile-body">

        <div class="info-box">
            <span>Username</span>
            <strong><?php echo $_SESSION['username']; ?></strong>
        </div>

        <div class="info-box">
            <span>Email</span>
            <strong>farhana.rahaman37@gmail.com</strong>
        </div>

        <div class="info-box">
            <span>Status</span>
            <strong class="active-status">Active</strong>
        </div>

        <div class="info-box">
            <span>Last Login</span>
            <strong><?php echo date("d M Y"); ?></strong>
        </div>

    </div>

</div>