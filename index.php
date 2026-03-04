<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
$notification_query = $conn->query("SELECT * FROM products WHERE quantity < 10");
$notification_count = $notification_query->num_rows;

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
    background: linear-gradient(180deg, #1e272e, #2f3640);
    color:white;
    display:flex;
    flex-direction:column;
    padding-top:20px;
}

.logo{
    text-align:center;
    font-size:22px;
    font-weight:bold;
    margin-bottom:30px;
    letter-spacing:1px;
}

.sidebar a{
    padding:14px 20px;
    color:#dcdde1;
    text-decoration:none;
    display:flex;
    justify-content:space-between;
    align-items:center;
    transition:0.3s;
    font-size:15px;
}

.sidebar a:hover{
    background:#353b48;
    color:white;
    padding-left:25px;
}

.sidebar a.active{
    background:#3742fa;
    color:white;
    border-left:4px solid #00a8ff;
}

.side-badge{
    background:#ff4757;
    color:white;
    font-size:12px;
    padding:3px 8px;
    border-radius:20px;
}

.sidebar-bottom{
    margin-top:auto;
    padding:20px;
}

.menu-title{
    padding:14px 20px;
    cursor:pointer;
    color:#dcdde1;
    transition:0.3s;
}

.menu-title:hover{
    background:#353b48;
    color:white;
}

.submenu{
    display:none;
    flex-direction:column;
}

.submenu a{
    padding:10px 40px;
    font-size:14px;
    background:#2f3640;
}

.submenu a:hover{
    background:#3d3d3d;
}

.logout-btn{
    background:#ff4757;
    text-align:center;
    padding:10px;
    border-radius:8px;
    font-weight:bold;
    display:block;
    color:white;
}

.logout-btn:hover{
    background:#e84118;
}

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
.content{
    flex:1;
    padding:40px;
}
.page-title{
    font-size:22px;
    margin-bottom:20px;
    font-weight:bold;
}

.settings-card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    width:100%;
    max-width:500px;
}
.settings-wrapper{
    display:flex;
    justify-content:center;
    margin-top:20px;
} 
.profile-box{
    display:flex;
    align-items:center;
    gap:20px;
}

.avatar{
    width:70px;
    height:70px;
    background:#3742fa;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    border-radius:50%;
    font-weight:bold;
}

.active-status{
    color:#2ed573;
    font-weight:bold;
}

.settings-form{
    display:flex;
    flex-direction:column;
}

.settings-form label{
    margin-top:15px;
    margin-bottom:5px;
    font-weight:500;
}

.settings-form input{
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
    outline:none;
    transition:0.3s;
}

.settings-form input:focus{
    border-color:#3742fa;
}

.btn-primary{
    margin-top:20px;
    padding:10px;
    background:#3742fa;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s;
}

.btn-primary:hover{
    background:#2f3542;
}

.success-msg{
    margin-top:15px;
    color:#2ed573;
    font-weight:bold;
}

.error-msg{
    margin-top:15px;
    color:#ff4757;
    font-weight:bold;
}

.profile-card{
    width:100%;
    max-width:600px;
    background:white;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 15px 35px rgba(0,0,0,0.1);
}

