<?php



require_once("class.php");
require_once("FakeDatabaseValues.php");



if (isset($_GET["fakedb"])) {
    CreateDatabases();
    UpdateShips();
    FillDB();
    echo "FakeDB filled.<br/><br/>";
}
if (isset($_GET["galaxy"])) {
    Planet . CreateGalaxy();

}
if (isset($_GET["ship"])) {
    UpdateShips();

}
if (isset($_GET["database"])) {
    CreateDatabases();

}
?>


<a href='commands.php?fakedb=1'>FakeDB</a>
<a href='commands.php?galaxy=1'>Reset Galaxy</a>
<a href='commands.php?database=1'>Reset database</a>
<a href='commands.php?ship=1'>Update Ships</a>


<?php
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
?>