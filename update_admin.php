<?php
$db = new PDO('sqlite:db/database.sqlite');

// Update existing admin
$db->exec("UPDATE admin SET username='adiba', password='5722' WHERE id=2");

echo "Admin Updated Successfully!";
?>
