<?php
require_once "../config.php";

$login_error = "";
$debug_info = "";

// Ensure users exist
try {
  $userObj = new User();
  $userObj->createUser('Admin User', 'admin@admin.com', 'Admin', 'admin', 1000.00);
  $userObj->createUser('Regular User', 'user@user.com', 'User', 'customer', 100.00);
} catch (Exception $e) {
  $debug_info = "Setup error: " . $e->getMessage();
}

if (!empty($_POST)) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = new User();
    if ($user->login($email, $password)) {
        header("Location: ../index.php");
        exit();
    } else {
        $login_error = "Email or password is incorrect. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Bookshop</title>
  <link rel="stylesheet" href="../style/style.css">
</head>
<body>
   <div id="app">
    <h1>Log in to Bookshop</h1>
    <nav class="nav--login">
        <a class="selected" href="login.php">Log in</a>
        <a href="register.php">Sign up</a>
    </nav>
  
    <?php if ($login_error): ?>
    <div class="alert"><?= htmlspecialchars($login_error) ?></div>
    <?php endif; ?>
    
    <?php if ($debug_info): ?>
    <div class="alert"><?= htmlspecialchars($debug_info) ?></div>
    <?php endif; ?>
  
  <form method="POST" class="form form--login">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
  
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit" class="btn">Log In</button>
  </form>
  
  <div style="margin-top: 1rem; padding: 1rem; background: rgba(0,0,0,0.1); border-radius: 6px; font-size: 0.85rem;">
    <strong>Test Accounts:</strong><br>
    admin@admin.com / Admin<br>
    user@user.com / User
  </div>
</div>
</body>
</html>