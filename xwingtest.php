<?php
require("class.php");

















// $content = file_get_contents("http://xwing-miniatures-second-edition.wikia.com/wiki/Soontir_Fel");
// showContent($content);
// return;



$allShips = Pilot::GetAllShips();



$sources = array("http://xwing-miniatures-second-edition.wikia.com/wiki/Category:Imperial_Pilots", "http://xwing-miniatures-second-edition.wikia.com/wiki/Category:Rebel_Pilots", "http://xwing-miniatures-second-edition.wikia.com/wiki/Category:Scum_Pilots");
$factions = array("Galactic Empire", "Rebel Alliance", "Scum and Villainy");
$newArray = array();

for ($iSource = 0; $iSource < 3; $iSource++) {
    $contentSource = file_get_contents($sources[$iSource]);
    $findSource = strpos($contentSource, "Pages in category");
    while (true) {
        $findSource = strpos($contentSource, "<a href=\"", $findSource) + 9;
        if ($findSource == false) break;
        $endSource = strpos($contentSource, "\" title", $findSource);

        if (substr($contentSource, $findSource, 4) == "http") break;

        $content = file_get_contents("http://xwing-miniatures-second-edition.wikia.com" . substr($contentSource, $findSource, $endSource - $findSource));



        $find = strpos($content, "<font size=\"1\">Init</font>");
        $find = strpos($content, "<b>", $find) + 3;
        $endFind = strpos($content, "</b>", $find + 1);


        $name = substr($content, $find, $endFind - $find);

        $xws = strtolower(preg_replace('/[^a-z0-9]/i', '', $name));



        foreach ($allShips as $ship) {
            if ($ship["xws"] == $xws) {

                $find = strpos($content, "Pilot Ability");
                $find = strpos($content, "style=\"text-align: center;\">", $find);
                $find = strpos($content, "\">", $find) + 3;
                $endFind = strpos($content, "</td></tr>", $find + 1);

                if (substr($content, $find, 10) == "</td></tr>")
                    $ship["ability"] = "None";
                else
                    $ship["ability"] = strip_tags(str_replace("\n", "", substr($content, $find, $endFind - $find)));



                $find = strpos($content, "Ship Ability");
                $find = strpos($content, "style=\"text-align: center;\">", $find);
                $find = strpos($content, "\">", $find) + 3;
                $endFind = strpos($content, "</td></tr>", $find + 1);

                if (substr($content, $find, 10) == "</td></tr>")
                    $ship["shipability"] = "None";
                else
                    $ship["shipability"] = strip_tags(str_replace("\n", "", substr($content, $find, $endFind - $find)));


                $find = strpos($content, "<b>Force</b>");
                if ($find != false) {
                    $find = strpos($content, "colspan=\"3\">", $find);
                    $find = strpos($content, ">", $find) + 2;
                    $endFind = strpos($content, "<sup>", $find + 1);
                    $ship["force"] = intval(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
                } else
                    $ship["force"] = 0;

                if (strpos($content, "Initiative 1 Pilots"))
                    $ship["initiative"] = 1;
                if (strpos($content, "Initiative 2 Pilots"))
                    $ship["initiative"] = 2;
                if (strpos($content, "Initiative 3 Pilots"))
                    $ship["initiative"] = 3;
                if (strpos($content, "Initiative 4 Pilots"))
                    $ship["initiative"] = 4;
                if (strpos($content, "Initiative 5 Pilots"))
                    $ship["initiative"] = 5;
                if (strpos($content, "Initiative 6 Pilots"))
                    $ship["initiative"] = 6;


                $find = strpos($content, "Shd");
                $find = strpos($content, "<td style=\"color: red; text-align: center;\">", $find) + 44;
                $endFind = strpos($content, "</td>", $find);
                $count = strtolower(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
                $ship["attack"] = intval($count);
                if (intval($count) > 10) {
                    $split1 = intval(substr($count, 0, 1));
                    $split2 = intval(substr($count, 1, 1));
                    if ($split1 > $split2) $ship["attack"] = $split1;
                    else $ship["attack"] = $split2;
                }

                $find = strpos($content, "Shd");
                $find = strpos($content, "<td style=\"color: #41A317; text-align: center;\">", $find) + 47;
                $endFind = strpos($content, "</td>", $find);
                $count = strtolower(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
                $ship["agility"] = intval($count);

                $find = strpos($content, "Shd");
                $find = strpos($content, "<td style=\"color: #00FFFF; text-align: center;\">", $find) + 47;
                $endFind = strpos($content, "</td>", $find);
                $count = strtolower(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
                $ship["shields"] = intval($count);

                $find = strpos($content, "Shd");
                $find = strpos($content, "<td style=\"color: orange; text-align: center;\">", $find) + 46;
                $endFind = strpos($content, "</td>", $find);
                $count = strtolower(preg_replace('/[^0-9]/i', '', substr($content, $find, $endFind - $find)));
                $ship["hull"] = intval($count);


                if (strpos($content, "<span title=\"Small base\"") != false)
                    $ship["size"] = 1;

                if (strpos($content, "<span title=\"Medium base\"") != false)
                    $ship["size"] = 2;

                if (strpos($content, "<span title=\"Large base\"") != false)
                    $ship["size"] = 3;

                $newArray[] = $ship;
                break;
            }


        }


    }

}

usort($newArray, function ($a, $b) {
    return strcmp($a["faction"], $b["faction"]);
});

usort($newArray, function ($a, $b) {
    return strcmp($a["name"], $b["name"]);
});
echo "{\"pilots\": ";
echo json_encode($newArray);
echo "}";
function showContent($content)
{


    $find = strpos($content, "<font size=\"1\">Init</font>");
    $find = strpos($content, "<b>", $find) + 3;
    $endFind = strpos($content, "</b>", $find + 1);


    $name = substr($content, $find, $endFind - $find);


    echo "Name: $name: ";

    $find = strpos($content, "Pilot Ability");
    $find = strpos($content, "style=\"text-align: center;\">", $find);
    $find = strpos($content, "\">", $find) + 3;
    $endFind = strpos($content, "</td></tr>", $find + 1);

    echo "\"ability\": \"" . substr($content, $find, $endFind - $find) . "\",<br/>";

    $find = strpos($content, "<b>Force</b>");
    if ($find != false) {
        $find = strpos($content, "colspan=\"3\">", $find);
        $find = strpos($content, ">", $find) + 2;
        $endFind = strpos($content, "<sup>", $find + 1);
        echo "\"force\":  " . substr($content, $find, $endFind - $find) . ",<br/>";
    }

    if (strpos($content, "Initiative 1 Pilots"))
        echo "\"init\": 1,<br/>";
    if (strpos($content, "Initiative 2 Pilots"))
        echo "\"init\": 2,<br/>";
    if (strpos($content, "Initiative 3 Pilots"))
        echo "\"init\": 3,<br/>";
    if (strpos($content, "Initiative 4 Pilots"))
        echo "\"init\": 4,<br/>";
    if (strpos($content, "Initiative 5 Pilots"))
        echo "\"init\": 5,<br/>";
    if (strpos($content, "Initiative 6 Pilots"))
        echo "\"init\": 6,<br/>";



    $find = strpos($content, "</th><th style=\"color: #41A317; text-align: center;\"> Agl");
    $find = strpos($content, "<td style=\"color: red; text-align: center;\">", $find) + 44;
    $endFind = strpos($content, "</td>", $find);
    $count = strtolower(preg_replace('/[^a-z0-9]/i', '', substr($content, $find, $endFind - $find)));
    echo "\"attack\": " . intval($count) . ",<br/>";

}
?>