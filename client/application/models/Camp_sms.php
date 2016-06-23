<?php

class Camp_sms extends My_Model {

    const DB_TABLE = 'camp_sms';
    const DB_TABLE_PK = 'sms_id';

    /**
     * user unique identifier.
     * @var int
     */
    public $sms_id;

    /**
     * FOREIGN KEY 
     * @var string
     */
    public $user_id;

    /**
     * @var string
     */
    public $name_camp;

    /**
     * @var string
     */
    public $sms_text;

    /**
     * @var array json
     */
    public $lists;

    /**
     * @var string
     */
    public $name_sender;

    /**
     * @var boolean
     */
    public $all_contacts;

    /**
     * @var int
     */
    public $all_contacts_count;

    /**
     * @var int
     */
    public $contacts_count;

    /**
     * @var string
     */
    public $state;

    /**
     * @var date
     */
    public $date_created;

    /**
     * @var date
     */
    public $date_start_send;

    /**
     * @var date
     */
    public $date_end_send;

    /**
     * @var int
     */
    public $id_envoie;


}
