<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");
$base = DatabaseRead("operationbase", $mysqli, "owner='{$user->DatabaseID}'");



$xml = str_replace("operationbase", "opbase", ToXML($base));
$xml = str_replace("/>", ">", $xml);


$grounded = DatabaseReadAll('pilot', $mysqli, "flight='Au sol'");
$sick = DatabaseReadAll('pilot', $mysqli, "flight='Infirmerie'");

$xml .= "<Grounded>";
foreach ($grounded as $pilot) {
    $xml .= $pilot->ToXML($mysqli);
}
$xml .= "</Grounded>";
$xml .= "<Sick>";
foreach ($sick as $pilot) {
    $xml += $pilot->ToXML($mysqli);
}
$xml .= "</Sick>";
$xml .= "</opbase>";

echo $xml;


?>