<?php
require_once("class.php");

$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

if (isset($_POST["ship"])) { //Selection d'un vaisseau, envoi des upgrades possible
    $ship = new Pilot(); //TypeHint
    $ship = DatabaseRead('pilot', $mysqli, 'id=' . $_POST["ship"]);
    $stock = Upgrade::GetMyInstallableStock($ship, $user, $mysqli);
    echo "<stock>";
    $item = new Upgrade();//TypeHint
    foreach ($stock as $item)
        echo "<upgrade name='{$item->Name}' xws='{$item->XWS}'  desc='{$item->Desc}' cost='{$item->GetCost($ship)}' type='{$item->Type}' />";
    echo "</stock>";
    $ship->Flight = "TakeOff";
    DatabaseWrite($ship, $mysqli);
} else if (isset($_POST["shipid"])) {//Selection de l'upgrade sur le vaisseau
    $stockUpgrade = DatabaseRead('stock', $mysqli, "owner='{$user->DatabaseID}' and xws='{$_POST["xws"]}'");
    $upgrades = Upgrade::GetAllUpgrades();
    $upgrade = $upgrades[array_search($stockUpgrade->XWS, array_column($upgrades, "xws"))];
    $ship = new Pilot();//TypeHint
    $ship = DatabaseRead('pilot', $mysqli, 'id=' . $_POST["shipid"]);
    $cost = $ship->Upgrades[$_POST["upgradeid"]]->GetCost($ship);
    $ship->Upgrades[$_POST["upgradeid"]] = Upgrade::FromJSONArray($upgrade);
    DatabaseWrite($ship, $mysqli);
    DatabaseRemove($stockUpgrade, $mysqli);

    echo (-$cost + Upgrade::FromJSONArray($upgrade)->GetCost($ship));
} else if (isset($_POST["clear"])) { // Suppresson d'un seul vaisseau
    $ship = new Pilot();//TypeHint
    $ship = DatabaseRead('pilot', $mysqli, 'id=' . $_POST["shiptoremove"]);
    echo $ship->GetTotalCost();
    $upgrade = new Upgrade();//TypeHint
    foreach ($ship->Upgrades as $upgrade) {
        $stock = new Stock();
        $stock->XWS = $upgrade->XWS;
        $stock->Owner = $user->DatabaseID;
        $stock->Name = $upgrade->Name;
        DatabaseWrite($stock, $mysqli);
        $upgrade->Name = "vide";
        $upgrade->XWS = "";
        $upgrade->Cost = array(
            "basecost" => 0,
            "costagi2" => 0,
            "costagi3" => 0,
            "costagi4" => 0,
            "costmed" => 0,
            "costlarge" => 0
        );
        $upgrade->Desc = "";

    }
    $ship->Flight = "Au sol";
    DatabaseWrite($ship, $mysqli);

} else if (isset($_POST["cancel"])) { //Annulation du décollage.
    $pilots = DatabaseReadAll('pilot', $mysqli, "owner='{$user->DatabaseID}' AND flight='TakeOff'");
    foreach ($pilots as $pilot) {
        $upgrade = new Upgrade();//TypeHint
        foreach ($pilot->Upgrades as $upgrade) {
            $stock = new Stock();
            $stock->XWS = $upgrade->XWS;
            $stock->Owner = $user->DatabaseID;
            $stock->Name = $upgrade->Name;
            DatabaseWrite($stock, $mysqli);
            $upgrade->Name = "vide";
            $upgrade->XWS = "";
            $upgrade->Cost = array(
                "basecost" => 0,
                "costagi2" => 0,
                "costagi3" => 0,
                "costagi4" => 0,
                "costmed" => 0,
                "costlarge" => 0
            );
            $upgrade->Desc = "";
        }
        $pilot->Flight = "Au sol";
        DatabaseWrite($pilot, $mysqli);
    }
} else if (isset($_POST["takeoff"])) { //Validation de l'escouade
    $flightNumber = Pilot::GetNextFlightNumber($_POST["squadron"], $mysqli);
    $mysqli->query("UPDATE rebellion_pilot SET flight='Escadrille $flightNumber' WHERE owner='{$user->DatabaseID}' AND flight='TakeOff'");


} else { //Stock complet.
    $stock = DatabaseReadAll('stock', $mysqli, "owner='{$user->DatabaseID}'");
    $upgrades = Upgrade::GetAllUpgrades();
    echo "<stock>";
    $item = new Stock();
    foreach ($stock as $item) {
        $upgrade = $upgrades[array_search($item->XWS, array_column($upgrades, "xws"))];
        //echo "<upgrade name='{$upgrade["name"]}' xws='{$upgrade["xws"]}' desc='{$upgrade["desc"]}' />";
        echo "<upgrade name='{$upgrade["name"]}' xws='{$upgrade["xws"]}' desc='' type='{$upgrade["type"]}' />";
    }
    echo "</stock>";
}



?>