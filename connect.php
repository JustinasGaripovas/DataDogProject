<?php

define("LOCAL_SERVERNAME", "localhost");
define("LOCAL_USERNAME", "root");
define("LOCAL_PASSWORD", "");
define("LOCAL_DATABASE", "dd_pauliaus");

define("GLOBAL_SERVERNAME", "localhost");
define("GLOBAL_USERNAME", "root");
define("GLOBAL_PASSWORD", "");
define("GLOBAL_DATABASE", "dd_pauliaus");

define("CONNECTION_TYPE", "local");

$conn = establishConnection(CONNECTION_TYPE);

function establishConnection($type)
{

    switch ($type){
        case "global":
            $conn = @new mysqli(GLOBAL_SERVERNAME, GLOBAL_USERNAME, GLOBAL_PASSWORD, GLOBAL_DATABASE);
            break;

        case "local":
        default:
        $conn = @new mysqli(LOCAL_SERVERNAME, LOCAL_USERNAME, LOCAL_PASSWORD, LOCAL_DATABASE);
            break;


    }


    return $conn;

}

?>
