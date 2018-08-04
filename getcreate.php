<?php

require_once("class.php");

$username = $_POST["txtUser"];
$password = $_POST["txtPassword"];
$mail = $_POST["txtMail"];




$user = DatabaseRead("user", GetMySQLConnection(), "username='$username'");

if ($user != null) {
    echo "<create error=\"Utilisateur existant\" />";
    return;
}

$mailuser = DatabaseRead("user", GetMySQLConnection(), "mail='$mail'");
if ($mailuser != null) {
    echo "<create error=\"Adresse mail existante\" />";
    return;
}

$user = new User();
$user->Name = $username;
$user->Pass = $password;
$user->ConnectionGUID = guidv4();

DatabaseWrite($user, GetMySQLConnection());

echo "<create userguid='{$user->ConnectionGUID}' error=\"none\" />";



?>