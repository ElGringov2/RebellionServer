<?php



require_once("class.php");
require_once("FakeDatabaseValues.php");


echo Unique::GetIfUniqueExist("Ten Numb", GetMySQLConnection());

echo "<br>";
echo "<br>";
echo "<br>";


if (isset($_GET["fakedb"])) {
    CreateDatabases();
    UpdateCommandos();
    FillDB();
    echo "FakeDB filled.<br/><br/>";
}
if (isset($_GET["galaxy"])) {
    Planet::CreateGalaxy();

}
if (isset($_GET["draft"])) {
    config::SetValue("draftmode", $_GET["draft"]);

}
if (isset($_GET["database"])) {
    CreateDatabases();
    Commander::GetCommanders(GetMySQLConnection());
    config::Init();
    Planet::CreateGalaxy();


}
if (isset($_GET["campaign"])) {
    $mysqli = GetMySQLConnection();
    CreateTable("mission", $mysqli);
    $planets = DatabaseReadAll("planet", $mysqli);
    srand(1);
    foreach ($planets as $planet) {
        GenerateCampaign($planet->DatabaseID, $mysqli, rand());
    }
    echo "Campagne OK";
}





?>


<a href='commands.php?fakedb=1'>FakeDB</a>
<a href='commands.php?galaxy=1'>Reset Galaxy</a>
<a href='commands.php?database=1'>Reset game</a>
<a href='commands.php?campaign=1'>Create Campaign</a>


<?php

if (config::GetValue("draftmode") == 1)
    echo "<br>DRAFTMODE! <a href='commands.php?draft=0'>Switch to normalmode</a><br>";
else
    echo "<br>Normal mode. <a href='commands.php?draft=1'>Switch to draftmode</a><br>";
function UpdateShips()
{
    $mysqli = GetMySQLConnection();
    DatabaseTruncate("xwsship", $mysqli);
    $source = file_get_contents("https://raw.githubusercontent.com/guidokessels/xwing-data/master/data/ships.js");

    $data = json_decode($source);

    foreach ($data as $object) {
        if ($object->size != "huge") {
            $ship = new XWSShip();
            $ship->Name = $object->name;
            $ship->XWS = $object->xws;
            $ship->Attack = $object->attack;
            $ship->Agility = $object->agility;
            $ship->Shield = $object->shields;
            $ship->Hull = $object->hull;
            $ship->Size = $object->size;
            foreach ($object->faction as $faction) {
                if ($ship->Faction == "")
                    $ship->Faction = $faction;

            }
            DatabaseWrite($ship, $mysqli);
        }
    }


    $source = file_get_contents("https://raw.githubusercontent.com/guidokessels/xwing-data/master/data/pilots.js");
    $source = str_replace("\"?\",", "9999,", $source);
    $data = json_decode($source);
    $pilotArray = array();
    $abilities = array();
    foreach ($data as $object) {
        if (!isset($object->unique))
            continue;


        if (!isset($pilotArray[$object->ship])) {
            $pilotArray[$object->ship] = $object->points - $object->skill;
        } else {
            if ($pilotArray[$object->ship] > $object->points - $object->skill && $object->points != 9999) {
                $pilotArray[$object->ship] = $object->points - $object->skill;
            }
        }

    }
    foreach ($pilotArray as $shipname => $cost) {
        $ship = DatabaseRead("xwsship", $mysqli, "name='$shipname'");
        $ship->Cost = $cost;
        DatabaseWrite($ship, $mysqli);
    }
    echo "Pilot update OK<br/>";
}


function GenerateCampaign($planetID, $mysqli, $seed)
{
    srand($seed);

    $mainGame = rand(0, 2);

    for ($iRow = 1; $iRow < 5; $iRow++) {
        $allMissions = Mission::GetAllMission();
        $mainMissions = Mission::GetAllMission($mainGame);
        $mainMission = $mainMissions[rand(0, count($mainMissions) - 1)];
        $mainMission->Row = $iRow;
        $mainMission->Col = 1;
        $mainMission->PlanetID = $planetID;
        if ($iRow == 1)
            $mainMission->State = 0;
        DatabaseWrite($mainMission, $mysqli);


        $otherMission = rand(2, 4);
        for ($iCol = 2; $iCol <= $otherMission; $iCol++) {
            $Mission = $allMissions[rand(0, count($allMissions) - 1)];
            $Mission->Row = $iRow;
            $Mission->Col = $iCol;
            $Mission->PlanetID = $planetID;
            if ($iRow == 1)
                $Mission->State = 0;
            DatabaseWrite($Mission, $mysqli);

        }


    }



}


function UpdateCommandos()
{

    $names = ["Diala Passil", "Fenn Signis", "Gaarkhan", "Gideon Argus", "Jyn Odan", "Mak Eshka'rey", "Biv Bodhrik", "Saska Teft", "Loku Kanoloa", "MHD-19", "Verena Talos", "Drokkatta", "Jarrod Kelvin", "Ko-Tun Feralo"];

    foreach ($names as $name) {
        $commando = new Commando();
        $commando->Name = $name;
        DatabaseWrite($commando, GetMySQLConnection());
    }

}
?>