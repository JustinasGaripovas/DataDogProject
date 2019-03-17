<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false){
    header('Location: index.php');
} 

if(isset($_POST['type']) && isset($_POST['place'])){
    $conn = new mysqli('localhost', 'root', '', 'login');
    $email = $_SESSION['email'];
    $result = $conn->query("SELECT id FROM users WHERE email = '$email' LIMIT 1");
    $row = $result->fetch_assoc();
    $creator_id = $row["id"];
    $event_type = $_POST['type'];
    $event_place = $_POST['place'];
    $conn->query("INSERT INTO events (id, creator_id, event_type, place) VALUES (NULL, '$creator_id', '$event_type', '$event_place')");

    header('Location: loggedIn.php');
    $conn->close();
}

?>

<form action="newEvent.php" method="POST">
Event type:
<input name="type" type="text" placeholder="type" required/> <br/>
Event place:
<input name="place" type="text" placeholder="place"/> <br />
<button type="submit">Add</button>
</form>