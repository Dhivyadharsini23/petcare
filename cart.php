<?php require 'db.php';
if(!isset($_SESSION['uid'])){header("Location: login.php");exit;}
$uid=(int)$_SESSION['uid'];
if(isset($_GET['remove'])){
  $rid=(int)$_GET['remove'];
  $conn->query("DELETE FROM cart WHERE id=$rid AND user_id=$uid");
  header("Location: cart.php"); exit;
}
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['update'])){
  foreach($_POST['qty'] as $cid=>$q){
    $cid=(int)$cid; $q=max(1,(int)$q);
    $conn->query("UPDATE cart SET qty=$q WHERE id=$cid AND user_id=$uid");
  }
  header("Location: cart.php"); exit;
}
$res=$conn->query("SELECT c.id cid,c.qty,p.name,p.price,p.id pid FROM cart c JOIN pet_items p ON c.item_id=p.id WHERE c.user_id=$uid");
$rows=[]; $total=0;
while($r=$res->fetch_assoc()){ $r['sub']=$r['price']*$r['qty']; $total+=$r['sub']; $rows[]=$r; }
$delivery = $total==0 ? 0 : ($total>=500 ? 0 : 50);
$grand = $total + $delivery;
?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="style.css"><title>Cart</title></head><body>
<div class="navbar"><div class="brand">🐾 Pet Care</div>
<div><a href="dashboard.php">Doctors</a><a href="items.php">Shop</a><a href="cart.php">Cart 🛒 (<?=cart_count($conn)?>)</a><a href="appointments.php">Appointments</a><a href="logout.php">Logout</a></div></div>
<div class="container">
<h2>🛒 Your Cart</h2>
<?php if(!$rows): ?>
  <p>Your cart is empty. <a href="items.php" class="btn">Browse items</a></p>
<?php else: ?>
<form method="post">
<table>
<tr><th>Item</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr>
<?php foreach($rows as $r): ?>
<tr>
  <td><?=$r['name']?></td>
  <td>₹<?=$r['price']?></td>
  <td><input type="number" name="qty[<?=$r['cid']?>]" value="<?=$r['qty']?>" min="1" style="width:70px"></td>
  <td>₹<?=$r['sub']?></td>
  <td><a class="link-del" href="cart.php?remove=<?=$r['cid']?>">Remove</a></td>
</tr>
<?php endforeach; ?>
</table>
<div style="margin-top:12px"><button type="submit" name="update" value="1">Update Quantities</button></div>
</form>

<div class="summary">
  <div class="row"><span>Items Total</span><span>₹<?=$total?></span></div>
  <div class="row"><span>Door Delivery Fee <?= $delivery==0?'<em style="color:#38a169">(FREE)</em>':'' ?></span><span>₹<?=$delivery?></span></div>
  <div class="row grand"><span>Grand Total</span><span>₹<?=$grand?></span></div>
  <?php if($total<500): ?><p class="hint">💡 Add ₹<?=500-$total?> more for FREE door delivery!</p><?php endif; ?>
  <a href="checkout.php" class="btn big">Proceed to Checkout →</a>
</div>
<?php endif; ?>
</div></body></html>
