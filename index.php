<?php session_start();

if (isset( $_GET["status"])){
    $status = $_GET["status"];
    if ( $status === "unauthorized") echo "<script> alert('Unauthorized login') </script>";
    if ( $status === "incorrect") echo "<script> alert('Incorrect credentials') </script>";
}


?>
<html>

<title> DD | PHP Homework</title>
<head>
    <link rel="stylesheet" type="text/css" href="style.css?ver=0.1">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>

<div class="login-form-container">

    <div class="login-form-logos">
        <img src="Resources/dd-logo.png" >
        <img src="Resources/ktu-logo.png" >

    </div>
    <form method="POST" class="login-form" action="login.php" >
        <input type="text" name="username" placeholder="Username" required>
        <br>
        <input type="password" name="password" placeholder="Password" required>
        <br>
        <input class="submit-button" type="submit" value="Login">
    </form>


</div>

</body>


</html>