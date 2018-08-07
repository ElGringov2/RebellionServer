<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");



echo "<newflight id='1'>";

$ships = DatabaseReadAll('pilot', $mysqli, "flight='Au sol'");
echo "<ships>";
$ship = new Pilot();
foreach ($ships as $ship)
    echo $ship->ToXML();

echo "</ships>";
echo "</newflight>";

?>