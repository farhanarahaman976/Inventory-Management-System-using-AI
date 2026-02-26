<?php
include 'db.php';

/* ========== STOCK UPDATE SYSTEM ========== */
if(isset($_POST['update_stock'])){

    $id = $_POST['product_id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];

    // Current quantity
    $current = $conn->query("SELECT quantity FROM products WHERE id=$id")->fetch_assoc()['quantity'];

    if($type == "in"){
        $new_quantity = $current + $amount;
    } else {
        $new_quantity = $current - $amount;
        if($new_quantity < 0){
            $new_quantity = 0;
        }
    }

    $conn->query("UPDATE products SET quantity=$new_quantity WHERE id=$id");

    echo "<script>alert('Stock Updated Successfully'); window.location='index.php?page=stock';</script>";
}
?>

<div class="card">
<h3>Stock Overview</h3>

<table>
<tr>
    <th>ID</th>
    <th>Product Name</th>
    <th>Current Stock</th>
    <th>Stock In</th>
    <th>Stock Out</th>
</tr>

<?php
$products = $conn->query("SELECT * FROM products");

if($products->num_rows > 0){
    while($row = $products->fetch_assoc()){
?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['product_name']; ?></td>

    <td class="<?php echo ($row['quantity'] < 10) ? 'low' : ''; ?>">
        <?php echo $row['quantity']; ?>
    </td>

    <!-- Stock In -->
    <td>
        <form method="POST" style="display:flex; gap:5px;">
            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="type" value="in">
            <input type="number" name="amount" min="1" required style="width:70px;">
            <button type="submit" name="update_stock" 
                style="background:#2ed573;color:white;border:none;padding:5px 10px;border-radius:5px;">
                Add
            </button>
        </form>
    </td>

    <!-- Stock Out -->
    <td>
        <form method="POST" style="display:flex; gap:5px;">
            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="type" value="out">
            <input type="number" name="amount" min="1" required style="width:70px;">
            <button type="submit" name="update_stock" 
                style="background:#ff4757;color:white;border:none;padding:5px 10px;border-radius:5px;">
                Remove
            </button>
        </form>
    </td>

</tr>

<?php
    }
}else{
    echo "<tr><td colspan='5'>No Products Found</td></tr>";
}
?>

</table>
</div>