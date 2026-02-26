<?php
// ================= SESSION START =================
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ================= DATABASE =================
include 'db.php';

// ================= LOGIN CHECK =================
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

// ================= MESSAGE VARIABLE =================
$message = "";

// ================= HANDLE FORM SUBMISSION =================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $price = $conn->real_escape_string($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);

    $sql = "INSERT INTO products (product_name, quantity, price, category) 
            VALUES ('$product_name', '$quantity', '$price', '$category')";

    if ($conn->query($sql) === TRUE) {
        $message = "Product Added Successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// ================= CATEGORY ARRAY =================
$categories = [
    'Cooking',
    'Beverages',
    'Breakfast',
    'Snacks',
    'Dairy',
    'Frozen',
    'Personal Care',
    'Cleaning',
    'Dry Fruits',
    'Bakery',
    'Stationery',
    'Baby Care',
    'Instant Food'
];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        .box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #3742fa;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #2f3542;
        }

        .msg {
            text-align: center;
            color: green;
            font-weight: bold;
        }

        .back {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #3742fa;
            font-weight: bold;
        }

        .back:hover {
            text-decoration: underline;
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

        <!-- CATEGORY SELECT -->
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Add Product</button>
    </form>

    <a class="back" href="index.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>