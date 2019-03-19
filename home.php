
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>

<div class="container">
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
    <p align="center">Created event list:</p>
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Location</th>
        </tr>
        </thead>
        <tbody>

        <?php

        define('LOCAL_SERVERNAME', "localhost");
        define('LOCAL_USERNAME', "root");
        define('LOCAL_PASSWORD', "248597136");
        define('LOCAL_DB_NAME', "dd_db");

        session_start();

        /*
         *     Tikriausiai reiktų INNER JOIN bet nematau, kodėl negalėtų būti su paprastu WHERE
         *     $sql = "SELECT Events.title, Events.description, Events.location FROM Events WHERE Events.fk_user = '" . $_SESSION['id'] . "'";
         */

        $sql = "SELECT Events.title, Events.description, Events.location FROM Events WHERE Events.fk_user = '" . $_SESSION['id'] . "'";

        $connection = @new mysqli(LOCAL_SERVERNAME,LOCAL_USERNAME,LOCAL_PASSWORD,LOCAL_DB_NAME);
        $result = $connection->query($sql);

        $count = $result->num_rows;

        if ($count > 0)
        {
            while ($row = $result->fetch_assoc()) {
                echo "<tr> ".
                    "<td>". $row["title"] . "</td>" .
                    "<td>". $row["description"] . "</td>" .
                    "<td>". $row["location"] . "</td>".
                    "</tr> ";
                //printf ("%s (%s)\n", $row["title"], $row["description"]);
            }
        }

        //TODO: Button to add event

        $connection->close();

        ?>
        </tbody>
    </table>
</div>
</body>

</html>
