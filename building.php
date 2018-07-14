<?php

class Building
{

    /**
     * @DatabaseType text
     * @DatabaseName name
     * @XmlAttribute name
     */
    public $Name = "";

    /**
     * @DatabaseType int(10)
     * @DatabaseName id
     * @DatabasePrimary
     * @XmlAttribute id
     */
    public $DatabaseID = -1;




    /**
     * @DatabaseType int(10)
     * @DatabaseName owner
     * @XmlAttribute owner
     */
    public $Owner = -1;



    /**
     * @DatabaseType int(10)
     * @DatabaseName ql
     * @XmlAttribute ql
     */
    public $QL = 1;

    /**
     * @XmlAttribute roundedQL
     */
    function RoundedQL() {
        return round($this->QL);
    }

    /**
     * @DatabaseType int(10)
     * @DatabaseName planetid
     * @XmlAttribute planet
     */
    public $PlanetID = -1;

    /**
     * @DatabaseType int(2)
     * @DatabaseName type
     * @XmlAttribute type
     */
    public $BuildingType = -1;


    function GetDescription()
    {
        if ($this->BuildingType == 1)
            return "Permet de construire des chasseurs. La qualité du chantier diminue le temps de construction.";
        if ($this->BuildingType == 2)
            return "Permet de recruter des personnes. La qualité de l'agence diminue le temps de recherche.";
        if ($this->BuildingType == 3)
            return "Permet de former des personnes. La qualité du centre diminue le temps de formation.";
        if ($this->BuildingType == 4)
            return "Permet de créer des objets manufacturé. La qualité de l'usine diminue le temps de construction des objets.";


        return "Batiment inconnu.";
    }

    function GetBaseTime()
    {
        if ($this->BuildingType == 1)
            return 48 * (11 - $this->QL);
        if ($this->BuildingType == 2)
            return 12 * (11 - $this->QL);
        if ($this->BuildingType == 3)
            return 24 * (11 - $this->QL);
        if ($this->BuildingType == 4)
            return 6 * (11 - $this->QL);


        return -1;
    }

    /**
     * @XmlAttribute img
     */
    function GetImage()
    {
        if ($this->BuildingType == 1)
            return "building_shipyard.png";
        if ($this->BuildingType == 2)
            return "building_agency.png";
        if ($this->BuildingType == 3)
            return "building_school.png";
        if ($this->BuildingType == 4)
            return "building_factory.png";
        if ($this->BuildingType == 5)
            return "building_market.png";

        return "sparkle.png";
    }

    /**
     * @XmlAttribute FullDescription
     */
    function GetFullDescription() {
        return $this->GetDescription() . "<br/>Temps de base: " . $this->GetBaseTime() . " cycles";
    }


    static function GetBaseName($BuildingType) {
        if ($BuildingType == 1) return "Chantier Naval";
        if ($BuildingType == 2) return "Agence de recrutement";
        if ($BuildingType == 3) return "Centre de formation";
        if ($BuildingType == 4) return "Usine";
        return "Un batiment";
    }
}


?>