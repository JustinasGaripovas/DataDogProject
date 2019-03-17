<?php
session_start();
include "connect.php";

if( isset($_POST['username']) && isset($_POST['password']) )
{
    $username = $_POST["username"];
    $password = $_POST["password"];



    $sql = "SELECT username, password_hash FROM users WHERE username='". $username ."'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        header('Location:/DataDogProject?status=incorrect');

        $conn->close();
        return;
    }

    $result = $result->fetch_assoc();
    $password_hash =  $result["password_hash"];

    if ( password_verify($password,$password_hash) ){

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location:home.php');

        $conn->close();
        return;
    }
    else{

        $conn->close();
        header('Location:/DataDogProject?status=incorrect');
    }

}