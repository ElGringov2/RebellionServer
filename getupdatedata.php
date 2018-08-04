<?php
require_once("class.php");

$mysqli = GetMySQLConnection();
$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

$commander = DatabaseRead("commander", $mysqli, "id={$_POST['selectedCmd']}");
$commander->UserID = $user->DatabaseID;
$user->CommanderName = $_POST["cmdName"];
DatabaseWrite($commander, $mysqli);




?>