<?php
require 'db.php';

$err = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $loc = trim($_POST['location']);

    if(!$name || !$email || !$pass || !$loc){
        $err = "All fields are required.";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $err = "Invalid email address.";
    }
    elseif(strlen($pass) < 6){
        $err = "Password must be at least 6 characters.";
    }
    else{

        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users(name,email,password,location) VALUES(?,?,?,?)");

        if(!$stmt){
            die("Prepare Failed : " . $conn->error);
        }

        $stmt->bind_param("ssss", $name, $email, $hash, $loc);

        if($stmt->execute()){
            header("Location: login.php");
            exit;
        }
        else{
            $err = "Email already registered.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container" style="max-width:480px;margin-top:60px">

<h2>🐾 Pet Care - Register</h2>

<p class="sub">Create your account to book vets and shop pet items</p>

<?php
if($err){
    echo "<div class='error'>$err</div>";
}
?>

<form method="post">

    <input type="text" name="name" placeholder="Full Name" required>

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password (Minimum 6 characters)" required>

    <select name="location" required>
        <option value="">-- Choose Your City --</option>
        <option>Chennai</option>
        <option>Bangalore</option>
        <option>Mumbai</option>
        <option>Delhi</option>
        <option>Kochi</option>
        <option>Coimbatore</option>
        <option>Pune</option>
    </select>

    <button class="big">Create Account</button>

</form>

<p style="margin-top:15px;text-align:center;">
    Already have an account?
    <a href="login.php">Login</a>
</p>

</div>

</body>
</html>