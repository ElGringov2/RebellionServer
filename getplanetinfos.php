<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

$planet = DatabaseRead("planet", $mysqli, "id=" . $_POST["planetGUID"]);
$history = "Pas d'historique pour l'instant.";
echo "<planet name=\"{$planet->Name}\" description=\"{$planet->Description}\" planetid='{$planet->DatabaseID}' image=\"{$planet->Image}\" history=\"$history\" >";

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





echo "<actions>";


//missions?
$missions = DatabaseReadAll('mission', $mysqli, 'planet=' . $planet->DatabaseID);
$pilotsOnPlanet = DatabaseReadAll('pilot', $mysqli, 'location=' . $planet->DatabaseID);
$commandosOnPlanet = DatabaseReadAll('commando', $mysqli, 'location=' . $planet->DatabaseID);

if (count($missions) == 0) {

    if (count($pilotsOnPlanet) != 0)
        echo "<action type='discover' assettype='pilot' icon='./logoxwing.png' name='Generer une campagne X-Wing' /> ";
    if (count($commandosOnPlanet) != 0)
        echo "<action type='discover' assettype='assault' icon='./assautempire.png' name='Generer une campagne Assaut sur l'Empire' /> ";
}

//Déplacement?
$PilotAssets = DatabaseReadAll('pilot', $mysqli, "owner = '{$user->DatabaseID}' and location !='{$planet->DatabaseID}' AND flight != 'Au sol'");
$pilot = new Pilot(); //typehint
$flightName = "";
foreach ($PilotAssets as $pilot) {
    if ($flightName != $pilot->Squadron . " " . $pilot->Flight) {
        $flightName = $pilot->Squadron . " " . $pilot->Flight;
        $flightplanet = new Planet();
        $flightplanet = DatabaseRead('planet', $mysqli, "id=" . $pilot->Location);
        $distance = round(Planet::GetDistance($planet, $flightplanet) / 10);
        echo "<action type='move'  icon='./flight.png' name='$flightName' asset=\"" . htmlspecialchars($pilot->Flight, ENT_XML1, 'UTF-8') . "\" dbid='{$pilot->DatabaseID}' assettype='flight' time='$distance' />";
    }
}

$PilotAssets = DatabaseReadAll('pilot', $mysqli, "owner = '{$user->DatabaseID}' and flight = 'Au sol'");
if (DatabaseRead("operationbase", $mysqli, 'owner=' . $user->DatabaseID)->PlanetID != $planet->DatabaseID) {
    foreach ($PilotAssets as $pilot) {
        $flightplanet = new Planet();
        $flightplanet = DatabaseRead('planet', $mysqli, "id=" . $pilot->Location);
        $distance = round(Planet::GetDistance($planet, $flightplanet) / 10);
        echo "<action type='move' icon='./flight_red.png' name='{$pilot->Name}' asset=\"" . htmlspecialchars($pilot->Name, ENT_XML1, 'UTF-8') . "\" dbid='{$pilot->DatabaseID}' assettype='pilot' time='$distance' />";
    }
}
$AssaultAssets = DatabaseReadAll('commando', $mysqli, "owner = '{$user->DatabaseID}' and location !='{$planet->DatabaseID}'");
$commando = new Commando(); //typehint
foreach ($AssaultAssets as $commando) {
    $flightplanet = new Planet();
    $flightplanet = DatabaseRead('planet', $mysqli, "id=" . $commando->Location);
    $distance = round(Planet::GetDistance($planet, $flightplanet) / 10);
    echo "<action type='move' icon='{$commando->GetPortrait()}' name='{$commando->Name}' asset=\"" . htmlspecialchars($commando->Name, ENT_XML1, 'UTF-8') . "\" dbid='{$commando->DatabaseID}' assettype=\"assault\" time='$distance' />";
}



echo "</actions>";


echo "</planet>";
?>