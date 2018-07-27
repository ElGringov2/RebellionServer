<?php
require_once("class.php");



$mysqli = GetMySQLConnection();
$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"]."'");


echo "<userinfos name='{$user->CommanderName}' crate='3' credits='{$user->Credits}' />";


?>