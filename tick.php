<?php

include("class.php");
$mysqli = GetMySQLConnection();

$pilots = DatabaseReadAll("pilot", $mysqli);
$pilot = new Pilot();
foreach ($pilots as $pilot) {
    if (startsWith($pilot->CurrentOrder, "MOVE")) {
        $pilot->Location = intval(str_replace("MOVE", "", $pilot->CurrentOrder));
        $pilot->CurrentOrder = "IDLE";
        
    }



    DatabaseWrite($pilot, $mysqli);
}









?>