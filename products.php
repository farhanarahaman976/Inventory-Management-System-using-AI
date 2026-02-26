<h2 style="margin-bottom:20px;">All Products</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

<?php
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['product_name']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><?php echo $row['price']; ?></td>

        <td>
            <?php
            if($row['quantity'] < 10){
                echo "<span class='low'>Low Stock</span>";
            } else {
                echo "Available";
            }
            ?>
        </td>

        <td>
            <a href="index.php?page=update_product&id=<?php echo $row['id']; ?>" class="edit-btn btn">Edit</a>
            <a href="products.php?delete_id=<?php echo $row['id']; ?>" 
               class="delete-btn btn"
               onclick="return confirm('Delete this product?')">Delete</a>
        </td>
    </tr>
<?php
    }
}else{
    echo "<tr><td colspan='6'>No products found</td></tr>";
}
?>

</table>