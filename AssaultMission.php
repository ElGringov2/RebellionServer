<?php

class AssaultMission
{


  /**
   * @DatabaseType text
   * @DatabaseName name
   * @var string
   */
  public $Name;

  /**
   * @DatabaseType int(10)
   * @DatabaseName id
   * @DatabasePrimary
   */
  public $DatabaseID = -1;

  /**
   * @DatabaseName ennemies
   * @DatabaseSerialize
   * @var array
   */
  public $Ennemies;

  /**
   * @DatabaseType int(3)
   * @DatabaseName size
   */
  public $Size;

  /**
   * @DatabaseType int(2)
   * @DatabaseName turn
   */
  public $Turn;

  /**
   * @DatabaseType int(2)
   * @DatabaseName objective
   */
  public $ObjectivePoints;

  /**
   * @DatabaseType int(2)
   * @DatabaseName startingthreat
   */
  public $StartingThreat = 1;


  public static function GetRandomMission($seed) : AssaultMission
  {
    srand($seed);
    $mission = new AssaultMission();
    $mission->Name = "Mission $seed";
    $mission->Size = rand(15, 40);
    $mission->Turn = (int)($mission->Size / 3);
    $mission->ObjectivePoints = (int)($mission->Turn / 2);
    $mission->Ennemies = EnnemiForMission::GetRandomEnnemiesForMission((int)($mission->Size / 8), rand());


    return $mission;
  }

  public static function GetMission(int $i) : AssaultMission
  {
    $mission = new AssaultMission();
    $mission->Size = 23;
    $mission->Name = "Ingérence impériale";
    $mission->ObjectivePoints = 2;
    $mission->Turn = 99;
    $mission->StartingThreat = 3;

    $mission->Ennemies = array();

    
    
    return $mission;
  }


}




?>