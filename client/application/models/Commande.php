<?php

class Commande extends MY_Model {

    const DB_TABLE = 'commande';
    const DB_TABLE_PK = 'id';

    public $id;
    public $user_id;
    public $id_pack;
    public $amount;
    public $count;
    public $date_commande;
    public $state;
}