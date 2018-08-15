<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

if (isset($_POST["discover"])) {

    if ($_POST["assettype"] == "pilot") {
        Mission::GenerateCampaign($_POST["planetid"], 0, $mysqli);
    } else
        if ($_POST["assettype"] == "assault") {
        Mission::GenerateCampaign($_POST["planetid"], 2, $mysqli);
    } else
        if ($_POST["assettype"] == "legion") {
        Mission::GenerateCampaign($_POST["planetid"], 3, $mysqli);

    }
}

?>