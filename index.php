<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// For dashboard summary
$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$total_stock = $conn->query("SELECT SUM(quantity) as stock FROM products")->fetch_assoc()['stock'];
$low_stock = $conn->query("SELECT COUNT(*) as low FROM products WHERE quantity<10")->fetch_assoc()['low'];
$recent_products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Inventory Dashboard</title>
<style>
* { box-sizing: border-box; margin:0; padding:0; font-family: Arial, sans-serif; }
body { display: flex; height: 100vh; background-color: #f4f6f9; }

/* Sidebar */
.sidebar {
    width: 220px;
    background-color: #2f3542;
    color: white;
    display: flex;
    flex-direction: column;
}
.sidebar h2 {
    text-align: center;
    padding: 20px 0;
    border-bottom: 1px solid #57606f;
    font-size: 22px;
}
.sidebar a {
    padding: 15px 20px;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid #57606f;
    transition: 0.3s;
}
.sidebar a:hover { background-color: #57606f; }
.logout-btn {
    margin: 20px 15px;
    padding: 10px;
    background-color: #ff4757;
    text-align: center;
    border-radius: 5px;
    color: white;
    text-decoration: none;
    font-weight: bold;
}
.logout-btn:hover { background-color: #e84118; }

/* Main */
.main { flex:1; display:flex; flex-direction:column; }
/* Topbar */
.topbar {
    height: 70px;
    background-color: #3742fa;
    color:white;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 30px;
    flex-shrink:0;
}
.topbar h2 { font-size:20px; }
.topbar input[type="text"] {
    width:300px;
    padding:8px 12px;
    border-radius:20px;
    border:none;
    outline:none;
}

/* Content */
.content { flex:1; padding:20px; overflow-y:auto; }

/* Cards */
.card { background:white; padding:20px; margin-bottom:20px; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.1);}
.card h3 { margin-bottom:15px; }
.card p { color:#555; }

/* Table */
table { width:100%; border-collapse:collapse; }
table th, table td { padding:10px; border-bottom:1px solid #ddd; text-align:center; }
table th { background-color:#3742fa; color:white; }
.btn { padding:6px 12px; border-radius:5px; color:white; text-decoration:none; margin:0 2px; }
.edit-btn { background-color:#2ed573; }
.delete-btn { background-color:#ff4757; }
.edit-btn:hover { background-color:#27ae60; }
.delete-btn:hover { background-color:#e84118; }
.low-stock { color:red; font-weight:bold; }
</style>
</head>
<body>

<div class="sidebar">
<h2>Inventory</h2>
<a href="index.php?page=dashboard">Dashboard</a>
<a href="index.php?page=all_products">All Products</a>
<a href="index.php?page=add_product">Add Product</a>
<a href="index.php?page=update_product">Update Product</a>
<a href="index.php?page=stock">Stock Overview</a>
<a href="index.php?page=best_selling">Best Selling</a>
<a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="main">
<div class="topbar">
<h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
<input type="text" placeholder="Search products...">
</div>

<div class="content">

<?php
if($page=='dashboard') {

    // Dashboard summary queries
    $total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
    $total_stock = $conn->query("SELECT SUM(quantity) as stock FROM products")->fetch_assoc()['stock'];
    $low_stock = $conn->query("SELECT COUNT(*) as low FROM products WHERE quantity<10")->fetch_assoc()['low'];
    $recent_products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 5");
    $low_products = $conn->query("SELECT * FROM products WHERE quantity<10");
?>

<!-- Summary Cards -->
<div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
    <div style="flex:1; background:#3742fa; color:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
        <h3>Total Products</h3>
        <p style="font-size:24px; font-weight:bold;"><?php echo $total_products; ?></p>
    </div>
    <div style="flex:1; background:#2ed573; color:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
        <h3>Total Stock</h3>
        <p style="font-size:24px; font-weight:bold;"><?php echo $total_stock ? $total_stock : 0; ?></p>
    </div>
    <div style="flex:1; background:#ff4757; color:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
        <h3>Low Stock Items</h3>
        <p style="font-size:24px; font-weight:bold;"><?php echo $low_stock; ?></p>
    </div>
</div>

<!-- Recently Added Products -->
<div class="card" style="padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05); margin-bottom:30px;">
    <h3 style="margin-bottom:15px;">Recently Added Products</h3>
    <?php if($recent_products->num_rows>0){ ?>
        <ul style="list-style:none; padding-left:0;">
            <?php while($row = $recent_products->fetch_assoc()){ ?>
                <li style="padding:10px 0; border-bottom:1px solid #eee;">
                    <strong><?php echo $row['product_name']; ?></strong> - Qty: <?php echo $row['quantity']; ?> 
                    <span style="color:#555;">(Price: <?php echo $row['price']; ?>)</span>
                </li>
            <?php } ?>
        </ul>
    <?php } else { echo "<p>No products yet.</p>"; } ?>
</div>

<!-- Low Stock Table -->
<div class="card" style="padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
    <h3 style="margin-bottom:15px;">Low Stock Items (Qty < 10)</h3>
    <?php if($low_products->num_rows>0){ ?>
        <table style="width:100%; border-collapse:collapse;">
            <tr style="background:#3742fa; color:white;">
                <th>ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php while($row = $low_products->fetch_assoc()){ ?>
                <tr style="text-align:center; border-bottom:1px solid #eee;">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td style="color:red; font-weight:bold;"><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { echo "<p>No low stock items.</p>"; } ?>
</div>

<?php } // end if dashboard ?>

</div> <!-- End content -->