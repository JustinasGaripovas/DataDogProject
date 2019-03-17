<!DOCTYPE html>
<?php
//require_once('User.php');
//$users = array(new User('email1', 'passw1'), new User('email2', 'passw2'), new User('email3', 'passw3'));

$conn = new mysqli('localhost', 'root', '', 'login');

if($conn->connect_error){
    die('Connection failed: ' . $conn->connect_error);
}

session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    header('Location: loggedIn.php');
    exit;
}

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $email_found = false;

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $result = $conn->query($sql);

    if($result->num_rows == 0){
        echo 'email is not registered';
        die;
    }
    else{
        $row = $result->fetch_assoc();
        if(!password_verify($password, $row['password'])){
            echo 'wrong password';
        }
        else{
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            header('Location: loggedIn.php');
        }
    }
}

$conn->close();
?>

<html>
<head>
    <meta charset="utf-8">
    <title>Login form</title>
</head>
<body>
    <form action="index.php" method="POST">
        Email:
        <input name="email" type="text" placeholder="enter your email" required/> <br/>
        Password:
        <input name="password" type="password" placeholder="enter your password" required/> <br />
        <button type="submit">Login</button>
    </form>
</body>
</html>