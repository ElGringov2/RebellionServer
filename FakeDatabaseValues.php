<?php
require_once("class.php");

function FillDB()
{

    srand(1);



    $mysqli = GetMySQLConnection();

    $count = Planet::CreateGalaxy();

    $user = new User();
    $user->Name = "ElGringo";
    $user->CommanderName = "ElGringo";
    $user->Pass = "Password";
    $user->ConnectionGUID = "{7B90A1CC-8BA4-4C55-9528-384025380E73}";



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
    $squadronNames = ["Dianoga", "Rancor", "Sharnaff", "Tauntaun", "Bantha"];
    $flights = ["Au sol", "Vol 1", "Vol 2"];
    $ships = DatabaseReadAll("xwsship", $mysqli);
    $users = DatabaseReadAll("user", $mysqli);
    foreach ($squadronNames as $squadronName) {
        $owner = $users[rand(0, count($users) - 1)]->DatabaseID;
        foreach ($flights as $flight) {
            $location = rand(1, $count);
            for ($i = 0; $i <= 3; $i++) {
                $pilot = new Pilot();
                $pilot->Name = GenerateName(rand());
                $pilot->Squadron = $squadronName;
                $pilot->Flight = $flight;
                $shipxws = $ships[rand(0, count($ships) - 1)];
                $pilot->ActualShip = $shipxws->XWS;
                $pilot->Experience = rand(4, 50);
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










?>