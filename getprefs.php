<?php
require_once("class.php");


$mysqli = GetMySQLConnection();

$user = DatabaseRead('user', $mysqli, "connectionguid='" . $_POST["guid"] . "'");

if (isset($_POST["dispo"])) {
    $date = new DateTime(date("Y-m-d"));
    $date = $date->modify("+".$_POST["jour"]." day");
    $day = Planning::GetDay($mysqli, $user, $date);
    $day->Preference = intval($_POST["dispo"]);
    DatabaseWrite($day, $mysqli);
} else {
    $str = "<userprefs>";
    $str .= "<dispos>";


    $begin = new DateTime(date("Y-m-d"));
    $end = new DateTime(date("Y-m-d"));
    $end = $end->modify('+14 day');

    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($begin, $interval, $end);

    $dispos = array();
    foreach ($period as $dt)
        $str .= Planning::GetDay($mysqli, $user, $dt)->ToXML();

    $str .= "</dispos>";






    $str .= "</userprefs>";
    echo $str;
}

?>