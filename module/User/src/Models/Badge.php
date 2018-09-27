<?php
namespace User\Models;

class Badge {
    public $_idBadge;
    public $_nom;
    public $_photo;
    public $_description;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->_idBadge = (!empty($data['idBadge'])) ? $data['idBadge'] : null;
        $this->_nom = (!empty($data['nom'])) ? $data['nom'] : null;
        $this->_photo = (!empty($data['photo'])) ? $data['photo'] : null;
        $this->_description = (!empty($data['description'])) ? $data['description'] : null;
    }

    public function toValues(){
        return [
            'idBadge' => $this->_idBadge,
            'nom' => $this->_nom,
            'photo' => $this->_photo,
            'description' => $this->_description,
        ];
    }

}
?>