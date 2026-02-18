<?php
session_start();

try {
    $db = new PDO('sqlite:db/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected Successfully";
} catch (PDOException $e) {
    die("Connection Failed");
}
?>
