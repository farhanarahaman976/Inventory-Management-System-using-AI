<?php
$db = new PDO('sqlite:db/database.sqlite');

$result = $db->query("SELECT * FROM admin");

$admins = $result->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($admins);
echo "</pre>";
?>
