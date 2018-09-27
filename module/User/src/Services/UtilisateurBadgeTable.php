<?php
namespace User\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use User\Models\Utilisateurbadge;

class UtilisateurBadgeTable {
    protected $_tableGateway;
    protected $authService;
    protected $userManager;
    protected $_utilisateurSerie;

    public function __construct(TableGatewayInterface $tableGateway,$authService,$userManager,$utilisateurSerie){
        $this->_tableGateway = $tableGateway;
        $this->authService = $authService;
        $this->userManager = $userManager;
        $this->_utilisateurSerie = $utilisateurSerie;
    }

    // Fonction permettant d'insérer un badge dans la base de donnée
    public function insertBadge(Utilisateurbadge $s){
        $this->_tableGateway->insert($s->toValues());
    }

    //Renvoie tout
    public function fetchAll() {
        $resultSet = $this->_tableGateway->select();
        $return = array();
        foreach( $resultSet as $r )
            $return[]=$r;
        return $return;
    }

    //Renvoie requete pour un id utilisateur
    public function findById($idUser){
        return $this->_tableGateway->select(['idUtilisateur' => $idUser])->current();
    }


    //Récupère tous les badges de l'utilisateur connecté
    public function fetchByUserConnected(){

        //Récupère id de l'utilisateur connecté
        $id=$this->getUserConnected();

        //Récupère les objets
        $resultSet=$this->_tableGateway->select(['idUtilisateur' => $id]);
        $return = array();
        foreach( $resultSet as $r )
            $return[]=$r;
        return $return;
    }


    //Récupère l'id de l'utilisateur connecté
    public function getUserConnected(){
        return $this->userManager->findByMail($this->authService->getIdentity())->_id;
    }

    public function exist($idUtilisateur,$idBadge){
        $resultSet=$this->_tableGateway->select(['idUtilisateur' => $idUtilisateur,'idBadge'=>$idBadge]);
        $i=0;
        $test=false;
        foreach( $resultSet as $r )
            $i++;

        if($i!=0){
            $test=true;
        }
        return $test;
    }

    //Test pour les badges
    public function testBadges($idUtilisateur){
        $this->_utilisateurSerie->findById($idUtilisateur);


        //si a vu 50 épisodes
        if($this->_utilisateurSerie->countEpisodesById($idUtilisateur)>=50){
            if(!$this->exist($idUtilisateur,4)){
                $this->_tableGateway->insert(['idUtilisateur'=>$idUtilisateur,'idBadge'=>4]);
            }
        }


        //si suit 5 séries
        if($this->_utilisateurSerie->countById($idUtilisateur)>=5){
            if(!$this->exist($idUtilisateur,3)){
                $this->_tableGateway->insert(['idUtilisateur'=>$idUtilisateur,'idBadge'=>3]);
            }
        }

        //Si a vu 10 épisodes d'une série
        if($this->_utilisateurSerie->test10EpisodesSerie($idUtilisateur)){
            if(!$this->exist($idUtilisateur,1)){
                $this->_tableGateway->insert(['idUtilisateur'=>$idUtilisateur,'idBadge'=>1]);
            }
        }


    }

}
?>