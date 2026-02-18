<?php
$db = new PDO('sqlite:db/database.sqlite');

$db->exec("DELETE FROM admin"); 

$db->exec("INSERT INTO admin (username, password) VALUES ('adiba', '5722')");

echo "Fresh Admin Created!";
?>
