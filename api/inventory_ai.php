<?php
include '../db.php';

$message = $_POST['message'] ?? '';
$msg = strtolower(trim($message));

$response = "";
$found = false;

/* =========================
   GREETING
========================= */
if(preg_match('/hi|hello|hey|hii|hay|assalam/i', $msg)){
    echo "👋 Hello! আমি Inventory AI 🤖\nআপনি জানতে পারেন:\n📦 Products\n⚠️ Low Stock\n📂 Categories\n💸 Cheap Items\n🛒 Product Search";
    exit;
}

/* =========================
   SHOW ALL CATEGORIES
========================= */
if(preg_match('/category|categories|show category|all category/i', $msg)){

    $q = $conn->query("SELECT DISTINCT category FROM products");

    $response = "📂 <b>All Categories:</b><br>";

    while($r = $q->fetch_assoc()){
        $response .= "➡ " . $r['category'] . "<br>";
    }

    echo $response;
    exit;
}

/* =========================
   LOW STOCK (ALL)
========================= */
if(preg_match('/low stock|stock low/i', $msg)){

    $q = $conn->query("SELECT * FROM products WHERE quantity <= 10 ORDER BY quantity ASC");

    if($q->num_rows > 0){

        $response = "⚠️ <b>Low Stock Products:</b><br>";

        while($r = $q->fetch_assoc()){
            $response .= "➡ {$r['product_name']} (Stock: {$r['quantity']})<br>";
        }

        $response .= "<br>🔔 Please restock these products soon!";
    } else {
        $response = "✅ No low stock products!";
    }

    echo $response;
    exit;
}

/* =========================
   CHEAP PRODUCTS
========================= */
if(preg_match('/cheap|budget|low price/i', $msg)){

    $q = $conn->query("SELECT * FROM products ORDER BY price ASC LIMIT 5");

    $response = "💸 <b>Cheap Products:</b><br>";

    while($r = $q->fetch_assoc()){
        $response .= "➡ {$r['product_name']} - ৳{$r['price']}<br>";
    }

    echo $response;
    exit;
}


/* =========================
   SMART SINGLE PRODUCT SEARCH (FIXED)
========================= */


$matchedProducts = []; // 🔥 MUST DEFINE

$q = $conn->query("SELECT * FROM products");

while($p = $q->fetch_assoc()){

    $name = strtolower($p['product_name']);

    if(stripos($msg, $name) !== false){
        $matchedProducts[] = $p;
    }
}

/* =========================
   SHOW RESULT
========================= */

if(count($matchedProducts) > 0){

    foreach($matchedProducts as $p){

        $response = "🛒 <b>{$p['product_name']}</b><br>";
        $response .= "📂 Category: {$p['category']}<br>";
        $response .= "📦 Stock: {$p['quantity']}<br>";
        $response .= "💰 Price: ৳{$p['price']}<br>";

        if($p['quantity'] <= 10){
            $response .= "⚠️ Low stock! Restock soon.<br>";
        }

        $alt = $conn->query("
            SELECT * FROM products 
            WHERE category='{$p['category']}'
            AND id != {$p['id']}
            LIMIT 3
        ");

        if($alt->num_rows > 0){
            $response .= "<br>💡 Alternatives:<br>";

            while($a = $alt->fetch_assoc()){
                $response .= "➡ {$a['product_name']}<br>";
            }
        }

        echo $response;
        exit;
    }
}

/* =========================
   NOT FOUND
========================= */

echo "❌ Product not found.<br>💡 Try: rice, sugar, oil, soap";
exit;

/* =========================
   SHOW MULTIPLE PRODUCTS (FIXED RICE BUG)
========================= */
if(count($matchedProducts) > 0){

    foreach($matchedProducts as $p){

        $response .= "🛒 <b>{$p['product_name']}</b><br>";
        $response .= "📂 Category: {$p['category']}<br>";
        $response .= "📦 Stock: {$p['quantity']}<br>";
        $response .= "💰 Price: ৳{$p['price']}<br>";

        /* LOW STOCK WARNING */
        if($p['quantity'] <= 10){
            $response .= "⚠️ Low stock! Restock soon.<br>";
        }
    }
        /* =========================
   ALTERNATIVES (FIXED SMART)
========================= */

$type = strtolower($p['product_name']);

/* smart keyword detect */
if(stripos($type, 'sugar') !== false) $keyword = 'sugar';
elseif(stripos($type, 'rice') !== false) $keyword = 'rice';
elseif(stripos($type, 'oil') !== false) $keyword = 'oil';
elseif(stripos($type, 'milk') !== false) $keyword = 'milk';
elseif(stripos($type, 'soap') !== false) $keyword = 'soap';
elseif(stripos($type, 'tea') !== false) $keyword = 'tea';
elseif(stripos($type, 'coffee') !== false) $keyword = 'coffee';
else $keyword = $p['category'];

/* SMART alternatives query */
$alt = $conn->query("
    SELECT * FROM products 
    WHERE (
        LOWER(product_name) LIKE '%$keyword%'
        OR LOWER(category) LIKE '%$keyword%'
    )
    AND id != {$p['id']}
    LIMIT 3
");

if($alt->num_rows > 0){
    $response .= "<br>💡 Alternatives:<br>";

    while($a = $alt->fetch_assoc()){
        $response .= "➡ {$a['product_name']}<br>";
    }
}
echo $response;
    exit;  
}
/* =========================
   NOT FOUND (SMART SUGGESTION)
========================= */

$q = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 5");

$response = "❌ Product not found.<br><br>💡 Try these:<br>";

while($r = $q->fetch_assoc()){
    $response .= "➡ {$r['product_name']}<br>";
}

echo $response;
?>