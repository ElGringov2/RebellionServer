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

  /**
   * Undocumented variable
   * @DatabaseType int(2)
   * @DatabaseName otherennemies
   * @var integer
   */
  public $OtherEnnemiesCount = 0;


  /**
   * Undocumented variable
   * @DatabaseType int(2)
   * @DatabaseName freegroup
   * @var integer
   */
  public $FreeStartingGroup = 0;



  /**
   * Undocumented variable
   * @DatabaseType int(2)
   * @DatabaseName exit
   * @var integer
   */
  public $NeedExit = 0;


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


  function CreateEnnemies($mysqli)
  {
    $dbennemies = DatabaseReadAll("assaultennemy", $mysqli, "true", true);
    $array = array();
    foreach ($this->Ennemies as $ennemy) {
      $e = clone $dbennemies[$ennemy["dbid"]];
      if ($ennemy["count"] == 0)
        $e->Count = 0;
      else
        $e->Count = $e->TotalCount;
      $e->ActualHealth = $e->Health;
      $array[] = $e;
      if ($e->Elite == 1)
        unset($dbennemies[$ennemy["dbid"]]);

    }
    $dbennemies = array_values($dbennemies);

    for ($i = 0; $i < $this->OtherEnnemiesCount; $i++) {
      $index = rand(0, count($dbennemies) - 1);
      $e = clone $dbennemies[$index];
      if ($i < $this->FreeStartingGroup)
        $e->Count = $e->TotalCount;
      $e->ActualHealth = $e->Health;
      $array[] = $e;
      if ($e->Elite == 1)
        array_splice($dbennemies, $index, 1);

    }
    return $array;
  }
}




?>