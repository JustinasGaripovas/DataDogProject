<?php
include "connect.php";


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$starttime = microtime(true);

$sql = "SELECT username, password_hash FROM users WHERE username='admin'";


$result = $conn->query($sql)->fetch_assoc();

$endtime = microtime(true);
$duration = $endtime - $starttime; //calculates total time taken

echo "<br>" . $duration;

return $conn;