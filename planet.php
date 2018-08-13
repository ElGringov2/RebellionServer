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


    public static function GetDistance(Planet $a, Planet $b) : float {
        return 30;
    }

    static function CreateGalaxy()
    {

        $galaxy = [
            (object)["name" => "Bespin", "X" => "-3243", "Y" => "-888", "Image" => "planet_bespin.png"],
            (object)["name" => "Bothawui", "X" => "-1417", "Y" => "3992", "Image" => "planet_bothawui.png"],
            (object)["name" => "Corellia", "X" => "-287", "Y" => "925", "Image" => "planet_correlia.png"],
            (object)["name" => "Coruscant", "X" => "666", "Y" => "-342", "Image" => "planet_coruscant.png"],
            (object)["name" => "Dagobah", "X" => "-3795", "Y" => "1027", "Image" => "planet_dagobah.png"],
            (object)["name" => "Dantooine", "X" => "3271", "Y" => "-296", "Image" => "planet_dantooine.png"],
            (object)["name" => "Dathomir", "X" => "2265", "Y" => "1945", "Image" => "planet_dathomir.png"],
            (object)["name" => "DQar", "X" => "-3443", "Y" => "2004", "Image" => "planet_dqar.png"],
            (object)["name" => "Endor", "X" => "-2598", "Y" => "-2601", "Image" => "planet_endor.png"],
            (object)["name" => "Felucia", "X" => "2345", "Y" => "4431", "Image" => "planet_felucia.png"],
            (object)["name" => "Geonosis", "X" => "-2823", "Y" => "4401", "Image" => "planet_geonosis.png"],
            (object)["name" => "Hosnian Prime", "X" => "-629", "Y" => "-117", "Image" => "planet_hosnian.png"],
            (object)["name" => "Hoth", "X" => "-3846", "Y" => "-887", "Image" => "planet_hoth.jpg"],
            (object)["name" => "Ithor", "X" => "2635", "Y" => "-99", "Image" => "planet_ithor.jpg"],
            (object)["name" => "Jakku", "X" => "-934", "Y" => "-2312", "Image" => "planet_jakku.jpg"],
            (object)["name" => "Kashyyyk", "X" => "744", "Y" => "3050", "Image" => "planet_kashyyyk.png"],
            (object)["name" => "Kessel", "X" => "547", "Y" => "5541", "Image" => "planet_kessel.png"],
            (object)["name" => "Kuat", "X" => "353", "Y" => "1277", "Image" => "planet_kuat.jpg"],
            (object)["name" => "Malastare", "X" => "-2245", "Y" => "1364", "Image" => "planet_malastare.jpg"],
            (object)["name" => "Mandalore", "X" => "1916", "Y" => "2250", "Image" => "planet_mandalore.png"],
            (object)["name" => "Naboo", "X" => "-2726", "Y" => "2071", "Image" => "planet_naboo.png"],
            (object)["name" => "Nal Hutta", "X" => "-318", "Y" => "4730", "Image" => "planet_nalhutta.png"],
            (object)["name" => "Nar Shadda", "X" => "-661", "Y" => "4846", "Image" => "planet_narshadda.png"],
            (object)["name" => "Onderon", "X" => "710", "Y" => "2287", "Image" => "planet_onderon.jpg"],
            (object)["name" => "Ord Mantell", "X" => "2245", "Y" => "-630", "Image" => "planet_ordmantell.png"],
            (object)["name" => "Rattatak", "X" => "-2058", "Y" => "-2809", "Image" => "planet_rattatak.jpg"],
            (object)["name" => "Scarif", "X" => "-2016", "Y" => "5141", "Image" => "planet_scarif.png"],
            (object)["name" => "Sullust", "X" => "-2813", "Y" => "1168", "Image" => "planet_sullust.png"],
            (object)["name" => "Tatooine", "X" => "-2293", "Y" => "4382", "Image" => "planet_tatooine.png"],
            (object)["name" => "Utapau", "X" => "-4343", "Y" => "1452", "Image" => "planet_utapau.png"],
            (object)["name" => "Yavin", "X" => "2460", "Y" => "2887", "Image" => "planet_yavin.png"]
        ];

        $mysqli = GetMySQLConnection();
        foreach ($galaxy as $planet) {
            $realPlanet = new Planet();
            $realPlanet->X = $planet->X;
            $realPlanet->Y = $planet->Y;
            $realPlanet->Name = $planet->name;
            $realPlanet->Image = $planet->Image;

            DatabaseWrite($realPlanet, $mysqli);

        }

        return count($galaxy);

    }
}



?>