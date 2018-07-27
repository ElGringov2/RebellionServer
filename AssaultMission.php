<?php

class AssaultMission {


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



  public static function GetRandomMission($seed)
  {
    srand($seed);
    $mission = new AssaultMission();
    $mission->Name = "Mission $seed";
    $mission->Size = rand(8, 25);
    $mission->Turn = (int)($mission->Size / 2);
    $mission->ObjectivePoints = rand(1, 4);
    $mission->Ennemies = EnnemiForMission::GetRandomMission((int)($mission->Size / 8), rand());


    return $mission;
  }


}




?>