<?php
require("Commando.php");
require("AssaultEnnemy.php");
require("AssaultItem.php");
require("AssaultMission.php");




$content = file_get_contents("http://imperial-assault.wikia.com/wiki/Diala_Passil_(Hero)");


$player = new Commando();
$player->Name = GetString($content, "<h2 class=\"pi-item pi-item-spacing pi-title\">", "</h2>")["value"];
$healthInfo = GetInt($content, "\"pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing\"", "</td>");
$player->Health = $healthInfo["value"];
$enduranceInfo = GetInt($content, "\"pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing\"", "</td>", $healthInfo["pos"]);
$player->Endurance = $enduranceInfo["value"];
$speedInfo = GetInt($content, "\"pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing\"", "</td>", $enduranceInfo["pos"]);
$player->Move = $speedInfo["value"];
if (strpos($content, "alt=\"Black Die\"") != false)
    $player->DefenseBlack = 1;
else
    $player->DefenseWhite = 1;

$strSkillToFind = "<td class=\"pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing\"><a href=\"/wiki/Dice\"";
$endSkillToFind = "</noscript></a></td>";
$start = 0;


$start = strpos($content, $strSkillToFind, $start);
$end = strpos($content, $endSkillToFind, $start);
$lineContent= substr($content, $start, $end - $start);



var_dump($player);
return;


function GetString($content, $strToFind, $endStrToFind, int $offset = 0)
{
    $pos = strpos($content, $strToFind, $offset);
    $pos = strpos($content, ">", $pos) + 1;
    $endPos = strpos($content, $endStrToFind, $pos);
    return array("value" => trim(substr($content, $pos, $endPos - $pos)), "pos" => $pos);

}

function GetInt($content, $strToFind, $endStrToFind, int $offset = 0)
{
    $result = GetString($content, $strToFind, $endStrToFind, $offset);
    return array("value" => intval($result["value"]), "pos" => $result["pos"]);
}


$players = Commando::GetAllCommandos();
srand(1);


$threatExperience = 3;

for ($i = 0; $i < 8; $i++) {







    $threatLevel = $threatExperience / 3;
    $startThreat = rand(1, 2) * $threatLevel;

    $mission = AssaultMission::GetRandomMission($i);
    var_dump($mission);
    echo "mission: niveau de menace: $threatLevel, tour: {$mission->Turn}, points d'objectifs: {$mission->ObjectivePoints}:<br>";

    for ($iTry = 0; $iTry < 20; $iTry++) {
        $ennemies = AssaultMission::GetRandomMission($mission->Size, $i)->Ennemies;

        srand($iTry);

  //ob_start();



        $result = AutoResolve($players, $ennemies, $threatLevel, $startThreat, $mission->Turn, $mission->ObjectivePoints);
        foreach ($players as $player) {
            $player->Count = 2;
            $player->ActualHealth = $player->Health;
        }

  //ob_end_clean();
        echo "essai $iTry: " . $result->result . "<br>";
    }
    foreach ($players as $player) {
        $player->Count = 2;
        $player->ActualHealth = $player->Health;
        $player->Experience += ($result->result == "reussite" ? 2 : 1);
    }

    $threatExperience += ($result->result != "reussite" ? 2 : 1);



    var_dump($result);

    echo "mission $i, resultat: {$result->result}<br>";

}
var_dump($players);







/**
 * Autorun selected mission
 * 
 * @param array $Players Array of Commando
 * @param array $Ennemies array of AssaultEnnemies
 * @param int $ThreatPerRoungs amount of threat gaigned every rounds
 * @param int $threat starting threat
 * @param AssaultMission $mission Mission to be runned
 */
