<?php
require_once "../config.php";

if (!User::isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$feedback = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current'] ?? '';
    $new = $_POST['new'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($new !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($new) < 6) {
        $error = "Choose a stronger password (min 6 chars).";
    } else {
        $user = new User();
        if ($user->changePassword($_SESSION['user']['id'], $current, $new)) {
            $feedback = "Password updated.";
        } else {
            $error = "Current password is incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/style.css">
  <title>Change Password</title>
</head>
<body>
  <main class="detail">
    <h1>Change password</h1>
    <?php if ($feedback): ?><div class="pill" role="status"><?= htmlspecialchars($feedback) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="pill" role="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post" class="form form--vertical">
      <label>Current password
        <input type="password" name="current" required>
      </label>
      <label>New password
        <input type="password" name="new" required>
      </label>
      <label>Confirm new password
        <input type="password" name="confirm" required>
      </label>
      <button class="btn" type="submit">Update</button>
      <a class="btn btn--ghost" href="../index.php">Back</a>
    </form>
  </main>
</body>
</html>
