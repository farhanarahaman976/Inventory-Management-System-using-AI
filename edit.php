<?php
session_start();
include 'db.php';

// Login check
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

// Get product id from URL
if(!isset($_GET['id'])){
    header("Location: products.php");
    exit();
}

$id = $_GET['id'];

// Fetch product data
$result = $conn->query("SELECT * FROM products WHERE id=$id");
if($result->num_rows != 1){
    header("Location: products.php");
    exit();
}

$product = $result->fetch_assoc();
$message = "";

// Update product
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $sql = "UPDATE products SET product_name='$product_name', quantity='$quantity', price='$price' WHERE id=$id";

    if($conn->query($sql) === TRUE){
        $message = "Product Updated Successfully!";
        // Refresh product data
        $result = $conn->query("SELECT * FROM products WHERE id=$id");
        $product = $result->fetch_assoc();
    } else {
        $message = "Error: ".$conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f1f2f6;
            padding: 40px;
        }

        .box {
            background: white;
            padding: 30px;
            width: 400px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #2ed573;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #27ae60;
        }

        .msg {
            text-align: center;
            color: green;
        }

        .back {
            display: block;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Edit Product</h2>

    <?php if($message != "") { ?>
        <p class="msg"><?php echo $message; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="product_name" value="<?php echo $product['product_name']; ?>" required>
        <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
        <button type="submit">Update Product</button>
    </form>

    <a class="back" href="products.php">⬅ Back to Product List</a>
</div>

</body>
</html>