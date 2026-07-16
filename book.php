<?php require 'db.php';
if(!isset($_SESSION['uid'])){header("Location: login.php");exit;}
$did=intval($_GET['doc']??0); $err="";
$doc=$conn->query("SELECT * FROM doctors WHERE id=$did")->fetch_assoc();
if(!$doc){die("Doctor not found");}
if($_SERVER['REQUEST_METHOD']=='POST'){
  $pet=trim($_POST['pet']); $issue=trim($_POST['issue']); $date=$_POST['date'];
  if(!$pet||!$issue||!$date) $err="Fill all fields.";
  elseif(strtotime($date)<strtotime(date('Y-m-d'))) $err="Date must be future.";
  else{
    $stmt=$conn->prepare("INSERT INTO appointments(user_id,doctor_id,pet_name,pet_issue,appt_date) VALUES(?,?,?,?,?)");
    $stmt->bind_param("iisss",$_SESSION['uid'],$did,$pet,$issue,$date);
    if($stmt->execute()){header("Location: appointments.php");exit;}
    else $err="Error: ".$conn->error;
  }
}
?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="style.css"><title>Book</title></head><body>
<div class="navbar"><div>🐾 Pet Care</div><a href="dashboard.php" style="color:#fff">← Back</a></div>
<div class="container" style="max-width:500px">
<h2>Book with <?=$doc['name']?></h2>
<?php if($err) echo "<div class='error'>$err</div>"; ?>
<form method="post">
  <input name="pet" placeholder="Pet Name" required>
  <textarea name="issue" placeholder="What happened to your pet?" required></textarea>
  <input name="date" type="date" required>
  <button>Confirm Booking</button>
</form></div></body></html>
