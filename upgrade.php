<?php


class Upgrade
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
     * @DatabaseType text
     * @DatabaseName xws
     */
    public $XWS;

    /**
     * @DatabaseType int(3)
     * @DatabaseName cost
     */
    public $Cost;

    /**
     * @DatabaseType text
     * @DatabaseName type
     */
    public $Type;

        /**
     * @DatabaseType int(10)
     * @DatabaseName pilot
     */
    public $PilotID;
}
?>