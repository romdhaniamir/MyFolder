<?php

class List_contact extends My_Model{
    const DB_TABLE = 'contacts_lists';
    const DB_TABLE_PK = 'list_id';
    
     /**
     * user unique identifier.
     * @var int
     */
    public $list_id;
    
    /**
     * FOREIGN KEY 
     * @var string
     */
    public $user_id;
    
     /**
     * @var string
     */
    public $name;
    
  
}

