<?php
    session_start();
    if( !$_SESSION["loggedin"]){
        header('Location:/DataDogProject?status=unauthorized');
    }