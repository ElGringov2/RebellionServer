<?php

class Pilot
{

    /**
     * @DatabaseType text
     * @DatabaseName name
     */
    public $Name;

    /**
     * @DatabaseType int(10)
     * @DatabaseName id
     * @DatabasePrimary
     */
    public $DatabaseID = -1;

    /**
     * @DatabaseType text
     * @DatabaseName squadron
     */
    public $Squadron;



    /**
     * @DatabaseType text
     * @DatabaseName flight
     */
    public $Flight;

    /**
     * @DatabaseType int(10)
     * @DatabaseName location
     */
    public $Location;

    /**
     * @DatabaseType text
     * @DatabaseName ship
     */
    public $ShipName = "xwing";

    /**
     * @DatabaseType int(10)
     * @DatabaseName owner
     */
    public $Owner;

    /**
     * @DatabaseType int(2)
     * @DatabaseName condition
     */
    public $Condition = 10;

    /**
     * @DatabaseType text
     * @DatabaseName pilotability
     */
    public $Ability = "Aucune";

    /**
     * @DatabaseType text
     * @DatabaseName shipability
     */
    public $ShipAbility = "Aucune";
    /**
     * Le cout
     *
     * @var integer
     * @DatabaseType int(2)
     * @DatabaseName cost
     */
    public $Cost = 0;



    function GetTotalCost()
    {

        $upgradeCost = $this->Cost;
        foreach ($this->Upgrades as $upgrade) {
            if (!is_a($upgrade, "Upgrade"))
                $upgrade = Upgrade::FromJSONArray($upgrade);
            $upgradeCost += $upgrade->GetCost($this);
        }
        return $upgradeCost;
    }


    /**
     * @DatabaseType int(2)
     * @DatabaseName attack
     */
    public $Attack = 1;

    /**
     * @DatabaseType int(2)
     * @DatabaseName agility
     */
    public $Agility = 1;

    /**
     * @DatabaseType int(2)
     * @DatabaseName hull
     */
    public $Hull = 1;

    /**
     * @DatabaseType int(2)
     * @DatabaseName shields
     */
    public $Shields = 1;


    /**
     * @DatabaseType int(2)
     * @DatabaseName init
     */
    public $Initiative = 1;


    /**
     * @DatabaseType int(2)
     * @DatabaseName force
     */
    public $Force = 0;


    /**
     * La liste des upgrades
     *
     * @var array
     * @XmlIgnore
     * @DatabaseName upgrades
     * @DatabaseSerialize
     */
    public $Upgrades = array();


    /**
     * @DatabaseType int(2)
     * @DatabaseName unique
     */
    public $Unique = 0;

    /**
     * @DatabaseType int(2)
     * @DatabaseName size
     */
    public $Size = 1;

    function ToXML()
    {

        $upgradeStr = "<upgrades>";
        $upgrade = new Upgrade();
        foreach ($this->Upgrades as $upgrade) {
            if (!is_a($upgrade, "Upgrade"))
                $upgrade = Upgrade::FromJSONArray($upgrade);
            $upgradeStr .= $upgrade->ToXML($this);
        }
        $upgradeStr .= "</upgrades>";



        return sprintf(
            "<pilot name='%s' shipletter='%s' pilotskill='%s' shipattack='%s' shipagility='%s' shiphull='%s' shipshield='%s' pilotability=\"Pilote: %s &lt;br&gt;Vaisseau: %s\" shipname='%s' id='%s' cost='%s' condition='%s' squadron=\"%s\" flight=\"%s\"  >%s</pilot>",
            $this->Name,
            Pilot::GetShipLetter($this->ShipName),
            $this->Initiative,
            $this->Attack,
            $this->Agility,
            $this->Hull,
            $this->Shields,
            $this->Ability,
            $this->ShipAbility,
            $this->ShipName,
            $this->DatabaseID,
            $this->GetTotalCost(),
            $this->Condition,
            $this->Squadron,
            $this->Flight,
            $upgradeStr
        );
    }




    static function GetAllShips()
    {
        $data = json_decode(file_get_contents("xwingpilots.json"), true);

        return $data["pilots"];
    }


