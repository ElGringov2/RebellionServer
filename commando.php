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
   * @DatabaseName xp
   */
  public $Experience = 0;

  /**
   * @DatabaseType int(3)
   * @DatabaseName defwhite
   */
  public $DefenseWhite = 0;


  /**
   * @DatabaseType int(3)
   * @DatabaseName defblack
   */
  public $DefenseBlack = 0;



  /**
   * @DatabaseType int(3)
   * @DatabaseName move
   */
  public $Move;

  /**
   * @DatabaseType int(3)
   * @DatabaseName health
   */
  public $Health = 10;
  /**
   * @DatabaseType int(3)
   * @DatabaseName endurance
   */
  public $Endurance = 10;

  public $ActualHealth;
  public $MainWeapon;
  public $MainArmor;


  /**
   * @DatabaseType float(1, 19)
   * @DatabaseName tech
   */
  public $Tech = 0;

  /**
   * @DatabaseType float(1, 19)
   * @DatabaseName insight
   */
  public $Insight = 0;

  /**
   * @DatabaseType float(1, 19)
   * @DatabaseName strength
   */
  public $Strength = 0;

  /**
   * @DatabaseType int(10)
   * @DatabaseName onmission
   */
  public $OnMission = 0;


  /**
   * Pour les simulation de combat uniquement.
   * @DatabaseIgnore
   * @var integer
   */
  public $Count = 2;



  public function GetFullHealth()
  {
    if ($this->MainArmor == null)
      return $this->Health;
    return $this->Health + $this->MainArmor->Health;
  }

  public function GetAllBlackDefense()
  {
    if ($this->MainArmor != null)
      return $this->DefenseBlack + $this->MainArmor->DefenseDiceBlack;
    return $this->DefenseBlack;
  }



  public function GetAllWhiteDefense()
  {
    if ($this->MainArmor != null)
      return $this->DefenseWhite + $this->MainArmor->DefenseDiceWhite;
    return $this->DefenseWhite;
  }


  /**
   * @XmlAttribute portrait
   */
  function GetPortrait()
  {

    return "./portrait_" . strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $this->Name)) . ".png";

  }


  function GetLevel()
  {
    if ($this->Experience == 0) return 1;
    if ($this->Experience > 16) return 4;
    return $this->Experience / 4;
  }


  static function GetAllCommandos()
  {

    $array = array();

    $c = new Commando();
    $c->Name = "Diala Passil";
    $c->DefenseWhite = 1;
    $c->ActualHealth = 12;
    $c->Health = 12;
    $c->Move = 4;
    $weapon = new AssaultItem();
    $weapon->Name = "Baton en Plastacier";
    $weapon->AttackGreen = 1;
    $weapon->AttackYellow = 1;
    $weapon->SurgeToDamage = 1;
    $c->MainWeapon = $weapon;
    $array[] = $c;


    $c = new Commando();
    $c->Name = "Fenn Signis";
    $c->DefenseBlack = 1;
    $c->ActualHealth = 12;
    $c->Health = 12;
    $c->Move = 4;
    $weapon = new AssaultItem();
    $weapon->Name = "Blaster d'infanterie";
    $weapon->AttackGreen = 1;
    $weapon->AttackBlue = 1;
    $weapon->SurgeToDamage = 1;
    $c->MainWeapon = $weapon;
    $array[] = $c;


    $c = new Commando();
    $c->Name = "Gaarkhan";
    $c->DefenseBlack = 1;
    $c->ActualHealth = 14;
    $c->Health = 14;
    $c->Move = 3;
    $weapon = new AssaultItem();
    $weapon->Name = "Vibro-hache";
    $weapon->AttackRed = 1;
    $weapon->AttackYellow = 1;
    $weapon->SurgeToDamage = 1;
    $c->MainWeapon = $weapon;
    $array[] = $c;

    $c = new Commando();
    $c->Name = "Gideon Argus";
    $c->DefenseBlack = 1;
    $c->ActualHealth = 10;
    $c->Health = 10;
    $c->Move = 4;
    $weapon = new AssaultItem();
    $weapon->Name = "Blaster de poche";
    $weapon->AttackBlue = 1;
    $weapon->AttackYellow = 1;
    $weapon->SurgeToPierce = 2;
    $c->MainWeapon = $weapon;
    $array[] = $c;

    return $array;
  }


}

?>