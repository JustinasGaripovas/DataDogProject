<?php

include "login_check.php";
include("header.php");
?>


<html>


<head>


</head>

<body>
<button class="eventFormButton" onclick="document.getElementById('eventForm').style.display='block'" > Kurti... </button>

<?php
include "connect.php";
$sql = "SELECT Title, Text FROM events ORDER BY ID DESC";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<h2>" . $row["Title"] . "</h2>";
    echo "<p>" . $row["Text"] . "</h2>";
    echo "</div>";
}

$conn->close();

?>


<div id="eventForm" class="modal">

    <form method="post" class="modal-content" action="insert_event.php">

        <span onclick="document.getElementById('eventForm').style.display='none'" class="closeButton close" title="Close">&times;</span>

        <div class="container">
            <label ><b> Renginio pavadinimas: </b></label>
            <input type="text" placeholder="Pavadinimas..." name="title" required>
            <label><b> Renginio trumpas aprašas:</b></label>
            <!-- <input style="min-height: 300px;" type="text" placeholder="Aprašas..." name="eventText" required>

            <textarea rows="10" cols="50" name="eventText" placeholder="Aprašas..." > </textarea>*/ -->
            <textarea name="eventText" class="event-textarea"></textarea>
            <button type="submit">Kurti</button>
        </div>


    </form>
</div>
</body>
</html>