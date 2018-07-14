<?php

class Mission
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
     * @DatabaseType text
     * @DatabaseName desc   
     */
    public $Description;


    /**
     * @DatabaseType text
     * @DatabaseName reward   
     */
    public $Reward = "";

    /**
     * @DatabaseType int(2)
     * @DatabaseName type
     */
    public $MissionType = 0;

    /**
     * @DatabaseType int(2)
     * @DatabaseName col
     */
    public $Row = 0;

    /**
     * @DatabaseType int(2)
     * @DatabaseName row
     */
    public $Col = 0;


    /**
     * @DatabaseType int(10)
     * @DatabaseName planet
     */
    public $PlanetID = 0;



    /**
     * @XmlAttribute gamelogo
     */
    function GameLogo()
    {
        if ($this->MissionType == 0)
            return "./logoxwing.png";
        if ($this->MissionType == 1)
            return "./swlegion.png";
        if ($this->MissionType == 2)
            return "./assautempire.png";
        return "";
    }



    /**
     * 0 = Choix possible, 1 = choix annulé (pris une autre sur la même ligne)
     * 2 = En attente de résolution, 3 = réussie, 4 = echouée, 
     * -1 = impossible (pas encore arrivé sur la bonne ligne) )
     * @DatabaseType int(2)
     * @DatabaseName state
     * */
    public $State = -1;



    public static function GetAllMission($missionType = -1)
    {
        $array = array();
        $mission = new Mission();
        $mission->Name = "Percée";
        $mission->MissionType = 1;
        $mission->Description = "Traverser une zone défendue pour atteindre l'objectif suivant";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Interception des transmissions";
        $mission->MissionType = 1;
        $mission->Description = "Capturer et tenir les appareils de communication ennemis";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Récupération de ravitaillement";
        $mission->MissionType = 1;
        $mission->Description = "Mise en place d'antenne de surveillance temporaire";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Positions-clés";
        $mission->MissionType = 1;
        $mission->Description = "Capture de ravitaillement pour la cause";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Extraction";
        $mission->MissionType = 2;
        $mission->Description = "Un sénateur à besoin d'informations, et est prêt a payer.";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Une affaire en suspens";
        $mission->MissionType = 2;
        $mission->Description = "Liberer des esclaves Wookie d'un camp impérial";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "L'épreuve du métal";
        $mission->MissionType = 2;
        $mission->Description = "Saboter une usine de droides de combat";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Troubles civils";
        $mission->MissionType = 2;
        $mission->Description = "Sauver ces citoyens d'un CD-TT";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Intrusion";
        $mission->MissionType = 2;
        $mission->Description = "Ahsoka Tano à besoin d'aide pour capturer un Impérial";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Ca va secouer";
        $mission->MissionType = 2;
        $mission->Description = "Assister un groupe de rebelle dans une recherche de donnée";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Le prédateur et la proie";
        $mission->MissionType = 2;
        $mission->Description = "Securiser une cargaison";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Agents de récupération";
        $mission->MissionType = 2;
        $mission->Description = "Rechercher des informations cachés";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Embuscade en forêt";
        $mission->MissionType = 2;
        $mission->Description = "Retrouver des provisions d'un ancien refuge rebelle abandonné";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Sympathisants rebelles";
        $mission->MissionType = 2;
        $mission->Description = "Assister à une séance de recrutement";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Ingérence Impériale";
        $mission->MissionType = 2;
        $mission->Description = "Survivre à un assaut impérial sur le Faucon Millenium";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Feu de broussaille";
        $mission->MissionType = 2;
        $mission->Description = "Détruire un TR-TT";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Tentation";
        $mission->MissionType = 2;
        $mission->Description = "Aider une padawan à retrouver son ancien maitre";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Au lever de la lune";
        $mission->MissionType = 2;
        $mission->Description = "Un réglement de compte entre une contrebandière et une vielle connaissance";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Endettés";
        $mission->MissionType = 2;
        $mission->Description = "Sauvetage de Wookies";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Marche ou crève";
        $mission->MissionType = 2;
        $mission->Description = "Recherche d'un prototype dans un complexe impérial";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "De vieilles connaissances";
        $mission->MissionType = 2;
        $mission->Description = "Sauvetage d'anciens camarades";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Navrés pour le désorde";
        $mission->MissionType = 2;
        $mission->Description = "Eliminer un officier Impérial";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Retour au pays";
        $mission->MissionType = 2;
        $mission->Description = "Aider Luke Skywalker à s'enfuir";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "La cargaison";
        $mission->MissionType = 2;
        $mission->Description = "Voler une cargaison d'Epice volée à un Hutt";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Cible de choix";
        $mission->MissionType = 2;
        $mission->Description = "Terminer une mission echouée par un groupe de rebelle";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Nid de vipère";
        $mission->MissionType = 2;
        $mission->Description = "Exfiltrer un commando Rebelle";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Une tâche élementaire";
        $mission->MissionType = 2;
        $mission->Description = "Recuperer des données chimique d'un complexe Impérial abandonné";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Généreuse donation";
        $mission->MissionType = 2;
        $mission->Description = "Saboter une installation informatique à l'aide de criminels locaux";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Croisière de luxe";
        $mission->MissionType = 2;
        $mission->Description = "Voler une navette de luxe";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Ennemis d'une vie antérieure";
        $mission->MissionType = 2;
        $mission->Description = "Piller un avant-poste Impérial";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Douteuses tractations";
        $mission->MissionType = 2;
        $mission->Description = "Recuperer des informations sur les forces Impériales en présence";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Accrochage au canyon";
        $mission->MissionType = 2;
        $mission->Description = "Dérober des plans de bataille Imperiaux";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Opération Ciel Embrasé";
        $mission->MissionType = 2;
        $mission->Description = "Détruire un Destroyer Stellaire";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Connais ton ennemi";
        $mission->MissionType = 2;
        $mission->Description = "Capturer un officier Impérial";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Sur le qui-vive";
        $mission->MissionType = 2;
        $mission->Description = "Sauver des prisonnier Mon Calamari";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Meusures préventives";
        $mission->MissionType = 2;
        $mission->Description = "Sauver un prisonnier Rebelle";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 1";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 2";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 3";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 4";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 5";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 6";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 7";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 8";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 9";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        $mission = new Mission();
        $mission->Name = "Mission 10";
        $mission->MissionType = 0;
        $mission->Description = "En attente de la V2";
        if ($missionType == -1 || $missionType == $mission->MissionType) $array[] = $mission;
        return $array;
    }

}