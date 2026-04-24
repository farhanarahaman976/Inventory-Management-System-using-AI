<?php
session_start();
include '../db.php';

if(!isset($_SESSION['user'])){
header("Location: ../user_login.php");
exit();
}
if(isset($_GET['new_chat'])){

    $user = $_SESSION['user'];
    $title = "New Chat";

    $conn->query("INSERT INTO chats (username,title) VALUES ('$user','$title')");

    $chat_id = $conn->insert_id;

    header("Location: user_dashboard.php?page=assistant&chat_id=".$chat_id);
    exit();

}
if(isset($_GET['delete_chat'])){

    $chat_id = (int) $_GET['delete_chat'];
    $user = $_SESSION['user'];

    $conn->query("DELETE FROM chat_history WHERE chat_id=$chat_id AND username='$user'");
    $conn->query("DELETE FROM chats WHERE id=$chat_id AND username='$user'");

    header("Location: user_dashboard.php?page=assistant");
    exit();
}
$page = isset($_GET['page']) ? $_GET['page'] : 'assistant';
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins, Arial;
}

body{
display:flex;
background:#f4f6f9;
height:100vh;
}

/* Sidebar */
.sidebar{
width:230px;
background:#2f3542;
color:white;
display:flex;
flex-direction:column;
overflow-y:auto;
}

.sidebar h2{
text-align:center;
padding:20px;
border-bottom:1px solid #555;
}

.sidebar a{
padding:15px 20px;
color:white;
text-decoration:none;
border-bottom:1px solid #555;
transition:0.3s;
}

.sidebar a:hover{
background:#3742fa;
}

.menu-box{
padding:10px;
border-bottom:1px solid #444;
}

.menu-title{
font-weight:bold;
color:#fff;
padding:10px;
}

.submenu{
display:flex;
flex-direction:column;
gap:5px;
padding-left:10px;
}

.sub-item{
color:#ddd;
text-decoration:none;
padding:6px 10px;
border-radius:6px;
transition:0.3s;
display:block;
}

.sub-item:hover{
background:#3742fa;
color:white;
}

.chat-row{
display:flex;
justify-content:space-between;
align-items:center;
padding-right:10px;
}

.delete-btn{
color:red;
text-decoration:none;
font-size:14px;
}

/* Main */
.main{
flex:1;
display:flex;
flex-direction:column;
}

/* Topbar */
.topbar{
height:280px;
background:linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)),
url('../images/topbar.jpg');
background-size:cover;
background-position:center;
color:white;
display:flex;
justify-content:space-between;
align-items:center;
padding:0 40px;
}

.topbar h3{
font-size:36px;
font-family:Arial;
font-weight:600;
color:#beaaf2;
text-shadow:0 0 10px rgba(181,126,220,0.5);
}

/* Content */
.content{
padding:30px;
overflow-y:auto;
}

/* Card */
.card{
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.08);
}

/* Search */
.search-box{
display:flex;
margin-top:20px;
max-width:450px;
border-radius:50px;
overflow:hidden;
border:1px solid #ddd;
}

.search-box input{
flex:1;
padding:12px 20px;
border:none;
outline:none;
}

.search-box button{
padding:12px 25px;
background:#3742fa;
border:none;
color:white;
cursor:pointer;
}

/* =========================
   CHAT BOX (UPDATED 🔥)
========================= */
.chat-box{
    height:60vh;              /* 🔥 responsive height */
    min-height:400px;
    max-height:75vh;

    flex:1;                   /* 🔥 auto grow */

    overflow-y:auto;
    background:#1e1e1e;
    padding:20px;
    border-radius:15px;

    display:flex;
    flex-direction:column;
    gap:12px;

    color:white;
    border:1px solid #333;

    scroll-behavior:smooth;
    box-shadow:0 5px 20px rgba(0,0,0,0.4);
}

