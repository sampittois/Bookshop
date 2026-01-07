<?php
    function validLogin($email, $password) {
        if($email === "nook@novel" && $password === "nnn") {
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
            $login_error = "That password was incorrect. Please try again";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <div id="app">
    <h1>Log in to Nook&Novel</h1>
    <nav class="nav--login">
        <a class="selected" href="#" id="tabLogin">Log in</a>
        <a href="#" id="tabSignIn">Sign up</a>
    </nav>
  
    <div class="alert hidden">That password was incorrect. Please try again</div>
  
  <div class="form form--login">
    <label for="username">Username</label>
    <input type="text" id="username">
  
    <label for="password">Password</label>
    <input type="password" id="password">
  </div>
  
  <div class="form form--signup hidden">
    <!-- <label for="username2">Username</label>
    <input type="text" id="username2"> -->
  
    <label for="password2">Confirm Password</label>
    <input type="password" id="password2">
    
    <label for="email">Email</label>
    <input type="text" id="email">
  </div>
  
  <a href="#" class="btn" id="btnSubmit">Log In</a>
</div>
</body>
</html>