<?php
require_once("class.php");

srand(1);

$mysqli = GetMySQLConnection();


if (isset($_GET["debug"]))
    $user = DatabaseRead('user', $mysqli, "id='" . $_GET["user"] . "'");
else
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
    $assets = DatabaseReadAll("pilot", $mysqli, "location='{$planet->DatabaseID}' and owner='{$user->DatabaseID}'  ORDER BY squadron ASC, flight ASC");
    foreach ($assets as $asset) {
        $knownPlanet = true;
        if ($asset->Flight . " " . $asset->Squadron != $flight) {
            $flight = $asset->Flight . " " . $asset->Squadron;
            $fullName = $asset->Squadron . " " . $asset->Flight;
            $color = strtolower($asset->Squadron);
            $icons .= "\n<icon name=\"Escadron $fullName\" x='$x' y='$y' offset='$offset' icon='./flight_" . User::GetColor($asset->Owner) . ".png' ";
            if (substr($asset->CurrentOrder, 0, 4) == "MOVE") {
                $targetPlanet = DatabaseRead('planet', $mysqli, 'id='.str_replace("MOVE", "", $asset->CurrentOrder));
                $movex = $targetPlanet->GetX($minX, $minY, $maxX, $maxY);
                $movey = $targetPlanet->GetY($minX, $minY, $maxX, $maxY);
                $icons .= "action='move' movetox='$movex' movetoy='$movey' ";
            }
            $icons .= "/>";
            $offset += 32;
        }
    }
    $asset = new Commando(); //typehint

    $assets = DatabaseReadAll("commando", $mysqli, "location='{$planet->DatabaseID}' and owner='{$user->DatabaseID}'");
    foreach ($assets as $asset) {
        $knownPlanet = true;


        $icons .= "\n<icon name=\"{$asset->Name}\" x='$x' y='$y' offset='$offset' icon='{$asset->GetPortrait()}' ";
        if (substr($asset->CurrentOrder, 0, 4) == "MOVE") {
            $targetPlanet = DatabaseRead('planet', $mysqli, 'id='.str_replace("MOVE", "", $asset->CurrentOrder));
            $movex = $targetPlanet->GetX($minX, $minY, $maxX, $maxY);
            $movey = $targetPlanet->GetY($minX, $minY, $maxX, $maxY);
            $icons .= "action='move' movetox='$movex' movetoy='$movey' ";
        }
        $icons .= "/>";
        $offset += 32;
    }
    $base = DatabaseRead("operationbase", $mysqli, "planetid='{$planet->DatabaseID}' and owner='{$user->DatabaseID}'");
    if ($base != null) {
        $icons .= "\n<icon name=\"Votre base d'opération\" x ='$x' y='$y' offset='$offset' icon='./base.png' />";
        $offset += 32;
    }
    $flight = "";

    if ($knownPlanet) {
        $assets = DatabaseReadAll("pilot", $mysqli, "location='{$planet->DatabaseID}' and owner!='{$user->DatabaseID}' ORDER BY squadron ASC, flight ASC");
        foreach ($assets as $asset) {
            if ($asset->Flight . " " . $asset->Squadron != $flight) {
                $flight = $asset->Flight . " " . $asset->Squadron;
                $fullName = $asset->Squadron . " " . $asset->Flight;
                $color = strtolower($asset->Squadron);
                $icons .= "\n<icon name=\"Escadron $fullName\" x='$x' y='$y' offset='$offset' icon='./flight_$color.png' ";
                if (substr($asset->CurrentOrder, 0, 4) == "MOVE") {
                    $targetPlanet = DatabaseRead('planet', $mysqli, 'id='.str_replace("MOVE", "", $asset->CurrentOrder));
                    $movex = $targetPlanet->GetX($minX, $minY, $maxX, $maxY);
                    $movey = $targetPlanet->GetY($minX, $minY, $maxX, $maxY);
                    $icons .= "action='move' movetox='$movex' movetoy='$movey' ";
                }
                $icons .= "/>";
                $offset += 32;
            }
        }
        $base = DatabaseRead("operationbase", $mysqli, "planetid='{$planet->DatabaseID}' and owner!='{$user->DatabaseID}'");
        if ($base != null) {
            $icons .= "\n<icon name=\"Une base d'opération\" x ='$x' y='$y' offset='$offset' icon='./base.png' />";
            $offset += 32;
        }
        $assets = DatabaseReadAll("commando", $mysqli, "location='{$planet->DatabaseID}' and owner!='{$user->DatabaseID}'");
        foreach ($assets as $asset) {
            $knownPlanet = true;


            $icons .= "\n<icon name=\"{$asset->Name}\" x='$x' y='$y' offset='$offset' icon='{$asset->GetPortrait()}' ";
            if (substr($asset->CurrentOrder, 0, 4) == "MOVE") {
                $targetPlanet = DatabaseRead('planet', $mysqli, 'id='.str_replace("MOVE", "", $asset->CurrentOrder));
                $movex = $targetPlanet->GetX($minX, $minY, $maxX, $maxY);
                $movey = $targetPlanet->GetY($minX, $minY, $maxX, $maxY);
                $icons .= "action='move' movetox='$movex' movetoy='$movey' ";
            }
            $icons .= "/>";
            $offset += 32;

        }
    }
}
$xml .= "</planets>";


$xml .= "<icons>$icons</icons>";
$xml .= "</galaxy>";



echo $xml;
?>