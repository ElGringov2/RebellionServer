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
    public $ActualShip = "xwing";

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
     * @DatabaseType int(2)
     * @DatabaseName xp
     */
    public $Experience = 4;

    /**
     * @DatabaseType text
     * @DatabaseName pilotability
     */
    public $Ability = "Aucune";



    function GetTotalCost()
    {
        $ship = DatabaseRead('xwsship', GetMySQLConnection(), "xws='{$this->DatabaseID}'");
        return $this->Skill() + $ship->Cost;
    }


    function GetAttack()
    {
        $ship = DatabaseRead('xwsship', GetMySQLConnection(), "xws='{$this->DatabaseID}'");
        return $ship->Attack;
    }

    function GetAgility()
    {
        $ship = DatabaseRead('xwsship', GetMySQLConnection(), "xws='{$this->DatabaseID}'");
        return $ship->Agility;
    }


    function GetHull()
    {
        $ship = DatabaseRead('xwsship', GetMySQLConnection(), "xws='{$this->DatabaseID}'");
        return $ship->Hull;
    }


    function GetShield()
    {
        $ship = DatabaseRead('xwsship', GetMySQLConnection(), "xws='{$this->DatabaseID}'");
        return $ship->Shield;
    }


    function Skill()
    {
        return floor(sqrt($this->Experience));
    }


    function GetShipName()
    {
        $ship = DatabaseRead('xwsship', GetMySQLConnection(), "xws='{$this->DatabaseID}'");
        return $ship->Name;

    }



    function ToXML($mysqli) {
        $upgrades = DatabaseReadAll("upgrade", $mysqli, "pilot={$this->DatabaseID}");
        $upgradeStr = "<upgrades>";
        foreach($upgrades as $upgrade) {
            $upgradeStr .= ToXML($upgrade);
        }
        $upgradeStr .= "</upgrades>";
        $ship = DatabaseRead('xwsship', GetMySQLConnection(), "xws='{$this->ActualShip}'");



        return sprintf("<pilot name='%s' shipletter='%s' pilotskill='%s' shipattack='%s' shipagility='%s' shiphull='%s' shipshield='%s' pilotability=\"%s\" shipname='%s' id='%s' cost='%s' condition='%s' squadron=\"%s\" flight=\"%s\"  >%s</pilot>",
            $this->Name,
            XWSShip::GetShipLetter($this->ActualShip),
            $this->Skill(),
            $ship->Attack,
            $ship->Agility,
            $ship->Hull,
            $ship->Shield,
            $this->Ability,
            $ship->Name,
            $this->DatabaseID,
            $this->Skill() + $ship->Cost,
            $this->Condition,
            $this->Squadron,
            $this->Flight,
            $upgradeStr
         );
    }




}

?>