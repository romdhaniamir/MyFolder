<?php

class Historique_sms extends MY_Model {

    const DB_TABLE = 'historique_sms';
    const DB_TABLE_PK = 'id';

    public $user_id;
    public $admin_id;
    public $NbrSms;
    public $date_ajout;
}