.profile-header{
    background:linear-gradient(135deg, #3742fa, #6c5ce7);
    padding:30px;
    display:flex;
    align-items:center;
    gap:20px;
    color:white;
}

.profile-avatar{
    width:90px;
    height:90px;
    background:white;
    color:#3742fa;
    font-size:36px;
    font-weight:bold;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
}

.profile-name h3{
    margin:0;
    font-size:22px;
}

.profile-name p{
    margin:5px 0 0;
    opacity:0.9;
}

.profile-body{
    padding:25px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.info-box{
    background:#f8f9fa;
    padding:15px;
    border-radius:10px;
    display:flex;
    flex-direction:column;
}

.info-box span{
    font-size:13px;
    color:#888;
}

.info-box strong{
    margin-top:5px;
    font-size:15px;
}

.active-status{
    color:#2ed573;
    font-weight:bold;
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
.top-left h2{
    margin:0;
}

.top-right{
    display:flex;
    align-items:center;
    gap:20px;
}

.top-right input{
    padding:8px 15px;
    border-radius:20px;
    border:none;
    width:250px;
}
/* Notification Bell*/

.notification{
    position:relative;
    margin-left:20px;
    cursor:pointer;
}

.bell{
    font-size:22px;
}

.badge{
    position:absolute;
    top:-8px;
    right:-8px;
    background:red;
    color:white;
    font-size:12px;
    padding:3px 6px;
    border-radius:50%;
}

.dropdown{
    position:absolute;
    top:40px;
    right:0;
    width:280px;
    background:#ffffff;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
    padding:15px;
    display:none;
    z-index:999;
    max-height:300px;
    overflow-y:auto;
}

.dropdown h4{
    margin-bottom:10px;
    font-size:16px;
    color:#333;
}

.dropdown p{
    font-size:14px;
    padding:8px 10px;
    margin-bottom:6px;
    background:#f8f9fa;
    border-radius:6px;
    color:#333;
}

.dropdown p:hover{
    background:#ffecec;
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
    <h2 class="logo">The Inventory Hub</h2>

    <a href="index.php?page=dashboard" class="<?php if($page=='dashboard') echo 'active'; ?>">
        📊 Dashboard
    </a>

    <a href="index.php?page=all_products" class="<?php if($page=='all_products') echo 'active'; ?>">
        📦 All Products
    </a>

    <a href="index.php?page=add_product" class="<?php if($page=='add_product') echo 'active'; ?>">
        ➕ Add Product
    </a>

    <a href="index.php?page=stock" class="<?php if($page=='stock') echo 'active'; ?>">
        📉 Stock Overview
        <?php if($notification_count > 0){ ?>
            <span class="side-badge"><?php echo $notification_count; ?></span>
        <?php } ?>
    </a>

    <a href="index.php?page=best_selling" class="<?php if($page=='best_selling') echo 'active'; ?>">
        🔥 Best Selling
    </a>

    <!-- Settings Menu -->
<div class="menu-item">

    <div class="menu-title" onclick="toggleSettings()">
        ⚙ Settings ▾
    </div>

    <div class="submenu" id="settingsMenu">

        <a href="index.php?page=profile" 
        class="<?php if($page=='profile') echo 'active'; ?>">
            👤 Admin Profile
        </a>

        <a href="index.php?page=change_password" 
        class="<?php if($page=='change_password') echo 'active'; ?>">
            🔑 Change Password
        </a>

        <a href="index.php?page=store_settings" 
        class="<?php if($page=='store_settings') echo 'active'; ?>">
            🏪 Store Settings
        </a>

    </div>
</div>

    <div class="sidebar-bottom">
        <a href="logout.php" class="logout-btn">🚪 Logout</a>
    </div>
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

<div class="notification">
    <span class="bell">🔔</span>
    <?php if($notification_count > 0){ ?>
        <span class="badge"><?php echo $notification_count; ?></span>
    <?php } ?>
    
    <div class="dropdown">
        <h4>Low Stock Alerts</h4>
        <?php
$low_stock_items = $conn->query("SELECT * FROM products WHERE quantity < 10");

if($low_stock_items->num_rows > 0){
    while($row = $low_stock_items->fetch_assoc()){
        echo "<p>{$row['product_name']} (Qty: {$row['quantity']})</p>";
    }
} else {
    echo "<p>No notifications</p>";
}
?>
    </div>
</div>
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
// ================= SEARCH =================
elseif($page == 'all_products'){

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $search_safe = $conn->real_escape_string($search);

    if($search != ''){
        $result = $conn->query("SELECT * FROM products 
                                WHERE product_name LIKE '%$search_safe%' 
                                ORDER BY id DESC");
    } else {
        $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
    }

    echo "<h2 style='margin-bottom:20px;'>All Products</h2>";

    if($result->num_rows > 0){

        echo "<table class='low-stock-table'>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>";

        while($row = $result->fetch_assoc()){

            $highlight = '';
            if($search != '' && stripos($row['product_name'], $search) !== false){
                $highlight = "style='background:#ffe6e6; font-weight:bold;'";
            }

            echo "<tr $highlight>
                    <td>{$row['id']}</td>
                    <td>{$row['product_name']}</td>
                    <td>{$row['category']}</td>
                    <td>{$row['quantity']}</td>
                    <td>৳{$row['price']}</td>
                    <td>".($row['quantity'] < 10 
                        ? "<span class='low'>Low Stock</span>" 
                        : "Available")."</td>
                    <td>
                        <a href='index.php?page=update_product&id={$row['id']}' class='edit-btn btn'>Edit</a>
                        <a href='products.php?delete_id={$row['id']}' 
                           class='delete-btn btn'
                           onclick=\"return confirm('Delete this product?')\">
                           Delete
                        </a>
                    </td>
                  </tr>";
        }

        echo "</table>";

    } else {
        echo "<p>No products found.</p>";
    }
}

// ================= SETTINGS =================
if($page == 'profile'){
    include 'profile.php';
}
elseif($page == 'change_password'){
    include 'change_password.php';
}
elseif($page == 'store_settings'){
    include 'store_settings.php';
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

// ================= BEST SELLING =================
elseif($page=='best_selling'){

$best = $conn->query("SELECT * FROM products ORDER BY quantity ASC LIMIT 5");

echo "<div class='card'><h3>Best Selling Products</h3>";

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
<script>
document.querySelector('.notification').addEventListener('click', function(e){
    e.stopPropagation();
    var dropdown = document.querySelector('.dropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener('click', function(){
    document.querySelector('.dropdown').style.display = 'none';
});
</script>
<script>
function toggleSettings(){
    var menu = document.getElementById("settingsMenu");

    if(menu.style.display === "flex"){
        menu.style.display = "none";
    } else {
        menu.style.display = "flex";
    }
}
</script>
</body>
</html>