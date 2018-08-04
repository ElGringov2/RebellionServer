<?php

class config
{

    static function GetValue(string $name)
    {
        $mysqli = new mysqli("localhost", "root", "", "rebellion");
        if ($mysqli->connect_errno) {
            echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        $sql = "SELECT $name FROM rebellion_config";
        $res = $mysqli->query($sql);
        return $res->fetch_assoc()[$name];
    }

    static function SetValue(string $name, $value)
    {
        $mysqli = new mysqli("localhost", "root", "", "rebellion");
        if ($mysqli->connect_errno) {
            echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        $sql = "UPDATE `rebellion_config` SET `$name`='$value'";
        $res = $mysqli->query($sql);
    }

    static function Init()
    {
        $mysqli = new mysqli("localhost", "root", "", "rebellion");
        if ($mysqli->connect_errno) {
            echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        $sql = "DROP TABLE IF EXISTS `rebellion_config`;";
        $mysqli->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `rebellion_config` (
            `draftmode` tinyint(1) NOT NULL
          ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
        $mysqli->query($sql);

        $sql = "INSERT INTO `rebellion_config` (`draftmode`) VALUES ('0');";
        $mysqli->query($sql);

    }
}