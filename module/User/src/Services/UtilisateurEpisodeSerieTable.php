<?php
namespace User\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use User\Models\Utilisateurepisodeserie;

class UtilisateurEpisodeSerieTable {
    protected $_tableGateway;
    protected $authService;
    protected $userManager;

    public function __construct(TableGatewayInterface $tableGateway,$authService,$userManager){
        $this->_tableGateway = $tableGateway;
        $this->authService = $authService;
        $this->userManager = $userManager;
    }
    
    // Fonction permettant d'insérer une dans la table Utilisateur Episode Serie
    public function insert(Utilisateurepisodeserie $s){
        $this->_tableGateway->insert($s->toValues());
    }

    // Fonction permettant de supprimer un élément en fonction de son id 
    public function delete($idUser, $idSerie, $idSaison, $idEpisode){
        return $this->_tableGateway->delete(['idUtilisateur' => $idUser,'idSerie' => $idSerie, 'idSaison'=>$idSaison, 'idEpisode'=>$idEpisode]);
    }

    //
    public function select($idUser, $idSerie, $idSaison, $idEpisode){
        return $this->_tableGateway->select(['idUtilisateur' => $idUser,'idSerie' => $idSerie, 'idSaison'=>$idSaison, 'idEpisode'=>$idEpisode])->current();
    }

}
?>