<?php

require_once("class.php");

$username = $_POST["txtUser"];
$password = $_POST["txtPassword"];
$mysqli = GetMySQLConnection();

$user = DatabaseRead("user", $mysqli, "username='$username' and password='$password'");

if ($user == null)
    echo "<login error=\"Nom d'utilisateur/mot de passe erroné\" guid=\"\" />";
else {
    $user->ConnectionGUID = guidv4();
    DatabaseWrite($user, $mysqli);
    $commander = DatabaseRead("commander", $mysqli, "user='{$user->DatabaseID}'");
    echo "<login guid='" . $user->ConnectionGUID . "' action='";
    if ($commander == null)
        echo "choosecommander";
    else {
        $myPilots = DatabaseReadAll("pilot", $mysqli, "owner='{$user->DatabaseID}'");
        if (count($myPilots) < 12)
            echo "draftxwing";
    }
    echo "' error='' />";
}

?>