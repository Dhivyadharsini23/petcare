<?php
session_start();
$conn = new mysqli("localhost","root","","petcare",3307);
if($conn->connect_error){ die("Database connection failed: ".$conn->connect_error); }
function cart_count($conn){
  if(!isset($_SESSION['uid'])) return 0;
  $uid=(int)$_SESSION['uid'];
  $r=$conn->query("SELECT COALESCE(SUM(qty),0) c FROM cart WHERE user_id=$uid");
  return (int)$r->fetch_assoc()['c'];
}
?>
