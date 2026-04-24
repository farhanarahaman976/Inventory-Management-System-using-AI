<?php

$result = $conn->query("SELECT * FROM products");

echo "<div class='card'><h2>Available Products</h2>";

echo <table>
<tr>
<th>Name</th>
<th>Category</th>
<th>Stock</th>
<th>Price</th>
</tr>

$result = $conn->query("SELECT * FROM products");

while($row=$result->fetch_assoc()){
echo "<tr>
<td>{$row['product_name']}</td>
<td>{$row['category']}</td>
<td>{$row['quantity']}</td>
<td>৳{$row['price']}</td>
</tr>";
}

echo "</table></div>";
?>