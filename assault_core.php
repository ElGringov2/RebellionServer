<?php





function GetAssaultCommandoFromWikia(string $HeroName) : Commando
{

    $name = str_replace(" ", "_", $HeroName);
    $name = str_replace("'", "%27", $name);
    $content = file_get_contents("http://imperial-assault.wikia.com/wiki/{$name}_(Hero)");


    $player = new Commando();
    $player->Name = GetString($content, "<h2 class=\"pi-item pi-item-spacing pi-title\">", "</h2>")["value"];
    $healthInfo = GetInt($content, "\"pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing\"", "</td>");
    $player->Health = $healthInfo["value"];
    $enduranceInfo = GetInt($content, "\"pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing\"", "</td>", $healthInfo["pos"]);
    $player->Endurance = $enduranceInfo["value"];
    $speedInfo = GetInt($content, "\"pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing\"", "</td>", $enduranceInfo["pos"]);
    $player->Move = $speedInfo["value"];
    if (strpos($content, "alt=\"Black Die\"") != false)
        $player->DefenseBlack = 1;
    else
        $player->DefenseWhite = 1;

    $strSkillToFind = "<td class=\"pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing\"><a href=\"/wiki/Dice\"";
    $endSkillToFind = "</noscript></a></td>";
    $start = $speedInfo["pos"];
    $start = strpos($content, $strSkillToFind, $start + 1);


    $start = strpos($content, $strSkillToFind, $start + 1);
    $end = strpos($content, $endSkillToFind, $start);
    $lineContent = substr($content, $start, $end - $start);
    $player->Strength = (substr_count($lineContent, "alt=\"Red Die\"") * 1 / 6 +
        substr_count($lineContent, "alt=\"Green Die\"") * 3 / 6 +
        substr_count($lineContent, "alt=\"Blue Die\"") * 2 / 6 +
        substr_count($lineContent, "alt=\"Yellow Die\"") * 5 / 6) / 4;


    $start = strpos($content, $strSkillToFind, $start + 1);
    $end = strpos($content, $endSkillToFind, $start);
    $lineContent = substr($content, $start, $end - $start);
    $player->Insight = (substr_count($lineContent, "alt=\"Red Die\"") * 1 / 6 +
        substr_count($lineContent, "alt=\"Green Die\"") * 3 / 6 +
        substr_count($lineContent, "alt=\"Blue Die\"") * 2 / 6 +
        substr_count($lineContent, "alt=\"Yellow Die\"") * 5 / 6) / 4;


    $start = strpos($content, $strSkillToFind, $start + 1);
    $end = strpos($content, $endSkillToFind, $start);
    $lineContent = substr($content, $start, $end - $start);
    $player->Tech = (substr_count($lineContent, "alt=\"Red Die\"") * 1 / 6 +
        substr_count($lineContent, "alt=\"Green Die\"") * 3 / 6 +
        substr_count($lineContent, "alt=\"Blue Die\"") * 2 / 6 +
        substr_count($lineContent, "alt=\"Yellow Die\"") * 5 / 6) / 4;

    $pos = strpos($content, ">Weapon<");
    $text = "";
    $url = strpos($content, "\"pi-horizontal-group-item pi-data-value pi-font pi-border-color pi-item-spacing\">", $pos);
    $url = strpos($content, "<a href=", $url + 1);
    $url = strpos($content, "\"", $url + 1);
    $url2 = strpos($content, "\" title=", $url + 1);
    $text = substr($content, $url + 1, $url2 - $url - 1);


    $weaponContent = file_get_contents("http://imperial-assault.wikia.com" . $text);
    $weapon = new AssaultItem();
    $weapon->Name = GetString($weaponContent, "<h2 class=\"pi-item pi-item-spacing pi-title\">", "</h2>")["value"];

    $weapon->AttackBlue = substr_count($weaponContent, "Blue Die") / 2;
    $weapon->AttackRed = substr_count($weaponContent, "Red Die") / 2;
    $weapon->AttackYellow = substr_count($weaponContent, "Yellow Die") / 2;
    $weapon->AttackGreen = substr_count($weaponContent, "Green Die") / 2;

    $weapon->SurgeToDamage = substr_count($weaponContent, "+1 <a href=\"/wiki/Damage\"");
    $weapon->SurgeToDoubleDamage = substr_count($weaponContent, "+2 <a href=\"/wiki/Damage\"");

    $weapon->SurgeToPierce = substr_count($weaponContent, "</noscript></a>: Pierce 1</div>");
    $weapon->SurgeToDoublePierce = substr_count($weaponContent, "</noscript></a>: Pierce 2</div>");
    $weapon->SurgeToTriplePierce = substr_count($weaponContent, "</noscript></a>: Pierce 3</div>");


    $player->MainWeapon = $weapon;
    return $player;
}



function GetString($content, $strToFind, $endStrToFind, int $offset = 0)
{
    $pos = strpos($content, $strToFind, $offset);
    $pos = strpos($content, ">", $pos) + 1;
    $endPos = strpos($content, $endStrToFind, $pos);
    return array("value" => trim(substr($content, $pos, $endPos - $pos)), "pos" => $pos);

}

function GetInt($content, $strToFind, $endStrToFind, int $offset = 0)
{
    $result = GetString($content, $strToFind, $endStrToFind, $offset);
    return array("value" => intval($result["value"]), "pos" => $result["pos"]);
}



function CreateAssaultCommandosFile() : array
{
    $heroes = array("Diala Passil", "Fenn Signis", "Gaarkhan", "Gideon Argus", "Jyn Odan", "Mak Eshka'rey", "Biv Bodhrik", "Saska Teft", "Loku Kanoloa", "MHD-19", "Verena Talos");



    $array = array();
    foreach ($heroes as $hero)
        $array[] = GetHero($hero);



    $data = serialize($array);

    file_put_contents('assault_heroes.data', $data);

    return $array;
}

function GetAssaultCommandos() : array
{
    $data = file_get_contents("assault_heroes.data");
    return unserialize($data);
}









?>