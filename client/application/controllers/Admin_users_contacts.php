<?php

class Admin_users_contacts extends CI_Controller {

  

    /**
     * construct
     */
    public function __construct() {
        parent::__construct();

    }

    /* =========================================================================INDEX */

    public function index() {
        $this->load->helper('url');
        $this->load->library(array('form_validation', 'table'));
    echo 'admin users contacts' ;
    }

}
