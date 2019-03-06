<?php
$servername = 'localhost';
$username = "root";
$password = "";
$database = "dd_pauliaus";

$conn = @new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo '<div style="width:100%"">
  <h3>Klaida!</h3>
  <p>Nepavyko prisijungti prie MySQL.</p>
</div>';

}

?>
