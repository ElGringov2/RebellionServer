<?php


class Planet
{

    /**
     * @DatabaseType int(10)
     * @DatabaseName id
     * @DatabasePrimary
     */
    public $DatabaseID = -1;


    /**
     * @DatabaseType text
     * @DatabaseName name
     */
    public $Name = "";

    /**
     * @DatabaseType int(5)
     * @DatabaseName x
     */
    public $X = 0;

    /**
     * @DatabaseType int(5)
     * @DatabaseName y
     */
    public $Y = 0;


    /**
     * @DatabaseType text
     * @DatabaseName description
     */
    public $Description = "";

    /**
     * @DatabaseType text
     * @DatabaseName image
     */
    public $Image = "";


    function GetX($minX, $minY, $maxX, $maxY)
    {
        $max = $maxY - $minY;
        $pos = round(($this->Y - $minY) / $max * 100);
        return (int)$pos;
    }
    function GetY($minX, $minY, $maxX, $maxY)
    {
        $max = $maxX - $minX;
        $pos = 100 - round(($this->X - $minX) / $max * 100);
        return (int)$pos;
    }



    static function CreateGalaxy()
    {

        $galaxy = [
            (object)["name" => "Bespin", "X" => "-3243", "Y" => "-888"],
            (object)["name" => "Bothawui", "X" => "-1417", "Y" => "3992"],
            (object)["name" => "Corellia", "X" => "-287", "Y" => "925"],
            (object)["name" => "Coruscant", "X" => "666", "Y" => "-342"],
            (object)["name" => "Dagobah", "X" => "-3795", "Y" => "1027"],
            (object)["name" => "Dantooine", "X" => "3271", "Y" => "-296"],
            (object)["name" => "Dathomir", "X" => "2265", "Y" => "1945"],
            (object)["name" => "DQar", "X" => "-3443", "Y" => "2004"],
            (object)["name" => "Endor", "X" => "-2598", "Y" => "-2601"],
            (object)["name" => "Felucia", "X" => "2345", "Y" => "4431"],
            (object)["name" => "Geonosis", "X" => "-2823", "Y" => "4401"],
            (object)["name" => "Hosnian Prime", "X" => "-629", "Y" => "-117"],
            (object)["name" => "Hoth", "X" => "-3846", "Y" => "-887"],
            (object)["name" => "Ithor", "X" => "2635", "Y" => "-99"],
            (object)["name" => "Jakku", "X" => "-934", "Y" => "-2312"],
            (object)["name" => "Kashyyyk", "X" => "744", "Y" => "3050"],
            (object)["name" => "Kessel", "X" => "547", "Y" => "5541"],
            (object)["name" => "Kuat", "X" => "353", "Y" => "1277"],
            (object)["name" => "Malastare", "X" => "-2245", "Y" => "1364"],
            (object)["name" => "Mandalore", "X" => "1916", "Y" => "2250"],
            (object)["name" => "Naboo", "X" => "-2726", "Y" => "2071"],
            (object)["name" => "Nal Hutta", "X" => "-318", "Y" => "4730"],
            (object)["name" => "Nar Shadda", "X" => "-661", "Y" => "4846"],
            (object)["name" => "Onderon", "X" => "710", "Y" => "2287"],
            (object)["name" => "Ord Mantell", "X" => "2245", "Y" => "-630"],
            (object)["name" => "Rattatak", "X" => "-2058", "Y" => "-2809"],
            (object)["name" => "Scarif", "X" => "-2016", "Y" => "5141"],
            (object)["name" => "Sullust", "X" => "-2813", "Y" => "1168"],
            (object)["name" => "Tatooine", "X" => "-2293", "Y" => "4382"],
            (object)["name" => "Utapau", "X" => "-4343", "Y" => "1452"],
            (object)["name" => "Yavin", "X" => "2460", "Y" => "2887"]
        ];

        $mysqli = GetMySQLConnection();
        foreach ($galaxy as $planet) {
            $realPlanet = new Planet();
            $realPlanet->X = $planet->X;
            $realPlanet->Y = $planet->Y;
            $realPlanet->Name = $planet->name;

            DatabaseWrite($realPlanet, $mysqli);

        }

        return count($galaxy);

    }
}



?>