<?php
namespace User\Services;

use Zend\Db\TableGateway\TableGatewayInterface;
use User\Models\Badge;

class BadgeTable {
    protected $_tableGateway;
    protected $authService;
    protected $userManager;
    protected $_utilisateurSerie;

    public function __construct(TableGatewayInterface $tableGateway,$authService,$userManager){
        $this->_tableGateway = $tableGateway;
        $this->authService = $authService;
        $this->userManager = $userManager;
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
    public function findById($idBadge)
    {
        return $this->_tableGateway->select(['idBadge' => $idBadge])->current();
    }
}
?>