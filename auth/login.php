<?php
require_once "../config.php";

// If already logged in, go to home
if (User::isLoggedIn()) {
    header("Location: ../index.php");
    exit();
}

$login_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    $user = new User();
    if ($user->login($email, $password)) {
        header("Location: ../index.php");
        exit();
    } else {
        $login_error = "Couldn't log you in with those details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nook & Novel login</title>
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>
    <div class="shopLogin">
        <div class="form form--login">
            <form action="" method="post">
                <h2 class="form__title">Sign In</h2>

                <?php if(isset($login_error)): ?>

                    <div class="form__error">
                        <p>
                            <?php echo $login_error; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="form__field">
                    <label for="email">Email</label>
                    <input id="email" type="text" name="email" autocomplete="username" required>
                </div>
                <div class="form__field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" required>
                </div>

                <div class="form__field">
                    <input type="submit" value="Sign in" class="btn btn--primary">
                </div>
            </form>
        </div>
    </div>
</body>
</html>