function AutoResolve(array $Players, array $Ennemies, int $ThreatPerRound, int $threat, AssaultMission $mission) : stdClass
{
    echo "<br/>Autorunning {$mission->Name}";

    $movePerObjectif = $mission->Size / $mission->ObjectivePoints;


    $objectiveNeeded = $mission->Objective;
    $objective = 0;
    for ($i = 1; $i <= $mission->Turn; $i++) {

        $idPlayer = 0;
        $idEnnemy = 0;





        // $players = array();
        // $ennemies = array();


        // foreach ($Players as $pl)
        //     if ($pl->Count > 0)
        //     $players[] = $pl;
        // foreach ($Ennemies as $pl)
        //     if ($pl->Count > 0)
        //     $ennemies[] = $pl;


        shuffle($Ennemies);
        shuffle($Players);

        $move = array();

        echo "<hr>round $i:";
        while ($idPlayer < count($Players) || $idEnnemy < count($Ennemies)) {

            while ($idPlayer < count($Players) && $Players[$idPlayer]->Count == 0)
                $idPlayer++;


            if ($idPlayer != count($Players)) {

                $actor = $Players[$idPlayer];

                echo "<br><br>personnage: {$actor->Name}";

                for ($PlayerAction = 0; $PlayerAction < 2; $PlayerAction++) {


                    //4 actions possible: déplacement - attaque - repos - objectif



                    $attackOrObjective = rand(1, 4 - (2 * $PlayerAction));
                    if ($attackOrObjective > 2) {
                        echo "<br>Déplacement:";
                        $move[$actor->Name] += $actor->Move;
                        echo " {$move[$actor->Name]}/{$mission->Size}";
                    } else if ($attackOrObjective == 2) {
                        echo "<br>Attaque:";
                        $target = GetBestTarget($Ennemies, rand());
                        if ($target == null) {
                            echo "<br>Plus de cible. Objectif à la place.";
                            $objective++;
                        } else
                            Resolve($actor, $target, rand(), $actor->GetLevel(), 1);
                    } else if ($attackOrObjective == 1) {
                        $objective++;
                        echo "<br>Objectif.";
                    }
                }
                $idPlayer++;

            }
            if ($objective >= $objectiveNeeded) {
                echo "<br>Objective done.";
                $sizeOk = true;
                foreach ($player as $pl) {
                    if ($move[$pl->Name] < $mission->Size)
                        $sizeOk = false;
                }
                if (!$sizeOk) {
                    echo "player on board.";
                } else
                    break;
            }


            while ($idEnnemy < count($Ennemies) && $Ennemies[$idEnnemy]->Count == 0)
                $idEnnemy++;

            if ($idEnnemy < count($Ennemies)) {

                echo "<br><br>ennemi: {$Ennemies[$idEnnemy]->Name} x{$Ennemies[$idEnnemy]->Count},";


                for ($iEnnemyID = 0; $iEnnemyID < $Ennemies[$idEnnemy]->Count; $iEnnemyID++) {

                    $actor = $Ennemies[$idEnnemy];
                    if ($actor->Count > 0) {

                        $target = GetBestPlayerTarget($Players, rand());
                        if ($target != null)
                            Resolve($actor, $target, rand(), 1, $target->GetLevel());


                    } else
                        echo "Dead.";

                }

                $deadCheck = true;
                foreach ($Players as $player)
                    if ($player->Count == 2)
                    $deadCheck = false;

                if ($deadCheck)
                    break;

                $idEnnemy++;
            }





        }

        $threat += $ThreatPerRound;

        $deads = array(1, 2, 3);
        echo "<br>phase de menace: $threat points:";

        while (count($deads) > 0) {
            $deads = array();
            foreach ($Ennemies as $actor) {
                if ($actor->Count == 0 && $actor->TotalCost <= $threat)
                    $deads[] = $actor;
                else if ($actor->Count != $actor->TotalCount && $actor->ReinforceCost <= $threat)
                    $deads[] = $actor;
            }
            if (count($deads) > 0) {
                shuffle($deads);
                if ($deads[0]->Count == 0) {
                    echo "<br>repop de {$deads[0]->Name}";
                    $deads[0]->Count = $deads[0]->TotalCount;
                    $threat -= $deads[0]->TotalCost;
                } else {
                    echo "<br>reinforce de {$deads[0]->Name}";
                    $deads[0]->Count++;
                    $threat -= $deads[0]->ReinforceCost;
                }
                echo "<br>plus personne a reenforcer.";
            }




        }
        echo "<br>end round<br>Objectif: $objective/$objectiveNeeded<br>";

    }

    return (object)array("result" => ($objective >= $objectiveNeeded ? "reussite" : "echec"), "players" => $Players);
}

