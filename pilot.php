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
     * Le cout
     *
     * @var integer
     * @DatabaseType int(2)
     * @DatabaseName cost
     */
    public $Cost = 0;



    function GetTotalCost()
    {

        $upgradeCost = 0;
        foreach ($this->Upgrades as $upgrade)
            $upgradeCost += $upgrade->Cost;
        return $this->Cost;
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
     * La liste des upgrades
     *
     * @var array
     * @DatabaseName upgrades
     * @DatabaseSerialize
     */
    public $Upgrades = array();


    /**
     * @DatabaseType int(2)
     * @DatabaseName unique
     */
    public $Unique = 0;


    function ToXML()
    {

        $upgradeStr = "<upgrades>";
        foreach ($this->Upgrades as $upgrade) {
            $upgradeStr .= ToXML($upgrade);
        }
        $upgradeStr .= "</upgrades>";



        return sprintf(
            "<pilot name='%s' shipletter='%s' pilotskill='%s' shipattack='%s' shipagility='%s' shiphull='%s' shipshield='%s' pilotability=\"%s\" shipname='%s' id='%s' cost='%s' condition='%s' squadron=\"%s\" flight=\"%s\"  >%s</pilot>",
            $this->Name,
            Pilot::GetShipLetter($this->ShipName),
            $this->Initiative,
            $this->Attack,
            $this->Agility,
            $this->Hull,
            $this->Shields,
            $this->Ability,
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
    static function FromJSON(array $JSONData) : Pilot
    {
        $pilot = new Pilot();
        if (isset($JSONData["ability"]))
            $pilot->Ability = $JSONData["ability"];
        $pilot->ShipName = $JSONData["ship"];
        if (isset($JSONData["agility"]))
            $pilot->Agility = $JSONData["agility"];
        if (isset($JSONData["attack"]))
            $pilot->Attack = $JSONData["attack"];
        if (isset($JSONData["shields"]))
            $pilot->Shields = $JSONData["shields"];
        if (isset($JSONData["hull"]))
            $pilot->Hull = $JSONData["hull"];
        $pilot->Cost = $JSONData["cost"];
        $pilot->Name = $JSONData["name"];
        $pilot->Unique = $JSONData["unique"] == "false" ? 0 : 1;
        $pilot->Upgrades = array();
        foreach ($JSONData["upgrade"] as $key => $value) {

            for ($i = 0; $i < $value; $i++) {
                $upgrade = new Upgrade();
                $upgrade->Type = $key;
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
            "RZ-1 A-wing" => "a",
            "A/SF-01 B-wing" => "b",
            "bsf17bomber" => "Z",
            "cr90corvette" => "2",
            "croccruiser" => "5",
            "E-wing" => "e",
            "Firespray-class Patrol Craft" => "f",
            "G-1A Starfighter" => "n",
            "gozanticlasscruiser" => "4",
            "gr75mediumtransport" => "1",
            "HWK-290 Light Freighter" => "h",
            "ig2000" => "i",
            "JumpMaster 5000" => "p",
            "Kihraxz Fighter" => "r",
            "BTL-S8 K-wing" => "k",
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
            "UT-60D U-wing" => "u",
            "VCX-100 Light Freighter" => "G",
            "VT-49 Decimator" => "d",
            "T-65 X-wing" => "x",
            "Modified YT-1300 Light Freighter" => "m",
            "YT-2400 Light Freighter" => "o",
            "Customized YT-1300 Light Freighter" => "m",
            "Escape Craft" => "m",
            "YV-666 Light Freighter" => "t",
            "BTL-A4 Y-wing" => "y",
            "Z-95-AF4 Headhunter" => "z",
            "Fang Fighter" => "z"
        );
        return $letters[$ShipName];
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
     * @var integer
     */
    public $Cost = 0;

    /**
     * Le cout
     * @var string
     */
    public $Type = "";

}

?>