/* =========================
   USER MESSAGE
========================= */
.user-msg{
    align-self:flex-end;
    background:linear-gradient(135deg,#6c5ce7,#341f97);
    color:white;
    padding:12px 18px;
    border-radius:20px;
    max-width:65%;
    word-wrap:break-word;
}

/* =========================
   AI MESSAGE
========================= */
.ai-msg{
    align-self:flex-start;
    background:#2d3436;
    color:#dfe6e9;
    padding:12px 18px;
    border-radius:20px;
    max-width:65%;
    word-wrap:break-word;
}

/* =========================
   INPUT AREA (UPDATED 🔥)
========================= */
.chat-input{
    position:sticky;          /* 🔥 always bottom */
    bottom:0;

    display:flex;
    gap:12px;
    margin-top:15px;

    background:#1e1e1e;
    padding:10px;
    border-radius:30px;

    box-shadow:0 -2px 10px rgba(0,0,0,0.3);
}

/* INPUT FIELD */
.chat-input input{
    flex:1;
    padding:12px 15px;
    border:1px solid #555;
    border-radius:25px;
    font-size:14px;
    outline:none;
    background:#2d3436;
    color:white;
}

.chat-input input::placeholder{
    color:#b2bec3;
}

.chat-input input:focus{
    border-color:#6c5ce7;
    box-shadow:0 0 5px rgb(245, 244, 250);
}

/* BUTTON */
.chat-input button{
    padding:12px 20px;
    background:linear-gradient(135deg,#6c5ce7,#341f97);
    color:white;
    border:none;
    border-radius:25px;
    cursor:pointer;
    transition:0.3s;
}

.chat-input button:hover{
  background:#341f97;
    transform:scale(1.05);
}
.voice-btn{
    width:45px;
    height:45px;
    border:none;
    border-radius:50%;
    background:linear-gradient(135deg,#6c5ce7,#341f97);
    color:white;
    font-size:18px;
    cursor:pointer;

    display:flex;
    align-items:center;
    justify-content:center;

    transition:0.3s;
}

.voice-btn:hover{
    transform:scale(1.1);
    background:#341f97;
}
/* Typing */
.typing{
align-self:flex-start;
background:#2d3436;
padding:10px 15px;
border-radius:20px;
display:flex;
gap:5px;
width:60px;
justify-content:center;
}

.typing span{
width:6px;
height:6px;
background:#aaa;
border-radius:50%;
animation:blink 1.4s infinite;
}

.typing span:nth-child(2){animation-delay:0.2s;}
.typing span:nth-child(3){animation-delay:0.4s;}

@keyframes blink{
0%{opacity:0.2;}
50%{opacity:1;}
100%{opacity:0.2;}
}

/* TABLE */
table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

table th,table td{
padding:10px;
border-bottom:1px solid #ddd;
text-align:center;
}

table th{
background:#3742fa;
color:white;
}
</style>
</head>

<body>

<div class="sidebar">

<h2>User Panel</h2>

<div class="menu-box">

    <div class="menu-title">🤖 AI Assistant</div>

    <div class="submenu">

        <a href="?page=assistant&new_chat=1" class="sub-item">
            ➕ New Chat
        </a>

        <?php
        $user = $_SESSION['user'] ?? '';

        if(!empty($user)){

            $stmt = $conn->prepare("SELECT id, title FROM chats WHERE username=? ORDER BY id DESC");
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();

            while($row = $result->fetch_assoc()){
                echo "
                <div class='chat-row'>
                    <a href='?page=assistant&chat_id={$row['id']}' class='sub-item'>
                        💬 {$row['title']}
                    </a>

                    <a href='?delete_chat={$row['id']}' 
                       onclick='return confirm(\"Delete chat?\")'
                       class='delete-btn'>
                       🗑
                    </a>
                </div>";
            }
        }
        ?>

    </div>
</div>
<a href="?page=products">📦 Available Products</a>
<a href="?page=search">🔎 Search Product</a>
<a href="?page=request">📋 My Requests</a>

<a href="../index.php">⚙️ Admin Panel</a>
<a href="../logout.php">🚪 Logout</a>


</div>

<div class="main">

<div class="topbar">

<h3>Inventory User dashboard</h3>


</div>

<div class="content">

<?php

/* AI CHAT */

if($page=='assistant'){
?>

<div class="card">

<h2>AI Inventory Assistant</h2>

<div class="chat-box" id="chat">

<?php

$chat_id = $_GET['chat_id'] ?? 0;

if($chat_id > 0){

    $stmt = $conn->prepare("SELECT role, message FROM chat_history WHERE chat_id=? ORDER BY id ASC");
    $stmt->bind_param("i", $chat_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){

        if($row['role'] == 'user'){
            echo "<div class='user-msg'>{$row['message']}</div>";
        } else {
            echo "<div class='ai-msg'>{$row['message']}</div>";
        }
    }
}
?>

</div>

<div class="chat-input">

<input type="text" id="message" placeholder="Ask about inventory...">

<button onclick="send()">Send</button>
<button onclick="startVoice()" class="voice-btn">
    🎙️
</button>

</div>

</div>

<script>

/* =========================
   SEND MESSAGE FUNCTION
========================= */
function send(){

    var msgInput = document.getElementById("message");
    var msg = msgInput.value;

    if(msg.trim() == "") return;

    var chat = document.getElementById("chat");
    var chat_id = new URLSearchParams(window.location.search).get("chat_id");

    // Show user message
    chat.innerHTML += "<div class='user-msg'>"+msg+"</div>";

    // Typing animation
    chat.innerHTML += "<div class='typing' id='typing'><span></span><span></span><span></span></div>";
    chat.scrollTop = chat.scrollHeight;

   fetch("../api/inventory_ai.php", {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: "message="+encodeURIComponent(msg)+"&chat_id="+chat_id
    })
    .then(res => res.text())
    .then(data => {

        // Remove typing
        document.getElementById("typing").remove();

        // Show AI reply
        chat.innerHTML += "<div class='ai-msg'>"+data+"</div>";
        chat.scrollTop = chat.scrollHeight;
    })
    .catch(err => {
        document.getElementById("typing").remove();
        chat.innerHTML += "<div class='ai-msg'>❌ Error: API not responding</div>";
    });

    // Clear input
    msgInput.value = "";
}


/* =========================
   ENTER KEY SUPPORT 🔥
========================= */
document.addEventListener("DOMContentLoaded", function(){

    const input = document.getElementById("message");

    input.addEventListener("keydown", function(e){
        if(e.key === "Enter"){
            e.preventDefault();
            send();
        }
    });

});


/* =========================
   🎤 VOICE INPUT
========================= */
function startVoice(){

    let recognition = new webkitSpeechRecognition();

    recognition.lang = "en-US"; // চাইলে "bn-BD" করতে পারো

    recognition.onresult = function(e){
        let voiceText = e.results[0][0].transcript;
        document.getElementById("message").value = voiceText;
    }

    recognition.start();
}

</script>
<?php
}


/* PRODUCTS */

elseif($page=='products'){

$result=$conn->query("SELECT * FROM products");

echo "<div class='card'><h2>Available Products</h2>";

echo "<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Category</th>
<th>Stock</th>
<th>Price</th>
</tr>";

while($row=$result->fetch_assoc()){

echo "<tr>

<td>".$row['id']."</td>
<td>".$row['product_name']."</td>
<td>".$row['category']."</td>
<td>".$row['quantity']."</td>
<td>".$row['price']."</td>

</tr>";

}

echo "</table></div>";

}


/* SEARCH */

elseif($page=='search'){
?>

<div class="card">

<h2>Search Product</h2>

<form method="GET">

<input type="hidden" name="page" value="search">

<div class="search-box">

<input type="text" name="search" placeholder="Search product name...">

<button>Search</button>

</div>

</form>

<?php

if(isset($_GET['search'])){

$s=$conn->real_escape_string($_GET['search']);

$q=$conn->query("SELECT * FROM products WHERE product_name LIKE '%$s%'");

if($q->num_rows>0){

echo "<br>";

while($r=$q->fetch_assoc()){

echo "<p><b>".$r['product_name']."</b> | Category: ".$r['category']." | Stock: ".$r['quantity']."</p>";

}

}

else{

echo "<p style='margin-top:15px;color:red;'>Product Not Found</p>";

}

}

?>

</div>

<?php
}


/* REQUEST */

elseif($page=='request'){
?>

<div class="card">

<h2>Request Product</h2>

<form method="POST">

<input type="text" name="product_name" placeholder="Product Name" required style="padding:10px;width:250px;margin:10px 0;">

<br>

<input type="number" name="quantity" placeholder="Quantity" required style="padding:10px;width:250px;margin:10px 0;">

<br>

<button name="send_request" style="padding:10px 20px;background:#3742fa;color:white;border:none;border-radius:5px;">
Send Request
</button>

</form>

<?php

if(isset($_POST['send_request'])){

$user = $conn->real_escape_string($_SESSION['user']);
$product = $conn->real_escape_string($_POST['product_name']);
$qty = (int) $_POST['quantity'];

$conn->query("INSERT INTO product_requests(username,product_name,quantity)
VALUES('$user','$product','$qty')");

echo "<p style='color:green;margin-top:10px;'>Request Sent Successfully</p>";

}

?>

<hr style="margin:20px 0;">

<h3>My Requests</h3>

<table>

<tr>
<th>ID</th>
<th>Product</th>
<th>Quantity</th>
<th>Status</th>
</tr>

<?php

$user=$_SESSION['user'];

$q=$conn->query("SELECT * FROM product_requests WHERE username='$user'");

while($r=$q->fetch_assoc()){

echo "<tr>

<td>".$r['id']."</td>
<td>".$r['product_name']."</td>
<td>".$r['quantity']."</td>
<td>".$r['status']."</td>

</tr>";

}

?>

</table>

</div>

<?php
}

?>

</div>
</div>

</body>
</html>