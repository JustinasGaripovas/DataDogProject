 <?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false){
    header('Location: index.php');
} 
?>
<h1>You are logged in as <?php echo $_SESSION['email'] ?>!</h1>
