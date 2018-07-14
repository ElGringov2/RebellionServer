<?php

require_once("class.php");

$username = $_POST["txtUser"];
$password = $_POST["txtPassword"];


$user = DatabaseRead("user", GetMySQLConnection(), "username='$username' and password='$password'");

if ($user == null)
    echo "<login error=\"Nom d'utilisateur/mot de passe erroné\" guid=\"\" />";
else {
    $user->ConnectionGUID = com_create_guid();
    echo "<login guid='" .$user->ConnectionGUID. "' error='' />";
    DatabaseWrite($user, GetMySQLConnection());
}



?>