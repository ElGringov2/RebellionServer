<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

//$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"]."'");

$campaign = DatabaseReadAll("mission", $mysqli, "planet=".$_GET["planetGUID"]);

echo "<campaign>";
foreach ($campaign as $mission) {
    echo ToXML($mission);
}
echo "</campaign>";


?>