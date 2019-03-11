<html>

<head>
    <link rel="stylesheet" href="Style/style.css">
</head>

<body>
    <div class="login_form">

        <div class="login_label">
            <h3>Sing-in form</h3>
        </div>

        <div class="login_block">
            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <br>
                <input type="password" name="password" placeholder="Password" required>
                <br>
                <input class="submit_button" type="submit" value="Login">
            </form>
        </div>

        <div>
            <p>Login: datadog</p>
            <p>Password: 123</p>
        </div>


    </div>

</body>


<?php

$status = null;

if(isset($_GET['status'])) {
    $status = $_GET['status'];

    if ($status === 'unauthorized') {
        echo "<script>alert('Please login!')</script>";
    }

    if ($status === 'bad_credentials') {
        echo "<script>alert('Wrong credentials!')</script>";
    }
}
?>

</html>




