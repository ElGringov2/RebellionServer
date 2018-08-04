<?php
require_once("class.php");
$guid = $_POST["verifyguid"];
$mysqli = GetMySQLConnection();
if ($guid == "") echo "ERROR";
else {

    $user = DatabaseRead('user', $mysqli, "connectionguid='$guid'");

    if ($user == null)
        echo "ERROR";
    else {

        $commander = DatabaseRead("commander", $mysqli, "user='{$user->DatabaseID}'");

        if ($commander == null) {
            echo "CHOOSECOMMANDER";
            return;
        }

        $myPilots = DatabaseReadAll("pilot", $mysqli, "owner='{$user->DatabaseID}'");
        if (count($myPilots) < 12) {

            echo "DRAFTXWING";
            return;
        }

        echo "VALID";


    }





}
?>