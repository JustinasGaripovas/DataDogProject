<?php session_start(); ?>
<html>

<title> DD | PHP Homework</title>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>

<div class="login-form-container">
    <form method="POST" class="login-form" action="login.php" >
        <input type="text" name="username" placeholder="Username">
        <br>
        <input type="password" name="password" placeholder="Password">
        <br>
        <input class="submit-button" type="submit" value="Login">
    </form>


</div>

</body>


</html>