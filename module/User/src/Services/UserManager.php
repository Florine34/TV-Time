<?php
namespace User\Services;

use Zend\Db\TableGateway\TableGatewayInterface;

class UserManager {
    protected $_tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->_tableGateway = $tableGateway;
    }

    //Renvoie requete pour un nom d'utilisateur
    public function findByUsername($username){
        return $this->_tableGateway->select(['username' => $username])->current();
    }
    //Renvoie requete pour un mail d'utilisateur
    public function findByMail($mail){
        return $this->_tableGateway->select(['mail' => $mail])->current();
    }

    //Changement de mail
    public function changerMail($ancienMail,$nouveauMail){
        $this->_tableGateway->update(['mail'=>$nouveauMail],['mail'=>$ancienMail]);
        return ;
    }
}
?>