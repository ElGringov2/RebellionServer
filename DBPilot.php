<?php

class PilotUpgrade
{
    public $cannon = 0; //int
    public $modification = 0; //int
    public $sensor = 0; //int
    public $talent = 0; //int
    public $torpedo = 0; //int
    public $crew = 0; //int?
    public $gunner = 0; //int?
    public $force = 0; //int?
    public $turret = 0; //int?
    public $device = 0; //int?
    public $missile = 0; //int?
    public $illicit = 0; //int?
}

class DBPilot
{
    public $name; //String
    public $ship; //String
    public $cost; //int
    public $xws; //String
    public $unique = false; //boolean
    public $upgrade; //PilotUpgrade


    static function GetAllPilots()
    {
        $content = file_get_contents("xwingpilots.json");
        $data = json_decode($content, true);
        $array = array();

        foreach ($data["pilots"] as $item) {
            $upgrades = JSONToObject("PilotUpgrade", $item["upgrade"]);
            $pilot = JSONToObject("DBPilot", $item);
            $pilot->upgrade = $upgrades;
            $array[] = $pilot;
        }





        return $array;
    }
}

class DBUpgradeCost
{
    public $basecost; //int
    public $costagi2; //int
    public $costagi3; //int
    public $costagi4; //int
    public $costmed; //int
    public $costlarge; //int
}

class DBUpgrade
{
    public $faction; //String
    public $unique; //boolean
    public $slots; //int
    public $type; //String
    public $xws; //String
    public $name; //String
    public $cost; //Cost

}



?>