    /**
     * Retourne un pilote par rapport a un JSON de la base de donnée
     *
     * @param array $JSONData
     * @return Pilot
     */
    static function FromJSON(array $JSONData, int $id = -1) : Pilot
    {
        $pilot = new Pilot();
        if (isset($JSONData["ability"]))
            $pilot->Ability = $JSONData["ability"];
        if (isset($JSONData["shipability"]))
            $pilot->ShipAbility = $JSONData["shipability"];
        $pilot->ShipName = $JSONData["shipname"];
        if (isset($JSONData["agility"]))
            $pilot->Agility = $JSONData["agility"];
        if (isset($JSONData["attack"]))
            $pilot->Attack = $JSONData["attack"];
        if (isset($JSONData["initiative"]))
            $pilot->Initiative = $JSONData["initiative"];
        if (isset($JSONData["shields"]))
            $pilot->Shields = $JSONData["shields"];
        if (isset($JSONData["force"]))
            $pilot->Force = $JSONData["force"];
        if (isset($JSONData["hull"]))
            $pilot->Hull = $JSONData["hull"];
        if (isset($JSONData["size"]))
            $pilot->Size = intval($JSONData["size"]);
        $pilot->Cost = $JSONData["cost"];
        $pilot->Name = $JSONData["name"];
        $pilot->Unique = ($JSONData["unique"] ? 1 : 0);
        $pilot->Upgrades = array();
        $pilot->DatabaseID = $id;
        foreach ($JSONData["upgrade"] as $key => $value) {

            for ($i = 0; $i < $value; $i++) {
                $upgrade = new Upgrade();
                $upgrade->Type = $key;
                $upgrade->Name = "Vide";
                $pilot->Upgrades[] = $upgrade;
            }
        }




        return $pilot;
    }



    static function GetShipLetter(string $ShipName)
    {
        //$shipXWS = preg_replace('/[^a-z0-9]/i', '', strtolower($ShipName));
        $letters = array(
            "Aggressor Assault Fighter" => "i",
            "Alpha-class Star Wing" => "&",
            "ARC-170 Starfighter" => "c",
            "Attack Shuttle" => "g",
            "Auzituck Gunship" => "@",
            "RZ-1 A-Wing" => "a",
            "A/SF-01 B-Wing" => "b",
            "bsf17bomber" => "Z",
            "cr90corvette" => "2",
            "croccruiser" => "5",
            "E-Wing" => "e",
            "Firespray-class Patrol Craft" => "f",
            "G-1A Starfighter" => "n",
            "gozanticlasscruiser" => "4",
            "gr75mediumtransport" => "1",
            "HWK-290 Light Freighter" => "h",
            "ig2000" => "i",
            "JumpMaster 5000" => "p",
            "Kihraxz Fighter" => "r",
            "BTL-S8 K-Wing" => "k",
            "M12-L Kimogila" => "K",
            "Lambda-class T-4a Shuttle" => "l",
            "Lancer-class Pursuit Craft" => "L",
            "M3-A Interceptor" => "s",
            "protectoratestarfighter" => "M",
            "Quadrijet Transfer Spacetug" => "q",
            "raiderclasscorvette" => "3",
            "Scurrg H-6 bomber" => "H",
            "Sheathipede-class Shuttle" => "%",
            "StarViper-class Attack Platform" => "v",
            "t70xwing" => "w",
            "tieadvanced" => "A",
            "TIE Advanced v1" => "R",
            "TIE Advanced x1" => "R",
            "TIE/ag Aggressor" => "`",
            "TIE/sa Bomber" => "B",
            "TIE/D Defender" => "D",
            "TIE/ln Fighter" => "F",
            "tiefofighter" => "O",
            "TIE Interceptor" => "I",
            "TIE/ph Phantom" => "P",
            "TIE/ca Punisher" => "N",
            "tiesffighter" => "S",
            "tiesilencer" => "$",
            "TIE/sk Striker" => "T",
            "TIE Reaper" => "T",
            "upsilonclassshuttle" => "U",
            "UT-60D U-Wing" => "u",
            "VCX-100 Light Freighter" => "G",
            "VT-49 Decimator" => "d",
            "T-65 X-Wing" => "x",
            "Modified YT-1300 Light Freighter" => "m",
            "YT-2400 Light Freighter" => "o",
            "Customized YT-1300 Light Freighter" => "m",
            "Escape Craft" => "m",
            "YV-666 Light Freighter" => "t",
            "BTL-A4 Y-Wing" => "y",
            "Z-95-AF4 Headhunter" => "z",
            "Fang Fighter" => "z"
        );
        return $letters[$ShipName];
    }




