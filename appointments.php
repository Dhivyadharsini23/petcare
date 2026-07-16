<?php require 'db.php';
if(!isset($_SESSION['uid'])){header("Location: login.php");exit;}
$uid=$_SESSION['uid'];
if(isset($_GET['del'])){
  $id=intval($_GET['del']);
  $conn->query("DELETE FROM appointments WHERE id=$id AND user_id=$uid");
  header("Location: appointments.php");exit;
}
$msg="";
if($_SERVER['REQUEST_METHOD']=='POST'){
  $id=intval($_POST['id']); $date=$_POST['date']; $issue=trim($_POST['issue']);
  if($date && $issue){
    $stmt=$conn->prepare("UPDATE appointments SET appt_date=?,pet_issue=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssii",$date,$issue,$id,$uid);
    $stmt->execute(); $msg="Appointment updated.";
  }
}
$res=$conn->query("SELECT a.*,d.name AS dname FROM appointments a JOIN doctors d ON a.doctor_id=d.id WHERE a.user_id=$uid ORDER BY a.appt_date DESC");
$orders=$conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY created_at DESC");
?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="style.css"><title>Appointments</title></head><body>
<div class="navbar"><div class="brand">🐾 Pet Care</div>
<div><a href="dashboard.php">Doctors</a><a href="items.php">Shop</a><a href="cart.php">Cart 🛒 (<?=cart_count($conn)?>)</a><a href="appointments.php">Appointments</a><a href="logout.php">Logout</a></div></div>
<div class="container">
<h2>📅 My Appointments</h2>
<?php if($msg) echo "<div class='success'>$msg</div>"; ?>
<?php if(!$res->num_rows): ?><p>No appointments yet.</p><?php else: ?>
<table><tr><th>Doctor</th><th>Pet</th><th>Issue</th><th>Date</th><th>Actions</th></tr>
<?php while($a=$res->fetch_assoc()): ?>
<tr>
<form method="post">
  <td><?=$a['dname']?></td>
  <td><?=$a['pet_name']?></td>
  <td><input name="issue" value="<?=htmlspecialchars($a['pet_issue'])?>"></td>
  <td><input type="date" name="date" value="<?=$a['appt_date']?>"></td>
  <td>
    <input type="hidden" name="id" value="<?=$a['id']?>">
    <button>Update</button>
    <a class="btn" style="background:#c53030" href="?del=<?=$a['id']?>" onclick="return confirm('Delete?')">Delete</a>
  </td>
</form>
</tr>
<?php endwhile; ?>
</table>
<?php endif; ?>

<h2 style="margin-top:36px">📦 My Orders</h2>
<?php if(!$orders->num_rows): ?><p>No orders yet. <a href="items.php" class="btn">Shop now</a></p><?php else: ?>
<table><tr><th>Order #</th><th>Total</th><th>Delivery</th><th>Address</th><th>Status</th><th>Date</th></tr>
<?php while($o=$orders->fetch_assoc()): ?>
<tr><td>#<?=$o['id']?></td><td>₹<?=$o['grand_total']?></td><td><?=$o['delivery_fee']==0?'FREE':'₹'.$o['delivery_fee']?></td>
<td><?=htmlspecialchars($o['address'])?></td><td><?=$o['status']?></td><td><?=date('d M Y',strtotime($o['created_at']))?></td></tr>
<?php endwhile; ?>
</table>
<?php endif; ?>
</div></body></html>
