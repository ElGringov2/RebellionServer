<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

$planet = DatabaseRead("planet", $mysqli, "id=" . $_POST["planetGUID"]);
$history = "Pas d'historique pour l'instant.";
echo "<planet name=\"{$planet->Name}\" description=\"{$planet->Description}\" image=\"{$planet->Image}\" history=\"$history\" >";

echo "<buildings>";



$buildings = DatabaseReadAll("building", $mysqli, "planetid='{$planet->DatabaseID}'");

foreach ($buildings as $building) {
    echo ToXml($building);
}
echo "</buildings>";
echo "<assets>";
$containsAssets = false;

echo "<pilots>";
$assets = DatabaseReadAll("pilot", $mysqli, "location='{$planet->DatabaseID}' AND owner='{$user->DatabaseID}' ORDER BY squadron ASC, flight ASC");

foreach ($assets as $asset) {
    $containsAssets = true;
    echo $asset->ToXML($mysqli);
}
echo "</pilots>";
echo "<commandos>";
$assets = DatabaseReadAll("commando", $mysqli, "location='{$planet->DatabaseID}' AND owner='{$user->DatabaseID}'");

foreach ($assets as $asset) {
    $containsAssets = true;
    echo $asset->ToXML($mysqli);
}
echo "</commandos>";
echo "<legion>";
// $assets = DatabaseReadAll("legion", $mysqli, "location='{$planet->DatabaseID}' AND owner='{$user->DatabaseID}'");

// foreach ($assets as $asset) {
//     $containsAssets = true;
//     echo $asset->ToXML($mysqli);
// }
echo "</legion>";

echo "</assets>";
echo "<otherassets>";
if ($containsAssets) {

    echo "<pilots>";
    $assets = DatabaseReadAll("pilot", $mysqli, "location='{$planet->DatabaseID}' AND owner!='{$user->DatabaseID}' ORDER BY squadron ASC, flight ASC");

    foreach ($assets as $asset) {
        echo $asset->ToXML($mysqli);
    }
    echo "</pilots>";
    echo "<commandos>";
    $assets = DatabaseReadAll("commando", $mysqli, "location='{$planet->DatabaseID}' AND owner!='{$user->DatabaseID}'");

    foreach ($assets as $asset) {
        echo $asset->ToXML($mysqli);
    }
    echo "</commandos>";
    echo "<legion>";
// $assets = DatabaseReadAll("legion", $mysqli, "location='{$planet->DatabaseID}' AND owner!='{$user->DatabaseID}'");

// foreach ($assets as $asset) {
//     echo $asset->ToXML($mysqli);
// }
    echo "</legion>";



}



echo "</otherassets>";
$campaign = DatabaseReadAll("mission", $mysqli, "planet='" . $_POST["planetGUID"] . "' ORDER BY row DESC, col ASC");

echo "<campaign>";
foreach ($campaign as $mission) {
    echo ToXML($mission);
}
echo "</campaign>";

echo "</planet>";
?>