<?php session_start();


if (isset( $_GET["status"])){
    $status = $_GET["status"];
    if ( $status === "unauthorized") echo "<script> alert('Unauthorized login') </script>";
    if ( $status === "incorrect") echo "<script> alert('Incorrect credentials') </script>";
}


?>
<html>



<head>

<?php include "header.php"; ?>


</head>
<body>
<video autoplay muted loop id="backgroundVideo">
    <source src="Resources/background.mp4" type="video/mp4">
</video>

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