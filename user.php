<?php


class User
{

    /**
     * @DatabaseType int(10)
     * @DatabaseName id
     * @DatabasePrimary
     */
    public $DatabaseID = -1;


    /**
     * @DatabaseType text
     * @DatabaseName username
     */
    public $Name = "";

    /**
     * @DatabaseType text
     * @DatabaseName password
     */
    public $Pass = "";


    /**
     * @DatabaseType text
     * @DatabaseName commander
     */
    public $CommanderName = "";

    /**
     * @DatabaseType int(10)
     * @DatabaseName creds
     */
    public $Credits = 0;


    /**
     * @DatabaseType text
     * @DatabaseName mail
     */
    public $Mail = "";

    /**
     * @DatabaseType text
     * @DatabaseName connectionguid
     */
    public $ConnectionGUID = "";



    /**
     * @DatabaseType int(3)
     * @DatabaseName influence
     */
    public $Influence = 0;


    /**
     * @DatabaseType int(4)
     * @DatabaseName vp
     */
    public $VictoryPoints = 0;


    /**
     * @DatabaseType text
     * @DatabaseName firstsquadronname
     */
    public $FirstSquadronName = "";


    public static function GetColor(int $id) {
        return array(1 => "red", 2=> "blue", 3 => "brown", 4 => "yellow", 5 => "green", 6 => "purple", 7 => "turquoise", 8 => "black")[$id];
    }
}


?>