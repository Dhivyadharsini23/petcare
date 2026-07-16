<?php require 'db.php';
if(!isset($_SESSION['uid'])){header("Location: login.php");exit;}
$loc = isset($_GET['loc']) && $_GET['loc']!=='' ? $_GET['loc'] : $_SESSION['uloc'];
$locE=$conn->real_escape_string($loc);
$sort = $_GET['sort'] ?? 'rating';
$order = $sort=='fee' ? 'fee ASC' : ($sort=='exp' ? 'experience DESC' : 'rating DESC');
$res=$conn->query("SELECT * FROM doctors WHERE location='$locE' ORDER BY $order");
$locs=$conn->query("SELECT DISTINCT location FROM doctors ORDER BY location");
?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="style.css"><title>Doctors</title></head><body>
<div class="navbar"><div class="brand">🐾 Pet Care</div>
<div><a href="dashboard.php">Doctors</a><a href="items.php">Shop</a><a href="cart.php">Cart 🛒 (<?=cart_count($conn)?>)</a><a href="appointments.php">Appointments</a><a href="logout.php">Logout (<?=$_SESSION['uname']?>)</a></div></div>
<div class="container">
<h2>👨‍⚕️ Doctors in <?=$loc?></h2>
<p class="sub">Choose a certified vet and book instantly</p>
<form method="get" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px">
  <select name="loc" onchange="this.form.submit()" style="width:auto">
    <?php while($l=$locs->fetch_assoc()): ?>
      <option value="<?=$l['location']?>" <?=$l['location']==$loc?'selected':''?>><?=$l['location']?></option>
    <?php endwhile; ?>
  </select>
  <select name="sort" onchange="this.form.submit()" style="width:auto">
    <option value="rating" <?=$sort=='rating'?'selected':''?>>Top Rated</option>
    <option value="fee" <?=$sort=='fee'?'selected':''?>>Lowest Fee</option>
    <option value="exp" <?=$sort=='exp'?'selected':''?>>Most Experienced</option>
  </select>
</form>
<div class="doctors-grid">
<?php if(!$res->num_rows): ?>
  <p>No doctors available in <?=$loc?>.</p>
<?php endif; while($d=$res->fetch_assoc()): ?>
  <div class="doc-card">
    <h3><?=$d['name']?></h3>
    <div class="spec"><?=$d['specialization']?></div>
    <div class="meta">
      📍 <?=$d['location']?><br>
      <span class="rating">★ <?=$d['rating']?></span> · <?=$d['experience']?> yrs exp<br>
      💰 Consultation Fee: ₹<?=$d['fee']?>
    </div>
    <a class="btn" href="book.php?doc=<?=$d['id']?>">Book Appointment</a>
  </div>
<?php endwhile; ?>
</div>
</div></body></html>
