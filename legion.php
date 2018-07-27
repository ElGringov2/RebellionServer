<?php

class Legion
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
    public $Location;


    /**
     * @DatabaseType int(10)
     * @DatabaseName owner
     */
    public $Owner;


    

}