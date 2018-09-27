<?php
namespace User\Models;

class Utilisateurepisodeserie {
    public $_idUtilisateur;
    public $_idSerie;
    public $_idSaison; 
    public $_idEpisode;
    public $_note;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_idUtilisateur = (!empty($data['idUtilisateur'])) ? $data['idUtilisateur'] : null;
        $this->_idSerie = (!empty($data['idSerie'])) ? $data['idSerie'] : null;
        $this->_idSaison = (!empty($data['idSaison'])) ? $data['idSaison'] : null;
        $this->_idEpisode= (!empty($data['idEpisode'])) ? $data['idEpisode'] : null;
        $this->_note = (!empty($data['note'])) ? $data['note'] : null;
    }

    public function toValues(){
        return [
            'idUtilisateur' => $this->_idUtilisateur,
            'idSerie' => $this->_idSerie,
            'idSaison' => $this->_idSaison,
            'idEpisode' => $this->_idEpisode,
            'note' => $this->_note
        ];
    }
}
?>