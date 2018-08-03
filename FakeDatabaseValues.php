<?php
require_once("class.php");

function FillDB()
{

    srand(3);



    $mysqli = GetMySQLConnection();

    $count = Planet::CreateGalaxy();

    $planets = DatabaseReadAll("planet", $mysqli);
    foreach ($planets as $planet) {
        GenerateFakeCampaign($planet->DatabaseID, $mysqli, rand());
    }

    $commandos = DatabaseReadAll("commando", $mysqli);

    $user = new User();
    $user->Name = "ElGringo";
    $user->CommanderName = "ElGringo";
    $user->Pass = "Password";
    $user->ConnectionGUID = "{74CE52C9-47A0-4AE2-8A4D-7052C5D97AAE}";



    $id = DatabaseWrite($user, $mysqli);

    $commandoIndex = rand(0, count($commandos) - 1);
    $commando = $commandos[$commandoIndex];
    $commando->OwnerID = $id;
    $commando->Location = $planets[rand(0, count($planets) - 1)]->DatabaseID;
    DatabaseWrite($commando, $mysqli);
    unset($commandos[$commandoIndex]);
    $commandos = array_values($commandos);

    $operationbase = new OperationBase();
    $operationbase->PlanetID = rand(2, $count);;
    $operationbase->OwnerID = $id;
    $operationbase->HangarSize = 12;
    $operationbase->HangarMechanics = 2;
    $operationbase->MedbayCapacity = 8;
    $operationbase->MedbayBactaTanks = 3;
    $operationbase->TowerControlQuality = 5;
    $operationbase->TowerControlRange = 3;
    $operationbase->TotalCapacity = 24;
    DatabaseWrite($operationbase, $mysqli);

    for ($i = 1; $i < 4; $i++) {
        $user = new User();
        $user->Name = "user$i";
        $user->CommanderName = "Utilisateur $i";
        $user->Pass = "pass$i";
        $id = DatabaseWrite($user, $mysqli);
        $operationbase = new OperationBase();
        $operationbase->PlanetID = rand(2, $count);;
        $operationbase->OwnerID = $id;
        $operationbase->HangarSize = 12;
        $operationbase->HangarMechanics = 2;
        $operationbase->MedbayCapacity = 8;
        $operationbase->MedbayBactaTanks = 3;
        $operationbase->TowerControlQuality = 5;
        $operationbase->TowerControlRange = 3;
        $operationbase->TotalCapacity = 24;
        DatabaseWrite($operationbase, $mysqli);
        $commandoIndex = rand(0, count($commandos) - 1);
        $commando = $commandos[$commandoIndex];
        $commando->OwnerID = $id;
        $commando->Location = $planets[rand(0, count($planets) - 1)]->DatabaseID;
        DatabaseWrite($commando, $mysqli);
        unset($commandos[$commandoIndex]);
        $commandos = array_values($commandos);
    }


    for ($i = 1; $i <= 40; $i++) {
        $buildingType = rand(1, 4);
        $building = new Building();
        $building->Name = Building::GetBaseName($buildingType);
        $building->PlanetID = rand(2, $count);
        $building->QL = rand(1, 10);
        $building->BuildingType = $buildingType;
        DatabaseWrite($building, $mysqli);
    }

    //squadrons
    $squadronNames = ["Blue", "Brown", "Green", "Purple", "Red", "Turquoise", "Yellow"];
    $ownerCount = 0;
    $flights = ["Au sol", "Vol 1", "Vol 2"];
    // $ships = DatabaseReadAll("xwsship", $mysqli);
    $allships = Pilot::GetAllShips();
    $ships = array();
    foreach ($allships as $ship)
        if ($ship["faction"] == "Rebel Alliance")
        $ships[] = $ship;
    $users = DatabaseReadAll("user", $mysqli);
    foreach ($squadronNames as $squadronName) {
        $ownerCount++;
        if ($ownerCount == count($users) - 1)
            $ownerCount = 0;
        $owner = $users[$ownerCount]->DatabaseID;
        foreach ($flights as $flight) {
            $location = $planets[rand(0, count($planets) - 1)]->DatabaseID;
            for ($i = 0; $i <= 3; $i++) {
                $pilotid = rand(0, count($ships) - 1);
                $pilot = Pilot::FromJSON($ships[$pilotid]);
                if ($pilot->Unique == 1) {
                    array_splice($ships, $pilotid, 1);
                }
                //$pilot->Name = GenerateName(rand());
                $pilot->Squadron = $squadronName;
                $pilot->Flight = $flight;
                // $shipxws = $ships[rand(0, count($ships) - 1)];
                // $pilot->ActualShip = $shipxws->XWS;
                //$pilot->Experience = rand(4, 50);
                if ($flight == "Au sol")
                    $pilot->Location = -1;
                else
                    $pilot->Location = $location;
                $pilot->Owner = $owner;


                DatabaseWrite($pilot, $mysqli);
            }
        }


    }
}




function GenerateFakeCampaign($planetID, $mysqli, $seed)
{
    srand($seed);

    $mainGame = rand(0, 2);

    $state = rand(0, 2);
    $locationRow = rand(1, 5);



    for ($iRow = 1; $iRow < 5; $iRow++) {

        $otherMission = rand(2, 4);
        $locationCol = rand(1, $otherMission);

        $allMissions = Mission::GetAllMission();
        $mainMissions = Mission::GetAllMission($mainGame);
        $Mission = $mainMissions[rand(0, count($mainMissions) - 1)];
        $Mission->Row = $iRow;
        $Mission->Col = 1;
        $Mission->PlanetID = $planetID;

        if ($state == 0) { //non démarré
            if ($iRow == 1)
                $Mission->State = 0;
        } else if ($state >= 1) {//en cours/echouée, voir row/col
            if ($iRow > $locationRow)
                $Mission->State = -1;
            else if ($iRow == $locationRow) {
                if ($locationCol == 1) {
                    $Mission->State = ($state == 1 ? 2 : 4);
                } else
                    $Mission->State = 1;
            } else {
                if ($locationCol == 1)
                    $Mission->State = 3;
                else
                    $Mission->State = 1;
            }
        }


        DatabaseWrite($Mission, $mysqli);



        for ($iCol = 2; $iCol <= $otherMission; $iCol++) {
            $Mission = $allMissions[rand(0, count($allMissions) - 1)];
            $Mission->Row = $iRow;
            $Mission->Col = $iCol;
            $Mission->PlanetID = $planetID;
            if ($state == 0) { //non démarré
                if ($iRow == 1)
                    $Mission->State = 0;
            } else if ($state >= 1) {//en cours/echouée, voir row/col
                if ($iRow > $locationRow)
                    $Mission->State = -1;
                else if ($iRow == $locationRow) {
                    if ($locationCol == $iCol) {
                        $Mission->State = ($state == 1 ? 2 : 4);
                    } else
                        $Mission->State = 1;
                } else {
                    if ($locationCol == $iCol)
                        $Mission->State = 3;
                    else
                        $Mission->State = 1;
                }
            }
            DatabaseWrite($Mission, $mysqli);

        }


    }



}






?>