<!DOCTYPE html>
<?php
require_once('User.php');
$users = array(new User('email1', 'passw1'), new User('email2', 'passw2'), new User('email3', 'passw3'));

session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    header('Location: loggedIn.php');
    exit;
}

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $email_found = false;

    for($i = 0; $i < count($users); $i++){
        if($email === $users[$i]->user_email && password_verify($password, $users[$i]->user_password)){
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            $email_found = true;
            header('Location: loggedIn.php');
            break;
        }
        else if($email === $users[$i]->user_email){
            $email_found = true;
            echo "wrong password";
            break;
        }
    }
    if($email_found == false){
        echo "wrong email";
    }
}
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