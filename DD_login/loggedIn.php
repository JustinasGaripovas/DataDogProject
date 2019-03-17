 <?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false){
    header('Location: index.php');
} 
?>
<h1>You are logged in as <?php echo $_SESSION['email'] ?>!</h1>

<?php
$conn = new mysqli('localhost', 'root', '', 'login');
$email = $_SESSION['email'];
$query = "SELECT * FROM users INNER JOIN events ON users.id = events.creator_id WHERE email = '$email'";

$result = $conn->query($query);
if($result->num_rows > 0){
    echo 'Events by this user:'.'<br>';
    while($row = $result->fetch_assoc()){
        echo $row["email"].' '.$row["event_type"].' '.$row["place"]."<br>";
    }
}
else{
    echo 'No results';
}
$conn->close();
?>


<form action="newEvent.php" method="POST">
    <h3>Add new event:</h3>
    <button type="submit">Create</button>
</form>