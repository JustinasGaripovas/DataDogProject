<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 3/17/2019
 * Time: 5:46 PM
 */



include "login_check.php";
include "connect.php";


    $author = $_SESSION["username"];
    $title = $_POST["title"];
    $text = $_POST["eventText"];



    $sql = "INSERT INTO `events` (`ID`, `Title`, `Text`, `Author`) VALUES (NULL, '" . $title . " ', '" . $text . "', '". $author . "' );";


if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    header("Location:home.php?status=success");

} else {
    header("Location:home.php?status=fail");
    echo "Error: " . $sql . "<br>" . $conn->error;

}
$conn->close();