function Resolve($actor, $target, $seed, $attackerLevel = 1, $defenderLevel = 1)
{
    $greenDice = [0, 1, 0, 1, 2, 2];
    $greenDiceSurge = [0, 1, 1, 1, 0, 0];

    $blueDice = [0, 1, 1, 2, 2, 1];
    $blueDiceSurge = [1, 0, 1, 0, 0, 0];

    $yellowDice = [1, 0, 0, 1, 1, 2];
    $yellowDiceSurge = [2, 1, 1, 0, 1, 0];

    $redDice = [1, 2, 2, 2, 3, 3];
    $redDiceSurge = [0, 1, 0, 0, 0, 0];

    $blackDice = [0, 1, 1, 2, 2, 3];
    $blackDiceSurge = [1, 0, 0, 0, 0, 0];

    $whiteDice = [0, 0, 0, 1, 1, 0];
    $whiteDiceSurge = [0, 1, 0, 1, 1, 0];
    $whiteDiceCancel = [0, 0, 0, 0, 0, 1];


    echo "<br>Cible: {$target->Name}, {$target->ActualHealth}hp";


    $bestDamage = 0;
    $bestSurge = 0;

    srand($seed);
    for ($iRoll = 0; $iRoll < $attackerLevel; $iRoll++) {
        $Damage = 0;
        $Surge = 0;

        for ($iDice = 1; $iDice <= $actor->MainWeapon->AttackBlue; $iDice++) {
            $roll = rand(0, 5);
            $Damage += $blueDice[$roll];
            $Surge += $blueDiceSurge[$roll];
        }
        for ($iDice = 1; $iDice <= $actor->MainWeapon->AttackGreen; $iDice++) {
            $roll = rand(0, 5);
            $Damage += $greenDice[$roll];
            $Surge += $greenDiceSurge[$roll];
        }

        for ($iDice = 1; $iDice <= $actor->MainWeapon->AttackYellow; $iDice++) {
            $roll = rand(0, 5);
            $Damage += $yellowDice[$roll];
            $Surge += $yellowDiceSurge[$roll];
        }

        for ($iDice = 1; $iDice <= $actor->MainWeapon->AttackRed; $iDice++) {
            $roll = rand(0, 5);
            $Damage += $redDice[$roll];
            $Surge += $redDiceSurge[$roll];
        }
        if ($bestDamage < $Damage) {
            $bestDamage = $Damage;
            $bestSurge = $Surge;
        }
    }




    $bestBlock = 0;
    $bestCancelSurge = 0;
    for ($iRoll = 0; $iRoll < $defenderLevel; $iRoll++) {
        $Block = 0;
        $CancelSurge = 0;
        for ($defenseRoll = 0; $defenseRoll < $target->GetAllBlackDefense(); $defenseRoll++) {
            $roll = rand(0, 5);
            $Block += $blackDice[$roll];
            $CancelSurge += $blackDiceSurge[$roll];

        }
        for ($defenseRoll = 0; $defenseRoll < $target->GetAllWhiteDefense(); $defenseRoll++) {
            $roll = rand(0, 5);
            $Block += $whiteDice[$roll];
            $CancelSurge += $whiteDiceSurge[$roll];
            if ($whiteDiceCancel[$roll] == 1)
                $Block = 999999;
        }

        if ($bestBlock < $Block) {
            $bestBlock = $Block;
            $bestCancelSurge = $CancelSurge;
        }
    }



    echo "<br>Score: $bestDamage, surge: $bestSurge, block: $bestBlock, cancel surge $bestCancelSurge";

    $bestSurge -= $bestCancelSurge;
    $bestCancelSurge = 0;

    if ($bestSurge < 0) $bestSurge = 0;


    $DamageSurge = $bestSurge;
    if ($DamageSurge > $actor->MainWeapon->SurgeToDamage)
        $DamageSurge = $actor->MainWeapon->SurgeToDamage;
    $bestSurge -= $DamageSurge;


    $PierceSurge = $bestSurge;
    if ($PierceSurge > $actor->MainWeapon->SurgeToPierce)
        $PierceSurge = $actor->MainWeapon->SurgeToPierce;

    $bestSurge -= $PierceSurge;


    $bestBlock -= $actor->MainWeapon->Pierce;
    $bestBlock -= $PierceSurge;

    $bestDamage -= $bestBlock;
    $bestDamage += $DamageSurge;

    if ($bestDamage < 0) $bestDamage = 0;


    echo " -> score: $bestDamage, surge: $bestSurge, ";






    $target->ActualHealth -= $bestDamage;
    if ($target->ActualHealth <= 0) {
        $target->ActualHealth = $target->Health;
        $target->Count--;
        if ($target->Count <= 0)
            $target->ActualHealth = 0;
        echo " -one down- ";
    }


    echo "reste {$target->ActualHealth}/{$target->Health}";

}




function GetBestPlayerTarget($players, $Seed)
{

    $targets = array();

    foreach ($players as $possibleTarget)
        if ($possibleTarget->ActualHealth > 0)
        $targets[] = $possibleTarget;
    $target = rand(0, count($targets) - 1);

    return $targets[$target];
}



function GetBestTarget($ennemies, $seed)
{
    $targets = array();

    foreach ($ennemies as $possibleTarget)
        if ($possibleTarget->Health * $possibleTarget->Count > 0)
        $targets[] = $possibleTarget;

    $array = array();
    srand($seed);

    foreach ($targets as $enemy) {
        if ($enemy->ActualHealth <= 0) continue;

        $array[] = (object)array('obj' => $enemy, 'score' => ($enemy->Health - $enemy->ActualHealth) * 100 + rand(10, 99));

    }




    usort($array, "CompareScores");


    return reset($array)->obj;

}

function CompareScores($a, $b)
{
    if ($a->score > $b->score) return -1;

    if ($a->score < $b->score) return 1;

    return 0;

}
?>