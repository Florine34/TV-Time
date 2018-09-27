<?php
namespace User\Models;

class Utilisateurbadge {
    public $_idUtilisateur;
    public $_idBadge;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_idUtilisateur = (!empty($data['idUtilisateur'])) ? $data['idUtilisateur'] : null;
        $this->_idBadge = (!empty($data['idBadge'])) ? $data['idBadge'] : null;
    }

    public function toValues(){
        return [
            'idUtilisateur' => $this->_idUtilisateur,
            'idSerie' => $this->_idBadge,
        ];
    }

}
?>