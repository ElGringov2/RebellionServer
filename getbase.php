<?php
require_once("class.php");

$mysqli = GetMySQLConnection();
$user = new User();
$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");
$base = new OperationBase();
$base = DatabaseRead("operationbase", $mysqli, "owner='{$user->DatabaseID}'");



$planet = DatabaseRead('planet', $mysqli, "id=" . $base->PlanetID);
$xml = "<opbase PlanetName='{$planet->Name}' HangarSize='{$base->HangarSize}' HangarMechanics='{$base->HangarMechanics}' MedbayCapacity='{$base->MedbayCapacity}' MedbayBactaTanks='{$base->MedbayBactaTanks}'  >";



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