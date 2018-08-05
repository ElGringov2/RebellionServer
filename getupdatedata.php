<?php
require_once("class.php");

$mysqli = GetMySQLConnection();
$user = new User();
$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

$commander = new Commander();
$commander = DatabaseRead("commander", $mysqli, "id={$_POST['selectedCmd']}");
$commander->UserID = $user->DatabaseID;
$user->CommanderName = $_POST["cmdName"];
$user->FirstSquadronName = $_POST["squadName"];

$operationBase = new OperationBase();
$operationBase->OwnerID = $user->DatabaseID;
$planets = DatabaseReadAll("planet", $mysqli);
$opBases = DatabaseReadAll("operationbase", $mysqli);
foreach ($opBases as $base) 
    array_splice($planets, $base->PlanetID);

$planet = array_rand($planets);
$operationBase->PlanetID = $planets[$planet]->DatabaseID;
$operationBase->HangarMechanics = 1;
$operationBase->HangarSize = 14;
$operationBase->MedbayBactaTanks = 0;
$operationBase->MedbayCapacity = 3;
$operationBase->TotalCapacity = 32;
$operationBase->TowerControlQuality = 0;
$operationBase->TowerControlRange = 0;

DatabaseWrite($commander, $mysqli);
DatabaseWrite($user, $mysqli);
DatabaseWrite($operationBase, $mysqli);




?>