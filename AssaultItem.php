<?php
class AssaultItem
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
     * @DatabaseType int(2)
     * @DatabaseName agreen
     */
    public $AttackGreen = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName ablue
     */
    public $AttackBlue = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName ayellow
     */
    public $AttackYellow = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName ared
     */
    public $AttackRed = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName def
     */
    public $Defense = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName defsurge
     */
    public $DefenseSurge = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName defblack
     */
    public $DefenseDiceBlack = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName defwhite
     */
    public $DefenseDiceWhite = 0;
    /**
     * @DatabaseType int(5)
     * @DatabaseName cost
     */
    public $Cost = 100;
    /**
     * @DatabaseType int(2)
     * @DatabaseName surgedamage
     */
    public $SurgeToDamage = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName surgedoubledamage
     */
    public $SurgeToDoubleDamage = 0;    
    /**
     * @DatabaseType int(2)
     * @DatabaseName surgepierce
     */
    public $SurgeToPierce = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName surgedoublepierce
     */
    public $SurgeToDoublePierce = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName surgetriplepierce
     */
    public $SurgeToTriplePierce = 0;        
    /**
     * @DatabaseType int(2)
     * @DatabaseName damage
     */
    public $Damage = 0;
    /**
     * @DatabaseType int(2)
     * @DatabaseName pierce
     */
    public $Pierce = 0;

    /**
     * @DatabaseType int(2)
     * @DatabaseName health
     */
    public $Health = 0;

    static function create($name, $blue, $red, $yellow, $green)
    {
        $obj = new AssaultItem();
        $obj->AttackBlue = $blue;
        $obj->AttackGreen = $green;
        $obj->AttackRed = $red;
        $obj->AttackYellow = $yellow;
        $obj->Name = $name;

        return $obj;
    }

    static function createArmor($name, $defenseBlack, $defenseWhite)
    {
        $obj = new AssaultItem();
        $obj->Name = $name;
        $obj->DefenseWhite = $defenseWhite;
        $obj->DefendeBlack = $defenseBlack;

        return $obj;
    }

}
?>