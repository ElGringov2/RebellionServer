<?php


/**
 * Gestion des nom unique
 */
class Unique
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
     * Verifie si le personnage est en jeu
     *
     * @param string $Name le nom du personnage
     * @param mysqli $mysqli une connexion mysqli
     * @return boolean vrai si existant, faux sinon
     */
    static function GetIfUniqueExist(string $Name, mysqli $mysqli) : bool
    {
        $unique = DatabaseRead('unique', $mysqli, "name='$name'");

        if ($unique == null)
            return false;

        return true;

    }
}



?>