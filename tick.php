<?php

include("class.php");
$mysqli = GetMySQLConnection();

$pilots = DatabaseReadAll("pilot", $mysqli);
foreach ($pilots as $pilot) {
    
}









?>