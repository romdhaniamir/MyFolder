<?php

class Name_sender extends My_Model{
    const DB_TABLE = 'sms_sender_names';
    const DB_TABLE_PK = 'sender_id';
    
     /**
     * user unique identifier.
     * @var int
     */
    public $sender_id;
    
    /**
     * FOREIGN KEY 
     * @var string
     */
    public $user_id;
    
     /**
     * @var string
     */
    public $name_sender;
    
  
}