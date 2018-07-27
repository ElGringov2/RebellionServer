<?php
require("class.php");

if (!isset($_POST["command"])) {
    echo "???";
    return;
}


if ($_POST["command"] == "resetassaultennemies") {
    CreateTable("assaultennemy", GetMySQLConnection());
    echo "OK";
    return;
}


if ($_POST["command"] == "addassaultennemy") {

    $data = json_decode($_POST["json"], true);


    $weapon = JSONToObject("AssaultItem", $data["MainWeapon"]);

    
    $e = JSONToObject("AssaultEnnemy", $data);
    $e->MainWeapon = $weapon;
    
    DatabaseWrite($e, GetMySQLConnection());
    echo "OK";
    return;

}

if ($_POST["command"] == "getassaultennemies") {
    echo json_encode(DatabaseReadAll("assaultennemy", GetMySQLConnection()));
    return;
}


if ($_POST["command"] == "resetassaultmissions") {
    CreateTable("assaultmission", GetMySQLConnection());
    echo "OK";
    return;
}

if ($_POST["command"] == "addassaultmission") {

    $data = json_decode($_POST["json"], true);



    
    $e = JSONToObject("AssaultMission", $data);
    //$e->MainWeapon = $weapon;
    
    DatabaseWrite($e, GetMySQLConnection());
    echo "OK";
    return;

}

if ($_POST["command"] == "getassaultmissions") {
    echo json_encode(DatabaseReadAll("assaultmission", GetMySQLConnection()));
    return;
}




?>