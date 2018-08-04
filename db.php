<?php
require_once("utils.php");


function GetMySQLConnection() : mysqli
{
    $mysqli = new mysqli("localhost", "root", "", "rebellion");
    if ($mysqli->connect_errno) {
        echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    return $mysqli;
}


function TableExists($table)
{
    $table = strtolower($table);
    $res = GetMySQLConnection()->Query("SHOW TABLES LIKE `rebellion_$table`");
    return mysql_num_rows($res) > 0;
}

function CreateDatabases()
{
    $mysqli = GetMySQLConnection();
    CreateTable('Pilot', $mysqli);
    CreateTable('unique', $mysqli);
    CreateTable('user', $mysqli);
    CreateTable('commander', $mysqli);
    CreateTable('planet', $mysqli);
    CreateTable('building', $mysqli);
    CreateTable('operationbase', $mysqli);
    CreateTable('mission', $mysqli);
    CreateTable('commando', $mysqli);
    CreateTable('legion', $mysqli);
    CreateTable('assaultennemy', $mysqli);
    CreateTable('assaultmission', $mysqli);
    CreateTable('assaultitem', $mysqli);
    CreateTable('planning', $mysqli);




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

        if (ReadDocAttribute($property, "DatabaseIgnore"))
            continue;

        $name = ReadDocAttribute($property, "DatabaseName");
        if ($name == "")
            $name = $property->getName();


        $type = ReadDocAttribute($property, "DatabaseType");
        if ($type == "")
            $type = "TEXT";

        if (ReadDocAttribute($property, "DatabasePrimary"))
            $primary = $name;

        $sql .= "`" . $name . "` " . $type . " NOT NULL " . ($primary == $name ? "AUTO_INCREMENT" : "") . ",";

    }
    $sql .= "PRIMARY KEY(`" . $primary . "`)";
    $sql .= ") ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";



    if (!$mysqli->query($sql))
        echo "Erreur: " . $sql . "<br/>" . $mysqli->error;
}


/**
 * Retourne des objets de la base de donnée, selon la clause Where.
 *
 * @param string $className Le nom de la classe d'objets à récuperer.
 * @param mysqli $mysqli Une connexion Mysqli
 * @param string $whereClause "true" par défaut. La clause Where.
 * @param boolean $orderArray Faux par défaut. Si vrai, l'array sera indexé par rapport au DatabaseID.
 * @return array Une liste d'objets.
 */
function DatabaseReadAll(string $className, mysqli $mysqli, string $whereClause = "true", $orderArray = false) : array
{
    if ($whereClause == "") $whereClause = "true";
    $sql = "SELECT * FROM `rebellion_" . strtolower($className) . "` WHERE $whereClause";
    $res = $mysqli->query($sql);
    $array = array();
    while ($row = $res->fetch_assoc()) {
        $object = DatabaseConvert($className, $row);
        if ($orderArray)
            $array[$object->DatabaseID] = $object;
        else
            $array[] = $object;
    }

    return $array;

}


function DatabaseRead($className, $mysqli, $whereClause = "true")
{
    $array = DatabaseReadAll($className, $mysqli, $whereClause);
    if (count($array) > 0)
        return reset($array);
    return null;
}

function DatabaseConvert($className, $row)
{
    $reflector = new ReflectionClass($className);
    $instance = $reflector->newInstance();
    $properties = $reflector->getProperties();


    foreach ($properties as $property) {
        if (ReadDocAttribute($property, "DatabaseIgnore"))
            continue;
        $dbName = ReadDocAttribute($property, "DatabaseName");

        if (array_key_exists($dbName, $row)) {
            if (ReadDocAttribute($property, "DatabaseSerialize"))
                $property->setValue($instance, unserialize($row[$dbName]));
            else {
                if (ReadDocAttribute($property, "DatabaseType") == "date")
                    $property->setValue($instance, date_create($row[$dbName]));
                else
                    $property->setValue($instance, $row[$dbName]);
            }
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

    $idProperty = null;

    $sqlNewLabels = "INSERT INTO `rebellion_" . strtolower(get_class($object)) . "` (";
    $sqlNewValues = "";
    $sqlUpdate = "UPDATE `rebellion_" . strtolower(get_class($object)) . "` SET ";
    foreach ($properties as $property) {
        if (ReadDocAttribute($property, "DatabaseIgnore"))
            continue;
        if (ReadDocAttribute($property, "DatabasePrimary")) {
            if ($property->getValue($object) == -1) {
                $new = true;
                $idProperty = $property;
            } else {
                $where = " WHERE " . ReadDocAttribute($property, "DatabaseName") . "='" . $property->getValue($object) . "'";
                $id = $property->getValue($object);
            }
        } else if (ReadDocAttribute($property, "DatabaseSerialize")) {
            $text = serialize($property->getValue($object));
            $sqlUpdate .= "`" . ReadDocAttribute($property, "DatabaseName") . "`='" . $mysqli->real_escape_string($text) . "', ";
            $sqlNewValues .= "'" . $mysqli->real_escape_string($text) . "', ";
            $sqlNewLabels .= "`" . ReadDocAttribute($property, "DatabaseName") . "`, ";
        } else if (ReadDocAttribute($property, "DatabaseType") == "date") {
            $sqlUpdate .= "`" . ReadDocAttribute($property, "DatabaseName") . "`='" . $property->getValue($object)->format("Y-m-d H:i:s") . "', ";
            $sqlNewValues .= "'" . $property->getValue($object)->format("Y-m-d H:i:s") . "', ";
            $sqlNewLabels .= "`" . ReadDocAttribute($property, "DatabaseName") . "`, ";
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

    if ($id == -1) {
        $id = $mysqli->insert_id;
        $idProperty->setValue($object, $id);
    }
    return $id;
}

?>