<?php
include "../db.php";
?>

<div class="card">

<h2>Search Product</h2>

<form method="GET" class="search-box">

<input type="hidden" name="page" value="search">

<input type="text" name="search" placeholder="Search product..." required>

<button type="submit">Search</button>

</form>

<br>

<?php

if(isset($_GET['search'])){

$s=$_GET['search'];

$q=$conn->query("SELECT * FROM products WHERE product_name LIKE '%$s%'");

if($q->num_rows>0){

echo "<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Category</th>
<th>Stock</th>
<th>Price</th>
</tr>";

while($r=$q->fetch_assoc()){

echo "<tr>

<td>".$r['id']."</td>
<td>".$r['product_name']."</td>
<td>".$r['category']."</td>
<td>".$r['quantity']."</td>
<td>".$r['price']."</td>

</tr>";

}

echo "</table>";

}else{

echo "<p style='color:red'>Product Not Found</p>";

}

}
?>

</div>