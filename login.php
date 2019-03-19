<?php
//Database information
define('LOCAL_SERVERNAME', "localhost");
define('LOCAL_USERNAME', "root");
define('LOCAL_PASSWORD', "248597136");
define('LOCAL_DB_NAME', "dd_db");

session_start();

$username = $_POST["username"];
$password = $_POST["password"];

if (!isset($password) || !isset($username))
{
    header("Location:index.php?status=bad_credentials");
}

$hashed_password = password_hash($password,PASSWORD_DEFAULT);

$sql = "SELECT id, username, password FROM Users WHERE username = '" . $username . "'";
$connection = @new mysqli(LOCAL_SERVERNAME,LOCAL_USERNAME,LOCAL_PASSWORD,LOCAL_DB_NAME);
$result = $connection->query($sql);

if ($result->num_rows > 0)
{
    $result = $result->fetch_assoc();

    if(password_verify($password,$result['password']))
    {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $result['username'];
        $_SESSION['id'] = $result['id'];
        header("Location:home.php");
        return;
    }
}

header("Location:index.php?status=bad_credentials");


$connection->close();


?>