    /**
     * Retourne la liste des pilotes à choisir pour le draft, uniquement si c'est le tour du joueur de choisir.
     *
     * @param User $user l'utilisateur
     * @param mysqli $mysqli une connection mysqli
     * @return string Le xml.
     */
    static function GetDraft(User $user, mysqli $mysqli) : string
    {
        $userCheck = DatabaseReadAll('user', $mysqli);
        $dict = array();
        foreach ($userCheck as $dbuser) {
            $dict[$dbuser->DatabaseID] = count(DatabaseReadAll("pilot", $mysqli, "owner='{$dbuser->DatabaseID}'"));
            if ($dbuser->DatabaseID == $user->DatabaseID && $dict[$dbuser->DatabaseID] >= 12)
                return "<draft done='1' />";
        }



        $dbuser = DatabaseRead('user', $mysqli, "id=" . array_keys($dict, min($dict))[0]);

        //echo "dbuser=".$user->DatabaseID.", min=".array_keys($dict, min($dict))[0];
        if ($dict[$user->DatabaseID] >= 4) {
            $pilots = Pilot::GetAllShips();
            usort($pilots, function ($a, $b) {
                if ($a["shipname"] == $b["shipname"])
                    return strcmp($a["name"], $b["name"]);
                return strcmp($a["shipname"], $b["shipname"]);
            });
            $str = "<draft>";
            foreach ($pilots as $key => $p) {
                if (!$p["unique"] && $p["faction"] == "Rebel Alliance")
                    $str .= Pilot::FromJSON($p, $key)->ToXML();
            }
            return $str . "</draft>";
        } else if ($dbuser->DatabaseID == $user->DatabaseID) {
            $uniques = DatabaseReadAll('unique', $mysqli);
            $pilots = Pilot::GetAllShips();
            usort($pilots, function ($a, $b) {
                if ($a["shipname"] == $b["shipname"])
                    return strcmp($a["name"], $b["name"]);
                return strcmp($a["shipname"], $b["shipname"]);
            });
            $str = "<draft>";
            foreach ($pilots as $key => $p) {
                if ($p["unique"] && $p["faction"] == "Rebel Alliance" && Unique::GetIfUniqueExist($p["name"], $mysqli) == false)
                    $str .= Pilot::FromJSON($p, $key)->ToXML();
            }
            return $str . "</draft>";

        } else
            return "<draft />";




    }
}


class Stock
{
    /**
     * @DatabaseType text
     * @DatabaseName name
     */
    public $Name;

    /**
     * @DatabaseType int(10)
     * @DatabaseName id
     * @DatabasePrimary
     */
    public $DatabaseID = -1;

    /**
     * @DatabaseType text
     * @DatabaseName xws
     */
    public $XWS;

    /**
     * @DatabaseType int(10)
     * @DatabaseName owner
     */
    public $Owner = -1;

    static function FromUpgradeJSON(array $json, int $OwnerID) : Stock
    {
        $stock = new Stock();
        $stock->Name = $json["name"];
        $stock->XWS = $json["xws"];
        $stock->Owner = $OwnerID;
        return $stock;
    }

    static function MergeStock(array &$a1, array $a2)
    {
        foreach ($a2 as $item) {
            if (!$item["unique"] && Stock::objArraySearch($a1, "xws", $item["xws"]) == null)
                $a1[] = $item;
        }

    }


    private static function objArraySearch($array, $index, $value)
    {
        foreach ($array as $arrayInf) {
            if ($arrayInf[$index] == $value) {
                return $arrayInf;
            }
        }
        return null;
    }



}


class Upgrade
{
    /**
     * Le nom
     * @var string
     */
    public $Name = "Vide";

    /**
     * Le XWS
     * @var string
     */
    public $XWS = "";

