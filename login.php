<?php
session_start();
include "connect.php";

if( isset($_POST['username']) && isset($_POST['password']) )
{
    $username = $_POST["username"];
    $password = $_POST["password"];

    //$sql = "SELECT username, password_hash FROM users WHERE username=" . $username;
    $sql = "SELECT username, password_hash FROM users WHERE username='". $username ."'";
    $result = $conn->query($sql)->fetch_assoc();

    $password_hash =  $result["password_hash"];

    if ( password_verify($password,$password_hash)){

        $_SESSION['loggedin'] = true;
        header('Location:home.php');
    }
    else{
        header('Location:/DataDogProject?status=incorrect');
    }

}