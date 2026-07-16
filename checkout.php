<?php require 'db.php';
if(!isset($_SESSION['uid'])){header("Location: login.php");exit;}
$uid=(int)$_SESSION['uid'];
$res=$conn->query("SELECT c.id cid,c.qty,p.name,p.price FROM cart c JOIN pet_items p ON c.item_id=p.id WHERE c.user_id=$uid");
$rows=[]; $total=0;
while($r=$res->fetch_assoc()){ $r['sub']=$r['price']*$r['qty']; $total+=$r['sub']; $rows[]=$r; }
if(!$rows){ header("Location: cart.php"); exit; }
$delivery = $total>=500 ? 0 : 50;
$grand = $total + $delivery;
$err=""; $success_id=0;
if($_SERVER['REQUEST_METHOD']=='POST'){
  $addr=trim($_POST['address']); $phone=trim($_POST['phone']);
  if(!$addr || !$phone){ $err="Address and phone are required."; }
  elseif(!preg_match('/^\d{10}$/',$phone)){ $err="Enter a valid 10-digit phone number."; }
  else{
    $addrE=$conn->real_escape_string($addr);
    $phoneE=$conn->real_escape_string($phone);
    $conn->query("INSERT INTO orders(user_id,total,delivery_fee,grand_total,address,phone) VALUES($uid,$total,$delivery,$grand,'$addrE','$phoneE')");
    $oid=$conn->insert_id;
    foreach($rows as $r){
      $n=$conn->real_escape_string($r['name']);
      $conn->query("INSERT INTO order_items(order_id,item_name,qty,price) VALUES($oid,'$n',".$r['qty'].",".$r['price'].")");
    }
    $conn->query("DELETE FROM cart WHERE user_id=$uid");
    $success_id=$oid;
  }
}
$result = $conn->query("SELECT * FROM users WHERE id=$uid");

if($result){
    $user = $result->fetch_assoc();
} else {
    $user = [];
}

$user['address'] = $user['address'] ?? '';
$user['phone'] = $user['phone'] ?? '';?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="style.css"><title>Checkout</title></head><body>
<div class="navbar"><div class="brand">🐾 Pet Care</div>
<div><a href="dashboard.php">Doctors</a><a href="items.php">Shop</a><a href="cart.php">Cart 🛒 (<?=cart_count($conn)?>)</a><a href="appointments.php">Appointments</a><a href="logout.php">Logout</a></div></div>
<div class="container">
<?php if($success_id): ?>
  <div class="success big-success">
    <h2>✅ Order Placed Successfully!</h2>
    <p>Your order <b>#<?=$success_id?></b> is confirmed.</p>
    <p><b>Total Paid (COD):</b> ₹<?=$grand?></p>
    <p>🚚 <b>Door Delivery</b> in 2-4 business days.</p>
    <a href="items.php" class="btn">Continue Shopping</a>
  </div>
<?php else: ?>
<h2>🚚 Checkout - Door Delivery</h2>
<?php if($err):?><div class="error"><?=$err?></div><?php endif;?>
<div class="two-col">
  <div>
    <h3>Delivery Details</h3>
    <form method="post">
      <label>Full Delivery Address</label>
      <textarea name="address" rows="4" required placeholder="House no, Street, City, State, Pincode"><?=htmlspecialchars($_POST['address']??$user['address']??'')?></textarea>
      <label>Phone Number (10 digits)</label>
      <input name="phone" required pattern="\d{10}" value="<?=htmlspecialchars($_POST['phone']??$user['phone']??'')?>">
      <label>Payment Method</label>
      <select><option>Cash on Delivery (COD)</option></select>
      <button type="submit" class="big">Place Order · ₹<?=$grand?></button>
    </form>
  </div>
  <div>
    <h3>Order Summary</h3>
    <div class="summary">
      <?php foreach($rows as $r): ?>
        <div class="row"><span><?=$r['name']?> × <?=$r['qty']?></span><span>₹<?=$r['sub']?></span></div>
      <?php endforeach; ?>
      <hr>
      <div class="row"><span>Items Total</span><span>₹<?=$total?></span></div>
      <div class="row"><span>Door Delivery</span><span><?=$delivery==0?'FREE':'₹'.$delivery?></span></div>
      <div class="row grand"><span>Grand Total</span><span>₹<?=$grand?></span></div>
    </div>
  </div>
</div>
<?php endif; ?>
</div></body></html>
