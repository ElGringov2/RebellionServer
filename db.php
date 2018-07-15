<?php
require_once("utils.php");


function GetMySQLConnection()
{
    $mysqli = new mysqli("localhost", "root", "", "rebellion");
    if ($mysqli->connect_errno) {
        echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    return $mysqli;
}


function CreateDatabases()
{
    $mysqli = GetMySQLConnection();
    CreateTable('Pilot', $mysqli);
    CreateTable('Upgrade', $mysqli);
    CreateTable('user', $mysqli);
    CreateTable('planet', $mysqli);
    CreateTable('building', $mysqli);
    CreateTable('xwsship', $mysqli);
    CreateTable('upgrade', $mysqli);
    CreateTable('operationbase', $mysqli);
    CreateTable('mission', $mysqli);
    CreateTable('commando', $mysqli);




}



function CreateTable($object, $mysqli)
{
    $object = strtolower($object);
    if (!$mysqli->query("DROP TABLE IF EXISTS `rebellion_" . $object . "`;"))
        echo $mysqli->error;
    $sql = "CREATE TABLE `rebellion_" . $object . "` (";
    $primary = "";
    $reflector = new ReflectionClass($object);
    $properties = $reflector->getProperties();

    foreach ($properties as $property) {
        $name = ReadDocAttribute($property, "DatabaseName");
        if ($name == "")
            $name = $property->getName();

        // if (preg_match('/@DatabaseName\s+([^\s]+)/', $property->getDocComment(), $matches)) {
        //     list(, $name) = $matches;
        // }
        $type = ReadDocAttribute($property, "DatabaseType");
        if ($type == "")
            $type = "TEXT";
        // if (preg_match('/@DatabaseType\s+([^\s]+)/', $property->getDocComment(), $matches)) {
        //     list(, $type) = $matches;
        // }
        if (ReadDocAttribute($property, "DatabasePrimary"))
        // if (preg_match('/@DatabasePrimary\s+([^\s]+)/', $property->getDocComment(), $matches)) {
        $primary = $name;
        // }

        $sql .= "`" . $name . "` " . $type . " NOT NULL " . ($primary == $name ? "AUTO_INCREMENT" : "") . ",";

    }
    $sql .= "PRIMARY KEY(`" . $primary . "`)";
    $sql .= ") ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;";



    if (!$mysqli->query($sql))
        echo "Erreur: " . $sql . "<br/>" . $mysqli->error;
}




function DatabaseReadAll($className, $mysqli, $whereClause = "true", $debug = false)
{
    $sql = "SELECT * FROM `rebellion_" . strtolower($className) . "` WHERE $whereClause";
    if ($debug) echo $sql;
    $res = $mysqli->query($sql);
    $array = array();
    while ($row = $res->fetch_assoc()) {
        $array[] = DatabaseConvert($className, $row);
    }

    return $array;

}


function DatabaseRead($className, $mysqli, $whereClause = "true")
{
    $array = DatabaseReadAll($className, $mysqli, $whereClause);
    if (count($array) > 0)
        return $array[0];
    return null;
}

function DatabaseConvert($className, $row)
{
    $reflector = new ReflectionClass($className);
    $instance = $reflector->newInstance();
    $properties = $reflector->getProperties();


    foreach ($properties as $property) {
        $dbName = ReadDocAttribute($property, "DatabaseName");

        if (array_key_exists($dbName, $row)) {
            $property->setValue($instance, $row[$dbName]);
        }
    }


    return $instance;

}

function DatabaseTruncate($classname, $mysqli)
{
    $mysqli->query("TRUNCATE TABLE `rebellion_$classname`");
}

function DatabaseWrite($object, $mysqli)
{

    $reflector = new ReflectionClass(get_class($object));
    $properties = $reflector->getProperties();
    $new = false;
    $where = "";
    $id = -1;

    $sqlNewLabels = "INSERT INTO `rebellion_" . strtolower(get_class($object)) . "` (";
    $sqlNewValues = "";
    $sqlUpdate = "UPDATE `rebellion_" . strtolower(get_class($object)) . "` SET ";
    foreach ($properties as $property) {
        if (ReadDocAttribute($property, "DatabasePrimary")) {
            if ($property->getValue($object) == -1) {
                $new = true;
            } else {
                $where = " WHERE " . ReadDocAttribute($property, "DatabaseName") . "='" . $property->getValue($object) . "'";
                $id = $property->getValue($object);
            }
        } else {
            $sqlUpdate .= "`" . ReadDocAttribute($property, "DatabaseName") . "`='" . $mysqli->real_escape_string($property->getValue($object)) . "', ";
            $sqlNewLabels .= "`" . ReadDocAttribute($property, "DatabaseName") . "`, ";
            $sqlNewValues .= "'" . $mysqli->real_escape_string($property->getValue($object)) . "', ";
        }
    }

    $sqlNewLabels = rtrim($sqlNewLabels, ", ");
    $sqlNewValues = rtrim($sqlNewValues, ", ");
    $sqlUpdate = rtrim($sqlUpdate, ", ") . $where;
    $sql = $sqlUpdate . ";";

    if ($new)
        $sql = $sqlNewLabels . ") VALUES (" . $sqlNewValues . ");";

    if (!$mysqli->query($sql))
        echo "Erreur: " . $sql . "<br/>" . $mysqli->error;

    if ($id == -1)
        $id = $mysqli->insert_id;
    return $id;
}

?>