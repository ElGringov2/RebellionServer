<?php
require_once("class.php");

srand(1);

$mysqli = GetMySQLConnection();
$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

$xml = "<galaxy><planets>";

$planets = DatabaseReadAll("planet", $mysqli);

$minX = -5211;
$minY = -3370;
$maxX = 3925;
$maxY = 6649;


$filters = ["", "_red", "_orange", "_blue"];
$icons = "";

foreach ($planets as $planet) {
    $filter = $filters[rand(0, 3)];
    $x = $planet->GetX($minX, $minY, $maxX, $maxY);
    $y = $planet->GetY($minX, $minY, $maxX, $maxY);
    $xml .= "<planet name='{$planet->Name}' x='$x' y='$y' guid='{$planet->DatabaseID}' image='dot$filter.png' />";
    $knownPlanet = false;
    $flight = "";
    $offset = 0;
    $assets = DatabaseReadAll("pilot", $mysqli, "location='{$planet->DatabaseID}' and owner='{$user->DatabaseID}'");
    foreach ($assets as $asset) {
        $knownPlanet = true;
        if ($asset->Flight != $flight) {
            $flight = $asset->Flight;
            $fullName = $asset->Squadron . " " . $asset->Flight;
            $icons .= "<icon name=\"Escadron $fullName\" x='$x' y='$y' offset='$offset' icon='./pilot.png' />";
            $offset += 32;
        }
    }
    $base = DatabaseRead("operationbase", $mysqli, "planetid='{$planet->DatabaseID}' and owner='{$user->DatabaseID}'");
    if ($base != null) {
        $icons .= "<icon name=\"Votre base d'opération\" x ='$x' y='$y' offset='$offset' icon='./base.png' />";
        $offset += 32;
    }
    $flight = "";

    if ($knownPlanet) {
        $assets = DatabaseReadAll("pilot", $mysqli, "location='{$planet->DatabaseID}' and owner!='{$user->DatabaseID}'");
        foreach ($assets as $asset) {
            if ($asset->Flight != $flight) {
                $flight = $asset->Flight;
                $fullName = $asset->Squadron . " " . $asset->Flight;
                $icons .= "<icon name=\"Escadron $fullName\" x='$x' y='$y' offset='$offset' icon='./pilot.png' />";
                $offset += 32;
            }
        }
    }
}
$xml .= "</planets>";


$xml .= "<icons>$icons</icons>";
$xml .= "</galaxy>";



echo $xml;
?>