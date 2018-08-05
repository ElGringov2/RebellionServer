<?php
require("class.php");

ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);


 srand(1);
 var_dump(GenerateRandomSquadron("Galactic Empire", 200));


function GenerateRandomSquadron(string $Faction, int $Points)
{
    $allShips = Pilot::GetAllShips();

    $ships = array();

    foreach ($allShips as $shipInfos) {
        if ($shipInfos["faction"] == $Faction)
            $ships[] = Pilot::FromJSON($shipInfos);
    }

    if (count($ships) == 0) return array();

    $actualSquadronCost = 0;
    $unique = array();

    $pilotPoints = $Points * 0.9;

    $possibleShips = PossibleShips($ships, $pilotPoints - $actualSquadronCost, $unique);
    $array = array();

    while (count($possibleShips) != 0) {

        $index = rand(0, (count($possibleShips) - 1));


        $array[] = $possibleShips[$index];
        $actualSquadronCost += $possibleShips[$index]->Cost;
        if ($possibleShips[$index]->Unique == 1)
            $unique[$possibleShips[$index]->Name] = 1;
        $possibleShips = PossibleShips($ships, $pilotPoints - $actualSquadronCost, $unique);

    }

    //upgrades

    $upgrades = PossibleUpgrades($array, $Points - $actualSquadronCost, $unique, $Faction);



    while (count($upgrades) > 0) {

        $shipIndex = rand(0, count($upgrades) - 1);
        $ShipUpgrades = $upgrades[array_keys($upgrades)[$shipIndex]];

        $upgradeTypeIndex = rand(0, count($ShipUpgrades) - 1);

        $keys = array_keys($ShipUpgrades);
        $listUpgrade = $ShipUpgrades[$keys[$upgradeTypeIndex]];
        $upgrade = $listUpgrade[rand(0, count($listUpgrade) - 1)];

        foreach ($array[$shipIndex]->Upgrades as $key => $localUpgrade) {
            if ($localUpgrade->Type == $upgrade->Type && $localUpgrade->Name == "Vide") {
                $array[$shipIndex]->Upgrades[$key] = $upgrade;
                $actualSquadronCost += $upgrade->GetCost($array[$shipIndex]);
                break;
            }
        }





        $upgrades = PossibleUpgrades($array, $Points - $actualSquadronCost, $unique, $Faction);

    }

    echo "Totalcost = " . $actualSquadronCost . "<br>";
    return $array;



}


function PossibleShips(array $ships, int $pointsLeft, array $unique)
{
    $array = array();

    foreach ($ships as $ship) {
        if ($ship->Cost <= $pointsLeft && !isset($unique[$ship->Name]))
            $array[] = $ship;
    }



    return $array;
}


function PossibleUpgrades(array $Ships, int $Points, array $Unique, string $Faction)
{
    $array = array();
    $allUpgrades = Upgrade::GetAllUpgrades();

    foreach ($allUpgrades as $upgrade) {
        $ship = new Pilot();
        foreach ($Ships as $ship) {
            $shipupgrade = new Upgrade();
            foreach ($ship->Upgrades as $shipupgrade) {
                $testUpgrade = Upgrade::FromJSONArray($upgrade);
                $cost = $testUpgrade->GetCost($ship);
                if ($shipupgrade->Type == $upgrade["type"] && $cost <= $Points && !isset($unique[$upgrade["name"]]) && ($upgrade["faction"] == "" || $Faction == $upgrade["faction"]))
                    $array[$ship->Name][$shipupgrade->Type][] = $testUpgrade;
            }
        }
    }

    return $array;
}








// $content = file_get_contents("http://xwing-miniatures-second-edition.wikia.com/wiki/Soontir_Fel");
// showContent($content);
// return;



// $allShips = Pilot::GetAllShips();



// $allOtherShips = json_decode(file_get_contents("otherpilot.json"), true);


// for ($i = 0; $i < count($allShips); $i++) {
//     foreach ($allOtherShips as $correctShip)
//     {
//         if ($correctShip["xws"] == $allShips[$i]["xws"])
//         {
//             $allShips[$i]["cost"] = $correctShip["cost"];
//             $allShips[$i]["upgrade"] = $correctShip["upgrade"];


//             break;
//         }
//     }
    
// }



// foreach ($allShips as $ship) {
//     if ($ship["cost"] == 0) 
//     echo "<br>".$ship["name"]." - ".$ship["shipname"];
// }


// echo json_encode($allShips);

// $sources = array("http://xwing-miniatures-second-edition.wikia.com/wiki/Category:Imperial_Pilots", "http://xwing-miniatures-second-edition.wikia.com/wiki/Category:Rebel_Pilots", "http://xwing-miniatures-second-edition.wikia.com/wiki/Category:Scum_Pilots");
// $factions = array("Galactic Empire", "Rebel Alliance", "Scum and Villainy");
// $newArray = array();

