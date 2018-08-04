<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

$commanders = DatabaseReadAll("commander", $mysqli);

echo "<commanders>";

foreach ($commanders as $commander) {
    if (ceil($commander->DatabaseID / 2) == $user->DatabaseID)
        echo "<commander name=\"{$commander->Name}\" desc=\"{$commander->Description}\" id=\"{$commander->DatabaseID}\" />";
}
echo "</commanders>";

?>