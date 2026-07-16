<?php require 'db.php';
if(!isset($_SESSION['uid'])){header("Location: login.php");exit;}
$msg="";
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['add_id'])){
  $iid=(int)$_POST['add_id']; $uid=(int)$_SESSION['uid'];
  $qty=max(1,(int)$_POST['qty']);
  $chk=$conn->query("SELECT id,qty FROM cart WHERE user_id=$uid AND item_id=$iid");
  if($chk->num_rows){
    $row=$chk->fetch_assoc();
    $nq=$row['qty']+$qty;
    $conn->query("UPDATE cart SET qty=$nq WHERE id=".$row['id']);
  } else {
    $conn->query("INSERT INTO cart(user_id,item_id,qty) VALUES($uid,$iid,$qty)");
  }
  $msg="Item added to cart!";
}
$cat = isset($_GET['cat']) ? $_GET['cat'] : '';
$sql = "SELECT * FROM pet_items";
if($cat){ $c=$conn->real_escape_string($cat); $sql.=" WHERE category='$c'"; }
$sql.=" ORDER BY category,name";
$res=$conn->query($sql);
$cats=$conn->query("SELECT DISTINCT category FROM pet_items");
?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="style.css"><title>Pet Items Store</title></head><body>
<div class="navbar"><div class="brand">🐾 Pet Care</div>
<div><a href="dashboard.php">Doctors</a><a href="items.php">Shop</a><a href="cart.php">Cart 🛒 (<?=cart_count($conn)?>)</a><a href="appointments.php">Appointments</a><a href="logout.php">Logout</a></div></div>
<div class="container">
<h2>🛍️ Pet Items Store</h2>
<p class="sub">Free door delivery on orders above ₹500 · Cash on Delivery available</p>
<?php if($msg):?><div class="success"><?=$msg?> <a href="cart.php">View cart →</a></div><?php endif;?>
<div class="chips">
  <a class="chip <?=$cat===''?'active':''?>" href="items.php">All</a>
  <?php while($c=$cats->fetch_assoc()): ?>
    <a class="chip <?=$cat===$c['category']?'active':''?>" href="items.php?cat=<?=urlencode($c['category'])?>"><?=$c['category']?></a>
  <?php endwhile;?>
</div>
<div class="grid">
<?php while($i=$res->fetch_assoc()): ?>
  <div class="product">
    <div class="pcat"><?=$i['category']?></div>
    <h3><?=$i['name']?></h3>
    <p class="desc"><?=$i['description']?></p>
    <div class="price">₹<?=$i['price']?></div>
    <div class="stock"><?=$i['stock']?> in stock</div>
    <form method="post" class="buyform">
      <input type="hidden" name="add_id" value="<?=$i['id']?>">
      <input type="number" name="qty" value="1" min="1" max="<?=$i['stock']?>">
      <button type="submit">Add to Cart</button>
    </form>
  </div>
<?php endwhile; ?>
</div>
</div></body></html>
