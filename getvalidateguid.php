<?php
require_once("class.php");
$guid = $_POST["verifyguid"];
if ($guid == "") echo "ERROR";
else {

    $user = DatabaseRead('user', GetMySQLConnection(), "connectionguid='$guid'");

    if ($user == null)
        echo "ERROR";
    else
        echo "VALID";

}
?>