<?php
include "db.php";

// DELETE LOGIC
if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']); // security
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: index.php?page=all_products");
    exit();
}

?>