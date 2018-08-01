<?php
require('class.php');


$mysqli = GetMySQLConnection();



function CreateFakeValues(mysqli $mysqli)
{

    CreateTable("planning", $mysqli);

    srand(1);

    $users = array();

    for ($iUser = 0; $iUser < 8; $iUser++) {
        $usr = new User();
        $usr->CommanderName = "Commandant " . ($iUser + 1);
        $usr->Name = "cmdr" . ($iUser + 1);
        $usr->Pass = "cmdr" . ($iUser + 1);
        $usr->DatabaseID = $iUser + 1;
        $users[] = $usr;
        for ($i = 1; $i < 30; $i++) {
            $pref = rand(1, 10);
            if ($pref <= 5) continue;
            $day = new Planning();
            $day->Preference = $pref - 5;
            $date = new DateTime();
            $date->add(new DateInterval("P{$i}D"));
            $day->Day = $date;
            $day->UserID = $usr->DatabaseID;
            $day->UserName = $usr->CommanderName;
            DatabaseWrite($day, $mysqli);

        }
    }



}


$day = Planning::GetDay($mysqli, new DateTime("2018-07-30"));

var_dump($day);





?>