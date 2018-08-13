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
   * @DatabaseType text
   * @DatabaseName currentorder
   */
  public $CurrentOrder;
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
  public $Move = 4;

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

  /**
   * @DatabaseIgnore
   */
  public $ActualHealth;

  /**
   * @DatabaseIgnore
   */
  public $MainWeapon;

  /**
   * @DatabaseIgnore
   */
  public $MainArmor;


  /**
   * @DatabaseType float
   * @DatabaseName tech
   */
  public $Tech = 0;

  /**
   * @DatabaseType float
   * @DatabaseName insight
   */
  public $Insight = 0;

  /**
   * @DatabaseType float
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

  function ToXML(mysqli $mysqli) : string
  {
    $gearsString = "";
    if ($this->MainArmor != null)
      $gearsString .= $this->MainArmor->Name." ";
    if ($this->MainWeapon != null)
      $gearsString .= $this->MainWeapon->Name;

    
    return sprintf("<commando name='%s' move='%s' health='%s' endurance='%s' experience='%s' gears='%s' />", $this->Name, $this->Move, $this->Health, $this->Endurance, $this->Experience, $gearsString);
  }

}

?>