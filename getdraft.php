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
    $allPilots = Pilot::GetAllShips();
    $pilot = Pilot::FromJSON($allPilots[intval($_POST["selectedPilotID"])]);
    $pilot->Squadron = "Escadron";
    $pilot->Flight = "Au sol";
    $pilot->Owner = $user->DatabaseID;
    $pilot->Location = 0;
    DatabaseWrite($pilot, $mysqli);
    echo "OK";
}




?>