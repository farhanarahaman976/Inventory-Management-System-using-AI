<?php
$backup_file = "backup_" . date("Y-m-d") . ".sql";
exec("mysqldump -u root -p inventory_db > $backup_file");
echo "Backup Created!";
?>