// for ($iSource = 0; $iSource < 3; $iSource++) {
//     $contentSource = file_get_contents($sources[$iSource]);
//     $findSource = strpos($contentSource, "Pages in category");
//     while (true) {
//         $findSource = strpos($contentSource, "<a href=\"", $findSource) + 9;
//         if ($findSource == false) break;
//         $endSource = strpos($contentSource, "\" title", $findSource);

//         if (substr($contentSource, $findSource, 4) == "http") break;

//         $content = file_get_contents("http://xwing-miniatures-second-edition.wikia.com" . substr($contentSource, $findSource, $endSource - $findSource));



//         $find = strpos($content, "<font size=\"1\">Init</font>");
//         $find = strpos($content, "<b>", $find) + 3;
//         $endFind = strpos($content, "</b>", $find + 1);


//         $name = substr($content, $find, $endFind - $find);

//         $xws = strtolower(preg_replace('/[^a-z0-9]/i', '', $name));


//         $ship = array();
//         $ship["xws"] = $xws;
//         $ship["name"] = $name;
//         $ship["faction"] = $factions[$iSource];

//         // foreach ($allShips as $ship) {
//             // if ($ship["xws"] == $xws) {
//         $find = strpos($content, "Cost:");
//         $find = strpos($content, "colspan=\"4\">", $find);
//         $find = strpos($content, "\">", $find) + 3;
//         $endFind = strpos($content, "</td></tr>", $find + 1);
//         $ship["cost"] = intval(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));


//         $find = strpos($content, "<th class=\"wikia-infobox-header\" colspan=\"4\" style=\"text-align: center;\">", $find) + 73;
//         $endFind = strpos($content, "</th></tr>", $find);
//         $ship["shipname"] = trim( substr($content, $find, $endFind - $find - 1));

//         if (strpos($content, "Unique Pilots") != false)
//             $ship["unique"] = true;
//         else
//             $ship["unique"] = false;

//         $find = strpos($content, "Pilot Ability");
//         $find = strpos($content, "style=\"text-align: center;\">", $find);
//         $find = strpos($content, "\">", $find) + 3;
//         $endFind = strpos($content, "</td></tr>", $find + 1);

//         if (substr($content, $find, 10) == "</td></tr>")
//             $ship["ability"] = "None";
//         else
//             $ship["ability"] = strip_tags(str_replace("\n", "", substr($content, $find, $endFind - $find)));



//         $find = strpos($content, "Ship Ability");
//         $find = strpos($content, "style=\"text-align: center;\">", $find);
//         $find = strpos($content, "\">", $find) + 3;
//         $endFind = strpos($content, "</td></tr>", $find + 1);

//         if (substr($content, $find, 10) == "</td></tr>")
//             $ship["shipability"] = "None";
//         else
//             $ship["shipability"] = strip_tags(str_replace("\n", "", substr($content, $find, $endFind - $find)));


//         $find = strpos($content, "<b>Force</b>");
//         if ($find != false) {
//             $find = strpos($content, "colspan=\"3\">", $find);
//             $find = strpos($content, ">", $find) + 2;
//             $endFind = strpos($content, "<sup>", $find + 1);
//             $ship["force"] = intval(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
//         } else
//             $ship["force"] = 0;

//         if (strpos($content, "Initiative 1 Pilots"))
//             $ship["initiative"] = 1;
//         if (strpos($content, "Initiative 2 Pilots"))
//             $ship["initiative"] = 2;
//         if (strpos($content, "Initiative 3 Pilots"))
//             $ship["initiative"] = 3;
//         if (strpos($content, "Initiative 4 Pilots"))
//             $ship["initiative"] = 4;
//         if (strpos($content, "Initiative 5 Pilots"))
//             $ship["initiative"] = 5;
//         if (strpos($content, "Initiative 6 Pilots"))
//             $ship["initiative"] = 6;


//         $find = strpos($content, "Shd");
//         $find = strpos($content, "<td style=\"color: red; text-align: center;\">", $find) + 44;
//         $endFind = strpos($content, "</td>", $find);
//         $count = strtolower(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
//         $ship["attack"] = intval($count);
//         if (intval($count) > 10) {
//             $split1 = intval(substr($count, 0, 1));
//             $split2 = intval(substr($count, 1, 1));
//             if ($split1 > $split2) $ship["attack"] = $split1;
//             else $ship["attack"] = $split2;
//         }

//         $find = strpos($content, "Shd");
//         $find = strpos($content, "<td style=\"color: #41A317; text-align: center;\">", $find) + 47;
//         $endFind = strpos($content, "</td>", $find);
//         $count = strtolower(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
//         $ship["agility"] = intval($count);

//         $find = strpos($content, "Shd");
//         $find = strpos($content, "<td style=\"color: #00FFFF; text-align: center;\">", $find) + 47;
//         $endFind = strpos($content, "</td>", $find);
//         $count = strtolower(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
//         $ship["shields"] = intval($count);

