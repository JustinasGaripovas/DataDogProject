<?php
    session_start();
    if( !$_SESSION["loggedin"]){
        header('Location:index.php?status=unauthorized');
    }