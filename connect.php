<?php
$servername = "db4free.net";
$username = "pauviz1";
$password = "pauviz1admin";
$database = "dd_pauliaus";

$conn = @new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo '<div style="width:100%"">
  <h3>Klaida!</h3>
  <p>Nepavyko prisijungti prie MySQL.</p>
</div>';

}

?>
