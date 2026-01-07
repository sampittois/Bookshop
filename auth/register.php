<?php
require_once "../config.php";

$register_error = "";
$register_success = "";

if (!empty($_POST)) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    
    if ($password !== $password2) {
        $register_error = "Passwords do not match.";
    } else {
        $user = new User();
        if ($user->register($name, $email, $password)) {
            $register_success = "Registration successful! You can now log in.";
        } else {
            $register_error = "Email already exists or registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Bookshop</title>
  <link rel="stylesheet" href="../style/style.css">
</head>
<body>
   <div id="app">
    <h1>Register for Bookshop</h1>
    <nav class="nav--login">
        <a href="login.php">Log in</a>
        <a class="selected" href="register.php">Sign up</a>
    </nav>
  
    <?php if ($register_error): ?>
    <div class="alert"><?= htmlspecialchars($register_error) ?></div>
    <?php endif; ?>
    
    <?php if ($register_success): ?>
    <div class="alert" style="background-color: #74948C;"><?= htmlspecialchars($register_success) ?></div>
    <?php endif; ?>
  
  <form method="POST" class="form form--signup">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" required>
    
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
  
    <label for="password2">Confirm Password</label>
    <input type="password" id="password2" name="password2" required>
    
    <button type="submit" class="btn">Sign Up</button>
  </form>
  
  <p style="text-align: center; margin-top: 1rem; color: #CFB580;">
    Already have an account? <a href="login.php" style="color: #CA7C4C;">Log in</a>
  </p>
</div>
</body>
</html>