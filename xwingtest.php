<?php

require("class.php");





foreach(Pilot::GetAllShips() as $ship) {
    $pilot = Pilot::FromJSON($ship);
    echo Pilot::GetShipLetter($pilot->ShipName);
}



?>