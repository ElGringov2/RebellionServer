<?php
class AssaultEnnemy
{


  /**
   * @DatabaseType int(10)
   * @DatabaseName id
   * @DatabasePrimary
   */
  public $DatabaseID = -1;

  /**
   * @DatabaseName name
   * @DaatabaseType text
   */
  public $Name;


  public $Count;

  /**
   * @DatabaseName count
   * @DaatabaseType int(2)
   */
  public $TotalCount;

  /**
   * @DatabaseName reinforce
   * @DaatabaseType int(2)
   */

  public $ReinforceCost;

  /**
   * @DatabaseName cost
   * @DaatabaseType int(2)
   */
  public $TotalCost;

  /**
   * @DatabaseName health
   * @DaatabaseType int(2)
   */
  public $Health;

  public $ActualHealth;

  public $MainWeapon = null;

    /**
   * @DatabaseName defenceblack
   * @DaatabaseType int(2)
   */
public $DefenseBlack = 0;

   /**
   * @DatabaseName defensewhite
   * @DaatabaseType int(2)
   */
  public $DefenseWhite = 0;




  public function GetAllBlackDefense()
  {
    return $this->DefenseBlack;
  }



  public function GetAllWhiteDefense()
  {
    return $this->DefenseWhite;
  }


  static function GetEnnemies()
  {

    $array = array();

    $e = new AssaultEnnemy();
    $e->Name = "Stormtrooper";
    $e->Health = 3;
    $e->Count = 3;
    $e->TotalCount = 3;
    $e->ReinforceCost = 2;
    $e->TotalCost = 6;
    $e->ActualHealth = 3;
    $e->DefenseBlack = 1;
    $e->MainWeapon = AssaultItem::create("arme", 1, 0, 0, 1);
    $e->MainWeapon->SurgeToDamage = 1;
    $array[] = $e;


    $e = new AssaultEnnemy();
    $e->Name = "Stormtrooper (Elite)";
    $e->Health = 5;
    $e->Count = 3;
    $e->TotalCount = 3;
    $e->ReinforceCost = 3;
    $e->TotalCost = 9;
    $e->ActualHealth = 5;
    $e->DefenseBlack = 1;
    $e->MainWeapon = AssaultItem::create("arme", 1, 0, 0, 1);
    $e->MainWeapon->SurgeToDamage = 2;
    $array[] = $e;


    return $array;


  }




}


class EnnemiForMission
{
  public $EnnemiID;
  public $EnnemiCount;

  function __constructor($id, $count)
  {
    $this->$EnnemiID = $id;
    $this->$EnnemiCount = $count;
  }



  static function GetRandomMission($size, $seed)
  {
    srand($seed);

    $all = AssaultEnnemy::GetEnnemies();

    $array = array();
    $alive = rand(1, $size);
    $stock = rand(1, $size);
    for ($i = 0; $i < $alive; $i++) {
      $array[] = $all[rand(0, count($all) - 1)];
    }
    for ($i = 0; $i < $stock; $i++) {
      $ennemy = $all[rand(0, count($all) - 1)];
      $ennemy->TotalCount = 0;
      $array[] = $ennemy;
    }

    return $array;

  }
}
?>