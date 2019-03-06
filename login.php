<?php

include "connect.php";

$username = $_POST["username"];
$password = $_POST["password"];



if( isset($_POST['username']) &&  isset($_POST['password']))
{
    echo $username . $password . "<br>";
    $sql = "SELECT username, password_hash FROM users WHERE username=" . $username;
    echo $sql;
}