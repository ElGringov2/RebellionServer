<?php

class Commander {

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
     * @DatabaseType text
     * @DatabaseName desc
     */
    public $Description;

    /**
     * @DatabaseType int(10)
     * @DatabaseName user
     */
    public $UserID = -1;

    static function GetCommanders(mysqli $mysqli) {
        $array = array(
            "Commandant 1" => "Description 1",
            "Commandant 2" => "Description 2",
            "Commandant 3" => "Description 3",
            "Commandant 4" => "Description 4",
            "Commandant 5" => "Description 5",
            "Commandant 6" => "Description 6",
            "Commandant 7" => "Description 7",
            "Commandant 8" => "Description 8",
            "Commandant 9" => "Description 9",
            "Commandant 10" => "Description 10",
            "Commandant 11" => "Description 11",
            "Commandant 12" => "Description 12",
            "Commandant 13" => "Description 13",
            "Commandant 14" => "Description 14",
            "Commandant 15" => "Description 15",
            "Commandant 16" => "Description 16",
        );
        
        Commander::shuffle_assoc($array);

        foreach ($array as $key => $value) {
            $commander = new Commander();
            $commander->Name = $key;
            $commander->Description = $value;
            DatabaseWrite($commander, $mysqli);
        }
    }

    private static function shuffle_assoc(&$array) {
        $keys = array_keys($array);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }

}



?>