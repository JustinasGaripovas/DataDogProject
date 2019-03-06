<?php


function establishConnection($type)
{

    $username = "pauviz1";
    $database = "dd_pauliaus";


   if ($type === "global") {
       $servername = 'db4free.net';
       $password = "pauviz1admin";
    }
   else if ($type === "local") {
        $servername = 'localhost';
        $username = "root";
        $password = "";
    }

    $connection = @new mysqli($servername, $username, $password, $database);
    if ($connection->connect_error) {
        echo '<div style="width:100%"">
        <h3>Klaida!</h3>
         <p>Nepavyko prisijungti prie MySQL.</p>
        </div>';
    }
    return $connection;

   /* if ($conn->connect_error) {
        echo '<div style="width:100%"">
        <h3>Klaida!</h3>
         <p>Nepavyko prisijungti prie MySQL.</p>
        </div>';

    }*/


}

?>
