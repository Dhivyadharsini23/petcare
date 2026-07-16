<?php require 'db.php'; $err="";
if($_SERVER['REQUEST_METHOD']=='POST'){
  $email=trim($_POST['email']); $pass=$_POST['password'];
  if(!$email||!$pass) $err="Enter email & password.";
  else{
    $stmt=$conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email); $stmt->execute();
    $r=$stmt->get_result()->fetch_assoc();
    if($r && password_verify($pass,$r['password'])){
      $_SESSION['uid']=$r['id']; $_SESSION['uname']=$r['name']; $_SESSION['uloc']=$r['location'];
      header("Location: dashboard.php"); exit;
    } else $err="Invalid credentials.";
  }
}
?>
<!DOCTYPE html><html><head><link rel="stylesheet" href="style.css"><title>Login</title></head><body>
<div class="container" style="max-width:400px">
<h2>🐾 Pet Care - Login</h2>
<?php if($err) echo "<div class='error'>$err</div>"; ?>
<form method="post">
  <input name="email" type="email" placeholder="Email" required>
  <input name="password" type="password" placeholder="Password" required>
  <button>Login</button>
</form>
<p style="margin-top:12px">New user? <a href="register.php">Register</a></p>
</div></body></html>
