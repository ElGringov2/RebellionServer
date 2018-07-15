<?php

class Commando
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
     * @DatabaseType int(10)
     * @DatabaseName location
     */
    public $Location = -1;

        /**
     * @DatabaseType int(10)
     * @DatabaseName owner
     */
    public $OwnerID = -1;

    
        /**
     * @DatabaseType int(3)
     * @DatabaseName $xp
     */
    public $Experience = 0;


    /**
     * @XmlAttribute portrait
     */
    function GetPortrait() {
        return "./portrait_".strtolower(preg_replace("/[^a-zA-Z0-9]/", "",  $this->Name)).".png";
    }





}