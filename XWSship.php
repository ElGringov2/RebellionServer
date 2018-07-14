<?php

class XWSShip
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
     * @DatabaseType text
     * @DatabaseName size
     */
    public $Size;

    /**
     * @DatabaseType text
     * @DatabaseName faction
     */
    public $Faction = "";

    /**
     * @DatabaseType int(2)
     * @DatabaseName attack
     * @XmlAttribute attack
     */
    public $Attack;

    /**
     * @DatabaseType int(2)
     * @DatabaseName agility
     * @XmlAttribute agility
     */
    public $Agility;

    /**
     * @DatabaseType int(2)
     * @DatabaseName hull
     * @XmlAttribute hull
     */
    public $Hull;

    /**
     * @DatabaseType int(2)
     * @DatabaseName shield
     * @XmlAttribute shield
     */
    public $Shield;

    /**
     * @DatabaseType int(2)
     * @DatabaseName cost
     * @XmlAttribute cost
     */
    public $Cost = 0;



    static function GetShipLetter($shipXWS)
    {
        $letters = array(
            "aggressor" => "i",
            "alphaclassstarwing" => "&",
            "arc170" => "c",
            "attackshuttle" => "g",
            "auzituckgunship" => "@",
            "awing" => "a",
            "bwing" => "b",
            "bsf17bomber" => "Z",
            "cr90corvette" => "2",
            "croccruiser" => "5",
            "ewing" => "e",
            "firespray31" => "f",
            "g1astarfighter" => "n",
            "gozanticlasscruiser" => "4",
            "gr75mediumtransport" => "1",
            "hwk290" => "h",
            "ig2000" => "i",
            "jumpmaster5000" => "p",
            "kihraxzfighter" => "r",
            "kwing" => "k",
            "m12lkimogilafighter" => "K",
            "lambdaclassshuttle" => "l",
            "lancerclasspursuitcraft" => "L",
            "m3ainterceptor" => "s",
            "protectoratestarfighter" => "M",
            "quadjumper" => "q",
            "raiderclasscorvette" => "3",
            "scurrgh6bomber" => "H",
            "sheathipedeclassshuttle" => "%",
            "starviper" => "v",
            "t70xwing" => "w",
            "tieadvanced" => "A",
            "tieadvancedprototype" => "R",
            "tieadvprototype" => "R",
            "tieaggressor" => "`",
            "tiebomber" => "B",
            "tiedefender" => "D",
            "tiefighter" => "F",
            "tiefofighter" => "O",
            "tieinterceptor" => "I",
            "tiephantom" => "P",
            "tiepunisher" => "N",
            "tiesffighter" => "S",
            "tiesilencer" => "$",
            "tiestriker" => "T",
            "upsilonclassshuttle" => "U",
            "uwing" => "u",
            "vcx100" => "G",
            "vt49decimator" => "d",
            "xwing" => "x",
            "yt1300" => "m",
            "yt2400" => "o",
            "yt2400freighter" => "o",
            "yv666" => "t",
            "ywing" => "y",
            "z95headhunter" => "z"
        );
        return $letters[$shipXWS];
    }

}