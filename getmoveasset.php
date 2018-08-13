<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

if ($_POST["assettype"] == "pilot") {
    //Creation d'une nouvelle escadrille
    $pilot = new Pilot();//typehint
    $pilot = DatabaseRead("pilot", $mysqli, "id='{$_POST["dbid"]}'");
    $id = Pilot::GetNextFlightNumber($pilot->Squadron, $mysqli);
    $pilot->Flight = "Escadrille $id";
    $pilot->CurrentOrder = "MOVE" . $_POST["planetid"];
    DatabaseWrite($pilot, $mysqli);
} else if ($_POST["assettype"] == "flight") {
    $mysqli->query("UPDATE rebellion_pilot SET currentorder='MOVE" . $_POST["planetid"]."' WHERE flight='". $_POST["asset"]."'");
}
else if ($_POST["assettype"] == "assault") {
    $mysqli->query("UPDATE rebellion_commando SET currentorder='MOVE" . $_POST["planetid"]."' WHERE id='". $_POST["dbid"]."'");    
}

?>