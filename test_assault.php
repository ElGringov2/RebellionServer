<!-- <script src="Chart.js"></script>
<canvas id="myChart" width="400" height="400"></canvas> -->
<?php
require_once("commando.php");
require_once("AssaultEnnemy.php");
require_once("AssaultMission.php");
require_once("AssaultItem.php");
require_once("db.php");
// $missions = DatabaseReadAll("assaultmission", GetMySQLConnection());

// $mission = clone $missions[rand(0, count($missions) - 1)];
// echo json_encode($mission);


// return;

echo "Autoresolver - tests<br/>";

echo "srand=1<br/>";
srand(1);

function GetAssaultCommandos() : array
{
    $data = file_get_contents("assault_heroes.data");
    return unserialize($data);
}

$threatExperience = 3;
$allCommandos = GetAssaultCommandos();


$players = array();

echo "Joueurs: ";


$index = rand(0, count($allCommandos) - 1);
$players[] = $allCommandos[$index];
echo $allCommandos[$index]->Name . ", ";
array_splice($allCommandos, $index, 1);

$index = rand(0, count($allCommandos) - 1);
$players[] = $allCommandos[$index];
echo $allCommandos[$index]->Name . ", ";
array_splice($allCommandos, $index, 1);

$index = rand(0, count($allCommandos) - 1);
$players[] = $allCommandos[$index];
echo $allCommandos[$index]->Name . " ";
array_splice($allCommandos, $index, 1);

$index = rand(0, count($allCommandos) - 1);
$players[] = $allCommandos[$index];
echo " et " . $allCommandos[$index]->Name . ".<br/>";

$missions = DatabaseReadAll("assaultmission", GetMySQLConnection());

// foreach ($players as $player) {
//     $player->Experience += rand(15, 30);
// }

// $miss = clone $missions[1];

// $result = AutoResolve($players, 1, 2, $miss);

// return   ;
$stats = array();

for ($iExperience = 2; $iExperience < 40; $iExperience *= 2) {
    foreach ($players as $player) {
        $player->Experience = $iExperience;
    }

    foreach ($missions as $mission) {
        echo "<hr>Mission: {$mission->Name}: tour: {$mission->Turn}, objectifs à réaliser: {$mission->ObjectivePoints}, taille de la zone: {$mission->Size} unitées, experience des joueurs: $iExperience.<br/>Ennemis: ";
        $ennemies = $mission->CreateEnnemies(GetMySQLConnection());
        foreach ($ennemies as $ennemy) {
            if ($ennemy->TotalCount > 0)
                echo " {$ennemy->Name} x{$ennemy->Count}";
        }


        $iSuccess = 0;
        $iFailureIfObjective = 0;
        $iFailureIfDead = 0;

        $tentative = 100;
        echo "<br><br>Début des $tentative tentatives:<br/>";

        for ($i = 1; $i <= $tentative; $i++) {




            ob_start();
            srand($i);


            $result = AutoResolve($players, 1, 2, $mission);

            ob_end_clean();

            if ($result->result == "reussite") $iSuccess++;
            else {
                $failDead = true;
                foreach ($players as $pl)
                    if ($pl->Count > 1)
                    $failDead = false;

                if ($failDead) $iFailureIfDead++;
                else $iFailureIfObjective++;
            }

        }
        echo "OK. Taux de réussite: " . (int)($iSuccess / $tentative * 100) . "%<br>Détail des echecs: " .
            (int)($iFailureIfDead / $tentative * 100) . "% wipe équipe, " .
            (int)($iFailureIfObjective / $tentative * 100) . "% objectif non atteint.<br><hr>";
        $stats[$mission->Name . " avec XP$iExperience"] = array((int)($iSuccess / $tentative * 100), (int)($iFailureIfDead / $tentative * 100), (int)($iFailureIfObjective / $tentative * 100));
    }
}
?>
<!-- <script>
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php 
                foreach ($stats as $key => $value) {
                    echo "\"$key\", ";
                }
                ?>],
        data: [<?php
                foreach ($stats as $item) {
                    echo "\"{$item[0]}\", ";
                }


                ?>]}
};
</script>


 -->

