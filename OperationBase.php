<?php
class OperationBase
{

    /**
     * @DatabaseType int(10)
     * @DatabaseName id
     * @DatabasePrimary
     */
    public $DatabaseID = -1;


    /**
     * @DatabaseType int(10)
     * @DatabaseName owner
     */
    public $OwnerID = 0;

    /**
     * @DatabaseType int(10)
     * @DatabaseName planetid
     */
    public $PlanetID = 0;

    /**
     * @DatabaseType int(4)
     * @DatabaseName hangarsize
     */
    public $HangarSize = 0;

    /**
     * @DatabaseType int(4)
     * @DatabaseName hangarmechs
     */
    public $HangarMechanics = 0;

    /**
     * @DatabaseType int(4)
     * @DatabaseName mediccapacity
     */
    public $MedbayCapacity = 0;


    /**
     * @DatabaseType int(4)
     * @DatabaseName medicbacta
     */
    public $MedbayBactaTanks = 0;


    /**
     * @DatabaseType int(4)
     * @DatabaseName controlrange
     */
    public $TowerControlRange = 0;

    /**
     * @DatabaseType int(4)
     * @DatabaseName controlquality
     */
    public $TowerControlQuality = 0;


    /**
     * @DatabaseType int(4)
     * @DatabaseName capacity
     */
    public $TotalCapacity = 0;






}