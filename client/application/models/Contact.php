<?php

class Contact extends My_Model {

    const DB_TABLE = 'contacts';
    const DB_TABLE_PK = 'contact_id';

    /**
     * phone_numbe unique identifier.
     * @var int
     */
    public $contact_id;

    /**
     *  unique
     * @var string
     */
    public $phone_number;

    /**
     * FOREIGN KEY 
     * @var int
     */
    public $list_id;

    /**
     * @var string
     */
    public $mail;

    /**
     * @var string
     */
    public $l_name;

    /**
     * @var string
     */
    public $f_name;

    /**
     * @var string
     */
    public $sex;

    /**
     * @var Adress
     */
    public $address;

    /**
     * @var string
     */
    public $date_created;

    /**
     * @var string
     */
    public $date_modified;

    /**
     * @var string
     */
    public $country;

    /**
     * @var boolean 
     */
    public $trash;

}