//         $find = strpos($content, "Shd");
//         $find = strpos($content, "<td style=\"color: orange; text-align: center;\">", $find) + 46;
//         $endFind = strpos($content, "</td>", $find);
//         $count = strtolower(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
//         $ship["hull"] = intval($count);


//         if (strpos($content, "<span title=\"Small base\"") != false)
//             $ship["size"] = 1;

//         if (strpos($content, "<span title=\"Medium base\"") != false)
//             $ship["size"] = 2;

//         if (strpos($content, "<span title=\"Large base\"") != false)
//             $ship["size"] = 3;

//         $subContent = substr($content, strpos($content, "<th colspan=\"4\" style=\"text-align: center;\"> Upgrade Slots"));


//         $ship["upgrade"]["cannon"] = substr_count($subContent, "Cannon Upgrade Slot");
//         $ship["upgrade"]["talent"] = substr_count($subContent, "Talent Upgrade Slot");
//         $ship["upgrade"]["missile"] = substr_count($subContent, "Missile Upgrade Slot");
//         $ship["upgrade"]["astromech"] = substr_count($subContent, "Astromech Upgrade Slot");
//         $ship["upgrade"]["crew"] = substr_count($subContent, "Crew Upgrade Slot");
//         $ship["upgrade"]["gunner"] = substr_count($subContent, "Gunner Upgrade Slot");
//         $ship["upgrade"]["device"] = substr_count($subContent, "Device Upgrade Slot");
//         $ship["upgrade"]["force"] = substr_count($subContent, "Force Upgrade Slot");
//         $ship["upgrade"]["illicit"] = substr_count($subContent, "Illicit Upgrade Slot");
//         $ship["upgrade"]["modification"] = substr_count($subContent, "Modification Upgrade Slot");
//         $ship["upgrade"]["sensor"] = substr_count($subContent, "Sensor Upgrade Slot");
//         $ship["upgrade"]["torpedo"] = substr_count($subContent, "Torpedo Upgrade Slot");
//         $ship["upgrade"]["turret"] = substr_count($subContent, "Turret Upgrade Slot");
//         $ship["upgrade"]["title"] = substr_count($subContent, "Title Upgrade Slot");
//         $ship["upgrade"]["configuration"] = substr_count($subContent, "Configuration Upgrade Slot");





//         $newArray[] = $ship;
//                 // break;
//             // }


//         // }





//     }

// }

// usort($newArray, function ($a, $b) {
//     return strcmp($a["faction"], $b["faction"]);
// });

// usort($newArray, function ($a, $b) {
//     return strcmp($a["name"], $b["name"]);
// });
// echo "{\"pilots\": ";
// echo json_encode($newArray);
// echo "}";
// function showContent($content)
// {


//     $find = strpos($content, "<font size=\"1\">Init</font>");
//     $find = strpos($content, "<b>", $find) + 3;
//     $endFind = strpos($content, "</b>", $find + 1);


//     $name = substr($content, $find, $endFind - $find);


//     echo "Name: $name: ";

//     $find = strpos($content, "Pilot Ability");
//     $find = strpos($content, "style=\"text-align: center;\">", $find);
//     $find = strpos($content, "\">", $find) + 3;
//     $endFind = strpos($content, "</td></tr>", $find + 1);

//     echo "\"ability\": \"" . substr($content, $find, $endFind - $find) . "\",<br/>";

//     $find = strpos($content, "<b>Force</b>");
//     if ($find != false) {
//         $find = strpos($content, "colspan=\"3\">", $find);
//         $find = strpos($content, ">", $find) + 2;
//         $endFind = strpos($content, "<sup>", $find + 1);
//         echo "\"force\":  " . substr($content, $find, $endFind - $find) . ",<br/>";
//     }

//     if (strpos($content, "Initiative 1 Pilots"))
//         echo "\"init\": 1,<br/>";
//     if (strpos($content, "Initiative 2 Pilots"))
//         echo "\"init\": 2,<br/>";
//     if (strpos($content, "Initiative 3 Pilots"))
//         echo "\"init\": 3,<br/>";
//     if (strpos($content, "Initiative 4 Pilots"))
//         echo "\"init\": 4,<br/>";
//     if (strpos($content, "Initiative 5 Pilots"))
//         echo "\"init\": 5,<br/>";
//     if (strpos($content, "Initiative 6 Pilots"))
//         echo "\"init\": 6,<br/>";



//     $find = strpos($content, "</th><th style=\"color: #41A317; text-align: center;\"> Agl");
//     $find = strpos($content, "<td style=\"color: red; text-align: center;\">", $find) + 44;
//     $endFind = strpos($content, "</td>", $find);
//     $count = strtolower(preg_replace('/[^a-z0-9]/i', '', substr($content, $find, $endFind - $find)));
//     echo "\"attack\": " . intval($count) . ",<br/>";

// }
?>