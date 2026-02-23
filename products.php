<?php
session_start();
include 'db.php';

// Login check
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

// Delete Product
if(isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f1f2f6;
            padding: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        table th, table td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #3742fa;
            color: white;
        }

        a {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }

        .edit-btn {
            background-color: #2ed573;
        }

        .edit-btn:hover {
            background-color: #27ae60;
        }

        .delete-btn {
            background-color: #ff4757;
        }

        .delete-btn:hover {
            background-color: #e84118;
        }

        .low-stock {
            color: red;
            font-weight: bold;
        }

        .back-btn {
            display: block;
            width: 120px;
            margin: 20px auto;
            text-align: center;
            background-color: #3742fa;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #2f3542;
        }
    </style>
</head>
<body>

<h2>Product List</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Stock Status</th>
        <th>Action</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['product_name']."</td>";
            echo "<td>".$row['quantity']."</td>";
            echo "<td>".$row['price']."</td>";

            // Low Stock Alert
            if($row['quantity'] < 10){
                echo "<td class='low-stock'>Low Stock!</td>";
            } else {
                echo "<td>OK</td>";
            }

            echo "<td>
                    <a href='edit.php?id=".$row['id']."' class='edit-btn'>Edit</a>
                    <a href='products.php?delete_id=".$row['id']."' class='delete-btn' onclick=\"return confirm('Are you sure to delete?')\">Delete</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No products found</td></tr>";
    }
    ?>

</table>

<a href="index.php" class="back-btn">⬅ Back to Dashboard</a>

</body>
</html>