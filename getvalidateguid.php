<?php
require_once("class.php");
$guid = $_POST["verifyguid"];

$user = DatabaseRead('user', GetMySQLConnection(), "connectionguid='$guid'");

if ($user == null)
    echo "ERROR";
else
    echo "VALID";


?>