<?php
    function validLogin($email, $password) {
        if($email === "user@user.com" && $password === "User") {
            return true;
        }
        if($email === "admin@admin.com" && $password === "Admin") {
            return true;
        }
        return false;
    }

    if(!empty($_POST)) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        if(validLogin($email, $password)) {
            $salt = "cnbhvgryeujifkjn*@++++gftyzshinjhzisv%%ghj";
            $cookieVal = $email . "," . md5($email.$salt);
            setcookie("loggedin", $cookieVal, time() + (60*60*24*30)); // 1 month
            header("Location: index.php");
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
                <h2 form__title>Sign In</h2>

                <?php if(isset($login_error)): ?>

                    <div class="form__error">
                        <p>
                            <?php echo $login_error; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="form__field">
                    <label for="Email">Email</label>
                    <input type="text" name="email">
                </div>
                <div class="form__field">
                    <label for="Password">Password</label>
                    <input type="password" name="password">
                </div>

                <div class="form__field">
                    <input type="submit" value="Sign in" class="btn btn--primary">
                </div>
            </form>
        </div>
    </div>
</body>
</html>