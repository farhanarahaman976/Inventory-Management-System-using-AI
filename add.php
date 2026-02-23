<?php
session_start();
include 'db.php';

// Login check
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $sql = "INSERT INTO products (product_name, quantity, price) 
            VALUES ('$product_name', '$quantity', '$price')";

    if ($conn->query($sql) === TRUE) {
        $message = "Product Added Successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
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
            background-color: #3742fa;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #2f3542;
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
    <h2>Add Product</h2>

    <?php if($message != "") { ?>
        <p class="msg"><?php echo $message; ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <button type="submit">Add Product</button>
    </form>

    <a class="back" href="index.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>