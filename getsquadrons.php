<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");




$pilots = DatabaseReadAll('pilot', $mysqli, "owner='{$user->DatabaseID}' ORDER BY squadron ASC, flight ASC");

$squadron = "";
$flight = "";

echo "<squadrons>";


foreach ($pilots as $pilot) {
    if ($squadron != $pilot->Squadron) {
        if ($squadron != "")
            echo "</squadron>";
        echo "<squadron name='{$pilot->Squadron}' fullname='Escadron {$pilot->Squadron}'>";
        $squadron = $pilot->Squadron;
        $flight = "";
    }
    if ($flight != $pilot->Flight) {
        if ($flight != "")
            echo "</flight>";
        echo "<flight name='{$pilot->Flight}'>";
        $flight = $pilot->Flight;
    }
    echo $pilot->ToXML($mysqli);

}
echo "</flight>";
echo "</squadron>";
echo "</squadrons>";
?>