    /**
     * Le cout
     * @var array
     */
    public $Cost = array(
        "basecost" => 0,
        "costagi2" => 0,
        "costagi3" => 0,
        "costagi4" => 0,
        "costmed" => 0,
        "costlarge" => 0
    );

    function GetCost(Pilot $pilot)
    {
        $cost = $this->Cost["basecost"];
        $cost += $pilot->Size == 2 ? $this->Cost["costmed"] : 0;
        $cost += $pilot->Size == 3 ? $this->Cost["costlarge"] : 0;
        $cost += $pilot->Agility == 2 ? $this->Cost["costagi2"] : 0;
        $cost += $pilot->Agility == 3 ? $this->Cost["costagi3"] : 0;
        $cost += $pilot->Agility == 4 ? $this->Cost["costagi4"] : 0;
        return $cost;
    }
    /**
     * Le type
     * @var string
     */
    public $Type = "";

    /**
     * La capacitée
     * @var string
     */
    public $Desc = "Description";

    function ToXML(Pilot $parent)
    {
        $index = 0;
        foreach ($parent->Upgrades as $key => $element) {
            if ($element == $this) {
                break;
            }
            $index++;
        }

        return sprintf(
            "<upgrade name='%s' cost='%i' type='%s' desc='%s' index='%i' />",
            $this->Name,
            $this->GetCost($parent),
            $this->Type,
            $this->Desc,
            $index
        );
    }

    static function GetAllUpgrades()
    {
        $data = json_decode(file_get_contents("xwingupgrades.json"), true);

        return $data["upgrades"];
    }

    static function GetAllInstallableUpgrade(Pilot $pilot, mysqli $mysqli) : array
    {
        $stock = Upgrade::GetAllUpgrades();
        $array = array();
        foreach ($stock as $item) {
            $up = Upgrade::FromJSONArray($item);
            if (Unique::GetIfUniqueExist($up->Name, $mysqli)) continue;
            $slot = new Upgrade();
            foreach ($pilot->Upgrades as $slot) {
                if ($slot->Type == $up->Type) {
                    $array[] = $item;
                    break;
                }
            }
        }
        return $array;
    }

    static function GetMyInstallableStock(Pilot $pilot, User $user, mysqli $mysqli) : array
    {
        $stock = DatabaseReadAll('stock', $mysqli, "owner='{$user->DatabaseID}'");
        $upgrades = Upgrade::GetAllUpgrades();
        $array = array();
        $item = new Stock();
        foreach ($stock as $item) {
            $slot = new Upgrade();
            $upgradeItem = $upgrades[array_search($item->XWS, array_column($upgrades, "xws"))];
            foreach ($pilot->Upgrades as $slot) {
                if ($slot->Type == $upgradeItem["type"]) {
                    $array[] = Upgrade::FromJSONArray($upgradeItem);
                    break;
                }
            }
        }
        return $array;
    }

    static function FromJSONArray($data) : Upgrade
    {
        $upgrade = new Upgrade();
        $upgrade->Name = $data["name"];
        $upgrade->Type = $data["type"];
        $upgrade->XWS = $data["xws"];
        $upgrade->Cost = array(
            "basecost" => $data["cost"]["basecost"],
            "costagi2" => $data["cost"]["costagi2"],
            "costagi3" => $data["cost"]["costagi3"],
            "costagi4" => $data["cost"]["costagi4"],
            "costmed" => $data["cost"]["costmed"],
            "costlarge" => $data["cost"]["costlarge"]
        );
        return $upgrade;
    }


    static function CreateStartUpgrades(user $user, array $Pilots)
    {
        $stock = array();
        foreach ($Pilots as $p)
            Stock::MergeStock($stock, Upgrade::GetAllInstallableUpgrade($p, GetMySQLConnection()));


        usort($stock, function ($a, $b) {
            if ($a["type"] == $b["type"])
                return strcmp($a["name"], $b["name"]);
            return strcmp($a["type"], $b["type"]);
        });

        for ($i = 0; $i < 36; $i++) {
            $index = array_rand($stock);
            $item = Stock::FromUpgradeJSON($stock[$index], $user->DatabaseID);
            DatabaseWrite($item, GetMySQLConnection());
        }

    }


}

?>