<?php

class Pack extends MY_Model {

    const DB_TABLE = 'pack';
    const DB_TABLE_PK = 'id_pack';
    
    public $id_pack;
    public $name;
    public $desc;
    public $price;
    public $date_debut;
    public $date_fin;
    public $pays;
    public $nbr_sms;
    
    public function create($name, $desc, $price, $nbr_sms, $pays, $date_d, $date_f){
        $this->name = $name;
        $this->desc = $desc;
        $this->price = $price;
        $this->date_debut = $date_d;
        $this->date_fin = $date_f;
        $this->pays = $pays;
        $this->nbr_sms = $nbr_sms;
        $this->insert();
    }
}