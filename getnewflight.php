<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");
$squadron = $_POST["squadron"];


echo "<newflight>";

$ships = DatabaseReadAll('pilot', $mysqli, "flight='Au sol'");
echo "<ships>";
$ship = new Pilot();
foreach ($ships as $ship)
    echo $ship->ToXML();

echo "</ships>";
echo "</newflight>";

?>