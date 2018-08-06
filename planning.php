<?php


class Planning
{

    /**
     * @DatabaseType int(10)
     * @DatabaseName id
     * @DatabasePrimary
     */
    public $DatabaseID = -1;

    /**
     * @DatabaseType date
     * @DatabaseName day
     * @var DateTime
     */
    public $Day;

    /**
     * @DatabaseType int(3)
     * @DatabaseName pref
     */
    public $Preference;

    /**
     * Le nom de l'utilisateur
     * @DatabaseType text
     * @DatabaseName username
     *
     * @var string
     */
    public $UserName = "";

    /**
     * l'ID de l'utilisateur
     * @DatabaseType int(10)
     * @DatabaseName userid
     *
     * @var int
     */
    public $UserID = -1;


    function ToXML() : string {
        return "<dispo day='" . date_format($this->Day, "d/m") . "' pref='{$this->Preference}' user='{$this->UserName}' />";
    }


    /**
     * Récupère les disponibilitées d'une journée spécifique
     *
     * @param mysqli $mysqli Une connexion Mysqli  
     * @param DateTime $date Le jour spécifié (l'heure n'est pas importante)
     * @return array Les infos.
     */
    static function GetDays(mysqli $mysqli, DateTime $date) : array
    {

        $array = array();

        $data = DatabaseReadAll('planning', $mysqli, "day='" . date_format($date, "Y-m-d H:i:s") . "' order by pref desc");


        foreach ($data as $item) {
            $array[] = $item;
        }



        return $array;
    }



    static function GetDay(mysqli $mysqli, User $user, DateTime $date) : Planning
    {
        $data = DatabaseRead('planning', $mysqli, "day='" . date_format($date, "Y-m-d H:i:s") . "' AND userid='{$user->DatabaseID}'");
        if ($data == null)
        {
            $data = new Planning();
            $data->Day = $date;
            $data->Preference = 0;
            $data->UserID = $user->DatabaseID;
            $data->UserName = $user->CommanderName;
            $data->DatabaseID = -1;
        }
        return $data;
    }


}
