<?php
require_once("class.php");

if (config::GetValue("draftmode") == 0) {
    echo "<draft />";
    return;
}

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");




if (!isset($_POST["selectedPilotID"]))
    echo Pilot::GetDraft($user, $mysqli);
else {
    $uniques = DatabaseReadAll('unique', $mysqli);
    $pilots = Pilot::GetAllShips();
    usort($pilots, function ($a, $b) {
        if ($a["shipname"] == $b["shipname"])
            return strcmp($a["name"], $b["name"]);
        return strcmp($a["shipname"], $b["shipname"]);
    });
    $pilot = Pilot::FromJSON($pilots[intval($_POST["selectedPilotID"])]);
    $pilot->Squadron = $user->FirstSquadronName;
    $pilot->Flight = "Au sol";
    $pilot->Owner = $user->DatabaseID;
    $opbase = DatabaseRead("operationbase", $mysqli, "owner='".$user->DatabaseID."'");
    $pilot->Location = $opbase->PlanetID;
    if ($pilot->Unique == 1)
    {
        $unique = new Unique();
        $unique->Name = $pilot->Name;
        DatabaseWrite($unique, $mysqli);
    }
    DatabaseWrite($pilot, $mysqli);
    echo "OK";


    $checkEndDraft = DatabaseReadAll('pilot', $mysqli, "owner=".$user->DatabaseID);
    if (count($checkEndDraft) == 12) {
        Upgrade::CreateStartUpgrades($user, $checkEndDraft);
    }
}




?>