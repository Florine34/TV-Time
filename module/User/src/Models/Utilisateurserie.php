<?php
namespace User\Models;

class Utilisateurserie {
    public $_idUtilisateur;
    public $_idSerie;
    public $_episodesRestants;
    public $_episodesVus;
    public $_note;
    public $_favoris;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_idUtilisateur = (!empty($data['idUtilisateur'])) ? $data['idUtilisateur'] : null;
        $this->_idSerie = (!empty($data['idSerie'])) ? $data['idSerie'] : null;
        $this->_episodesRestants = (!empty($data['episodesRestants'])) ? $data['episodesRestants'] : null;
        $this->_episodesVus = (!empty($data['episodesVus'])) ? $data['episodesVus'] : null;
        $this->_note = (!empty($data['note'])) ? $data['note'] : null;
        $this->_favoris = (!empty($data['favoris'])) ? $data['favoris'] : null;
    }

    public function toValues(){
        return [
            'idUtilisateur' => $this->_idUtilisateur,
            'idSerie' => $this->_idSerie,
            'episodesRestants' => $this->_episodesRestants,
            'episodesVus' => $this->_episodesVus,
            'note' => $this->_note,
            'favoris' => $this->_favoris
        ];
    }
}
?>