<?php






/**
 * Autorun selected mission
 * 
 * @param array $Players Array of Commando
 * @param array $Ennemies array of AssaultEnnemies
 * @param int $ThreatPerRoungs amount of threat gaigned every rounds
 * @param int $threat starting threat
 * @param AssaultMission $mission Mission to be runned
 */
function AutoResolve(array $Players, int $ThreatPerRound, int $threat, AssaultMission $mission) : stdClass
{

    echo "<br/>Autorunning {$mission->Name}";
    $Ennemies = $mission->CreateEnnemies(GetMySQLConnection());
    $movePerObjectif = $mission->Size / $mission->ObjectivePoints;


    $objectiveNeeded = $mission->ObjectivePoints;
    $objective = 0;
    $move = array();

    foreach ($Players as $pl) {
        $move[$pl->Name] = 0;
        $pl->Count = 2;
        $pl->ActualHealth = $pl->Health;
    }

    for ($i = 1; $i <= $mission->Turn; $i++) {

        $idPlayer = 0;
        $idEnnemy = 0;


        shuffle($Ennemies);
        shuffle($Players);



        echo "<hr>round $i:";
        while ($idPlayer < count($Players) || $idEnnemy < count($Ennemies)) {

            while ($idPlayer < count($Players) && $Players[$idPlayer]->Count == 0)
                $idPlayer++;


            if ($idPlayer != count($Players)) {

                $actor = $Players[$idPlayer];

                echo "<br><br>personnage: {$actor->Name}";

                for ($PlayerAction = 0; $PlayerAction < 2; $PlayerAction++) {


                    //4 actions possible: déplacement - attaque - repos - objectif
                    
                    //déplacement
                    $ProbabilityMove = (2 - $PlayerAction); //Par défaut, 6/3 points .
                    if ($movePerObjectif * ($objective + 1) > $move[$actor->Name]) {
                        $ProbabilityMove += 2; //Si on ne peux pas faire d'objectif, il faut se déplacer.
                    }
                    if ($objective >= $objectiveNeeded)
                        $ProbabilityMove = 6; // 6/6 poins si l'objectif est atteint.
                    if ($move[$actor->Name] >= $mission->Size)
                        $ProbabilityMove = 0;

                    //Objectifs
                    $ProbabilityObjective = 0; //Par défaut, 0/0 points.
                    if ($movePerObjectif * ($objective + 1) < $move[$actor->Name]) {
                        $ProbabilityObjective = 2 + $PlayerAction * 6; // 2/8 points si à portée.
                    }
                    if ($objective >= $objectiveNeeded)
                        $ProbabilityObjective = 0; // 0/0 points si l'objectif est réussi.

                    $ProbabilityRest = 0; // Par défaut, 0/0 points.
                    if ($actor->ActualHealth < 6)
                        $ProbabilityRest += 3 * (1 - $PlayerAction); //Ca va mal, 3/0 points.
                    if ($actor->ActualHealth < 3)
                        $ProbabilityRest += 3 * (1 - $PlayerAction); //Ca va très mal, 6/0 points.

                    $ProbabilityAttack = ($PlayerAction + 1) * 2; // Par défaut, 2/4. L'inverse du déplacement.
                    $someTarget = GetBestTarget($Ennemies, rand());
                    if ($someTarget == null)
                        $ProbabilityAttack = 0; //Pas d'attaque si pas de cible...

                    if ($objective >= $objectiveNeeded && $move[$actor->Name] < $mission->Size)
                        $ProbabilityAttack = 0; // 0/0 points si l'objectif est réussi.




                    $selectedAction = rand(1, $ProbabilityAttack + $ProbabilityMove + $ProbabilityObjective + $ProbabilityRest);
                    echo "<br/>Attack=$ProbabilityAttack, Move=$ProbabilityMove, Objective=$ProbabilityObjective, Rest=$ProbabilityRest. Selected=$selectedAction";

                    if ($selectedAction == 0) {
                        echo "<br>Passe.";
                    } else if ($selectedAction <= $ProbabilityAttack) {
                        echo "<br>Attaque.";
                        $target = GetBestTarget($Ennemies, rand());

                        Resolve($actor, $target, rand(), $actor->GetLevel(), 1);
                    } else if ($selectedAction <= $ProbabilityAttack + $ProbabilityMove) {
                        echo "<br>Déplacement: ";
                        $move[$actor->Name] += $actor->Move;
                        echo $move[$actor->Name] . "cases.";
                    } else if ($selectedAction <= $ProbabilityAttack + $ProbabilityMove + $ProbabilityRest) {
                        $hp = rand(2, 5);
                        echo "<br>Repos. Gain de $hp";
                        $actor->ActualHealth += $hp;
                    } else {
                        $objType = ($objective + 1) % 4;
                        echo "<br>Objectif de type $objType: ";
                        if ($objType == 0) {
                            $objective++;
                            echo "Réussi.";
                        } else if ($objType == 1 && rand(1, 100) > $actor->Tech * 100) {
                            $objective++;
                            echo "Réussi.";
                        } else if ($objType == 2 && rand(1, 100) > $actor->Insight * 100) {
                            $objective++;
                            echo "Réussi.";
                        } else if ($objType == 3 && rand(1, 100) > $actor->Strength * 100) {
                            $objective++;
                            echo "Réussi.";
                        } else
                            echo "Echoué.";
                    }

                }
                $idPlayer++;

            }
            if ($objective >= $objectiveNeeded) {
                echo "<br>Objective done";
                $sizeOk = true;
                foreach ($Players as $pl) {
                    if ($move[$pl->Name] < $mission->Size)
                        $sizeOk = false;
                }
                if (!$sizeOk && $mission->NeedExit == 1) {
                    echo ", but player on board.";
                } else {
                    echo ", break.";
                    break;
                }
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

        if ($objective >= $objectiveNeeded) {
            echo "<br>Objective done";
            $sizeOk = true;
            foreach ($Players as $pl) {
                if ($move[$pl->Name] < $mission->Size)
                    $sizeOk = false;
            }
            if (!$sizeOk && $mission->NeedExit == 1) {
                echo " but player on board.";
            } else
                break;
        }

    }

    return (object)array("result" => ($objective >= $objectiveNeeded ? "reussite" : "echec"), "players" => $Players, "objectives" => $objective);
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
    if ($DamageSurge > $actor->MainWeapon->SurgeToDoubleDamage)
        $DamageSurge = $actor->MainWeapon->SurgeToDoubleDamage;
    $bestSurge -= $DamageSurge;
    $DamageSurge *= 2;


    $DamageSurge = $bestSurge;
    if ($DamageSurge > $actor->MainWeapon->SurgeToDamage)
        $DamageSurge = $actor->MainWeapon->SurgeToDamage;
    $bestSurge -= $DamageSurge;


    $TriplePierceSurge = $bestSurge;
    if ($TriplePierceSurge > $actor->MainWeapon->SurgeToTriplePierce)
        $TriplePierceSurge = $actor->MainWeapon->SurgeToTriplePierce;

    $bestSurge -= $TriplePierceSurge;


    $DoublePierceSurge = $bestSurge;
    if ($DoublePierceSurge > $actor->MainWeapon->SurgeToDoublePierce)
        $DoublePierceSurge = $actor->MainWeapon->SurgeToDoublePierce;

    $bestSurge -= $DoublePierceSurge;

    $PierceSurge = $bestSurge;
    if ($PierceSurge > $actor->MainWeapon->SurgeToPierce)
        $PierceSurge = $actor->MainWeapon->SurgeToPierce;

    $bestSurge -= $PierceSurge;


    $bestBlock -= $actor->MainWeapon->Pierce;
    $bestBlock -= $PierceSurge + ($DoublePierceSurge * 2) + ($TriplePierceSurge * 3);

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

    if (count($targets) == 0)
        return null;
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


    if (count($array) == 0) return null;

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