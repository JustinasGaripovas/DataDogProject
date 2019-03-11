
<html>

    <head>

    </head>

    <body>
        <h1 align="center">
            Welcome
            <?php
            session_start();

            if(!$_SESSION['logged_in'])
            {
                header('Location:index.php?status=unauthorized');
                return;
            }

            echo $_SESSION['username'];
            ?>
            !
        </h1>
    </body>

</html>
