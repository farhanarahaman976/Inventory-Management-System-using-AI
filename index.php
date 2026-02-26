<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Inventory Shop Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box;margin:0;padding:0;font-family:Arial;}
body{display:flex;height:100vh;background:#f4f6f9;}

/* Sidebar */
.sidebar{
    width:260px;
    background:#2f3542;
    color:white;
    display:flex;
    flex-direction:column;
}
.sidebar h2{
    text-align:center;
    padding:20px;
    border-bottom:1px solid #57606f;
}
.sidebar a{
    padding:15px 20px;
    color:white;
    text-decoration:none;
    border-bottom:1px solid #57606f;
    transition:0.3s;
}
.sidebar a:hover{background:#57606f;}
.logout-btn{
    margin:20px;
    padding:10px;
    background:#ff4757;
    text-align:center;
    border-radius:6px;
    font-weight:bold;
}
.logout-btn:hover{background:#e84118;}

/* Main */
.main{flex:1;display:flex;flex-direction:column;}
.main-title{
    font-size:42px;
    font-weight:900;
    letter-spacing:2px;
    background: linear-gradient(90deg, #bbdcfd, #a29bfe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Topbar */
/* Topbar Styling */
.topbar{
    height:220px;
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('images/topbar.jpg');
    background-size: cover;
    background-position: center;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 40px;
}


/* Search Bar */
.search-form{
    display:flex;
    align-items:center;
    gap:10px;
}

.search-form input{
    width:350px;          /* Search bar বড় */
    padding:12px 20px;
    border-radius:30px;
    border:none;
    outline:none;
    font-size:16px;
}

.search-form button{
    padding:12px 25px;
    border:none;
    border-radius:30px;
    background:#2ed573;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.search-form button:hover{
    background:#1e90ff;
}


/* Content */
.content{flex:1;padding:25px;overflow-y:auto;}

/* Cards */
.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

/* Summary Cards */
.summary{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:30px;
}
.summary-box{
    flex:1;
    padding:20px;
    border-radius:12px;
    color:white;
}
.blue{
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.green{
    background: linear-gradient(135deg, #43cea2, #185a9d);
}

.red{
    background: linear-gradient(135deg, #ff758c, #ff7eb3);
}
.summary-box h3{margin-bottom:10px;}
.summary-box p{font-size:24px;font-weight:bold;}

/* Table */
table{width:100%;border-collapse:collapse;}
table th, table td{
    padding:10px;
    border-bottom:1px solid #ddd;
    text-align:center;
}
table th{
    background:#3742fa;
    color:white;
}
.low{color:red;font-weight:bold;}

/* Low Stock Table Styling */
.low-stock-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
}

.low-stock-table thead {
    background: linear-gradient(90deg, #8e6bff, #6947ff);
    color: white;
}

.low-stock-table th, .low-stock-table td {
    padding: 12px 15px;
    text-align: center;
}

.low-stock-table tbody tr {
    background: #ffffff;
    transition: background 0.3s;
}

.low-stock-table tbody tr:nth-child(even) {
    background: #f9f9f9;
}

.low-stock-table tbody tr:hover {
    background: #ffe6e6;
}

.low-stock-table td.low {
    font-weight: bold;
    color: #000000; /* Dark red for low stock */
    background: #f6d6d6; /* Soft red background */
    border-radius: 6px;
}

/* Category Grid */
.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 25px;
}

/* Category Box */
.cat-box {
    position: relative;
    height: 160px;   /* box height bigger */
    border-radius: 15px;
    overflow: hidden;
    text-decoration: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.cat-box:hover {
    transform: scale(1.05);
}

/* Image */
.cat-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Category Name (Center Overlay) */
.cat-name {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 20px;   /* bigger text */
    font-weight: bold;
    text-align: center;
    background: rgba(0,0,0,0.5);
    padding: 8px 12px;
    border-radius: 8px;
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>The Inventory Hub</h2>
    <a href="index.php?page=dashboard">Dashboard</a>
    <a href="index.php?page=all_products">All Products</a>
    <a href="index.php?page=add_product">Add Product</a>
    <a href="index.php?page=stock">Stock Overview</a>
    <a href="index.php?page=best_selling">AI Best Selling</a>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<!-- Main -->
<div class="main">

<div class="topbar">

    <div class="title-section">
        <h2 class="main-title">The Inventory Hub</h2>
        <p class="sub-title">Smart storage. Smart tracking</p>
    </div>

    <form method="GET" action="index.php" class="search-form">
        <input type="hidden" name="page" value="all_products">
        <input type="text" name="search" placeholder="Search products...">
        <button type="submit">Search</button>
    </form>

</div>


<div class="content">

<?php

// ================= DASHBOARD =================
if($page=='dashboard'){

$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$total_stock = $conn->query("SELECT SUM(quantity) as stock FROM products")->fetch_assoc()['stock'];
$low_stock = $conn->query("SELECT COUNT(*) as low FROM products WHERE quantity<10")->fetch_assoc()['low'];

$recent = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 5");
$low_products = $conn->query("SELECT * FROM products WHERE quantity<10");

?>

<div class="summary">
    <div class="summary-box blue">
        <h3>Total Products</h3>
        <p><?php echo $total_products; ?></p>
    </div>
    <div class="summary-box green">
        <h3>Total Stock</h3>
        <p><?php echo $total_stock ? $total_stock : 0; ?></p>
    </div>
    <div class="summary-box red">
        <h3>Low Stock Items</h3>
        <p><?php echo $low_stock; ?></p>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom:20px;">Shop Categories</h3>

    <div class="category-grid">

        <a href="index.php?page=category&name=Cooking" class="cat-box">
            <p class="cat-name">Cooking & Staples</p>
            <img src="images/cooking.jpg" alt="Cooking & Staples">
        </a>

        <a href="index.php?page=category&name=Beverages" class="cat-box">
            <p class="cat-name">Beverages</p>
            <img src="images/beverages.jpg" alt="Beverages">
        </a>

        <a href="index.php?page=category&name=Breakfast" class="cat-box">
            <p class="cat-name">Breakfast Essentials</p>
            <img src="images/breakfast.jpg" alt="Breakfast Essentials">
        </a>

        <a href="index.php?page=category&name=Snacks" class="cat-box">
            <p class="cat-name">Snacks</p>
            <img src="images/snacks.jpg" alt="Snacks">
        </a>

        <a href="index.php?page=category&name=Dairy" class="cat-box">
            <p class="cat-name">Dairy Products</p>
            <img src="images/dairy.jpg" alt="Dairy Products">
        </a>

        <a href="index.php?page=category&name=Frozen" class="cat-box">
            <p class="cat-name">Frozen Food</p>
            <img src="images/frozen.jpg" alt="Frozen Food">
        </a>

        <a href="index.php?page=category&name=Personal Care" class="cat-box">
            <p class="cat-name">Personal Care</p>
            <img src="images/personalcare.jpg" alt="Personal Care">
        </a>

        <a href="index.php?page=category&name=Cleaning" class="cat-box">
            <p class="cat-name">Cleaning Products</p>
            <img src="images/cleaning.jpg" alt="Cleaning Products">
        </a>

        <a href="index.php?page=category&name=Dry Fruits" class="cat-box">
            <p class="cat-name">Dry Fruits & Nuts</p>
            <img src="images/dryfruits.jpg" alt="Dry Fruits & Nuts">
        </a>

        <a href="index.php?page=category&name=Bakery" class="cat-box">
            <p class="cat-name">Bakery Items</p>
            <img src="images/bakery.jpg" alt="Bakery Items">
        </a>

        <a href="index.php?page=category&name=Stationery" class="cat-box">
            <p class="cat-name">Stationery Items</p>
            <img src="images/stationery.jpg" alt="Stationery Items">
        </a>

        <a href="index.php?page=category&name=Baby Care" class="cat-box">
            <p class="cat-name">Baby Care Products</p>
            <img src="images/babycare.jpg" alt="Baby Care Products">
        </a>

        <a href="index.php?page=category&name=Instant Food" class="cat-box">
            <p class="cat-name">Instant Food</p>
            <img src="images/instantfood.jpg" alt="Instant Food">
        </a>

    </div>
</div>

<div class="card">
    <h3 style="margin-bottom:20px;">Low Stock Alert</h3>
    <?php
    if($low_products->num_rows>0){
        echo "<table class='low-stock-table'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>";
        while($row=$low_products->fetch_assoc()){
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['product_name']}</td>
                    <td class='low'>{$row['quantity']}</td>
                    <td>৳{$row['price']}</td>
                  </tr>";
        }
        echo "</tbody>
              </table>";
    } else {
        echo "<p style='text-align:center; color:green; font-weight:bold;'>No low stock items 🎉</p>";
    }
    ?>
</div>

<?php
}

// ================= CATEGORY =================
elseif($page=='category'){
    $cat = $_GET['name'];
    $result = $conn->query("SELECT * FROM products WHERE category='$cat'");
    echo "<div class='card'><h3>$cat Products</h3>";

    if($result->num_rows>0){
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>";
        while($row=$result->fetch_assoc()){
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['product_name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['price']}</td>
                  </tr>";
        }
        echo "</table>";
    }else{
        echo "<p>No products in this category.</p>";
    }
    echo "</div>";
}

// ================= ALL PRODUCTS =================
elseif($page=='all_products'){
    include 'products.php';
}

// ================= ADD PRODUCT =================
elseif($page=='add_product'){
    include 'add.php';
}

// ================= STOCK =================
elseif($page=='stock'){
    include 'stock.php';
}

// ================= AI BEST SELLING =================
elseif($page=='best_selling'){

$best = $conn->query("SELECT * FROM products ORDER BY quantity ASC LIMIT 5");

echo "<div class='card'><h3>AI Best Selling Products</h3>";

if($best->num_rows>0){
echo "<table>
<tr>
<th>Name</th>
<th>Remaining Qty</th>
<th>Price</th>
</tr>";
while($row=$best->fetch_assoc()){
echo "<tr>
<td>{$row['product_name']}</td>
<td>{$row['quantity']}</td>
<td>৳{$row['price']}</td>
</tr>";
}
echo "</table>";
}else{
echo "<p>No Data Available</p>";
}

echo "</div>";
}
?>

</div>
</div>
</body>
</html>