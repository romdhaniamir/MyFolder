<?php

class User extends MY_Model {

    const DB_TABLE = 'users';
    const DB_TABLE_PK = 'user_id';

    /**
     * user unique identifier.
     * @var int 
     */
    public $user_id;
    public $nbr_sms;
    /**
     * @var string
     */
    public $mail;

    /**
     * @var string
     */
    public $company;

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
    public $role;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $date_created;

    /**
     * @var string
     */
    public $date_modified;

    /**
     * @var boolean 
     */
    public $access;

    /**
     * @var int
     */
    public $phone_number;

    /**
     * @var int
     */
    public $state;

    /**
     * @var int
     */
    public $country;

    /**
     * @var int
     */
    public $function;

   

    /**
     * @var Adress
     */
    public $address;

}
