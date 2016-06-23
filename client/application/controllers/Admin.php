<?php

class Admin extends CI_Controller {

    /**
     * construct
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Historique_sms');
    }

    /* =========================================================================INDEX */

    public function index() {
        /*     $this->load->model('User');
          $user = new User();
          $user->load_with_key("mail", 'ons@mania.tn');
          $user->role='admin';
          $user->update(); */

        $this->load->helper('url');
        if ($this->session->userdata('session_admin')['logged_in']) {
            redirect('/admin/users');
        } else {
            $this->load->library(array('form_validation', 'table'));
            $this->load->view('bootstrap/header_login');
            $this->login();
        }
    }

    /**
     * login from web
     */
    private function login() {
        $this->load->model('User');
        $this->load->library('encrypt');
        $this->form_validation->set_rules(array(
            array(
                'field' => 'password',
                'label' => 'mot de passe',
                'rules' => 'required',
            ),
            array(
                'field' => 'mail',
                'rules' => 'required|valid_email|callback_key_login',
            ),
        ));
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');


        if (!$this->form_validation->run()) {
            $this->load->view('admin/login/login_view');
        } else {

            $user = new User();
            $user->load_with_key("mail", $this->input->post('mail'));
            $session_admin = array(
                'user_id' => $user->user_id,
                'logged_in' => TRUE,
                'mail' => $user->mail
            );

            $this->session->set_userdata('session_admin', $session_admin);
            redirect('/admin/users');
        }
    }

    /**
     * logout from web
     */
    public function logout() {
        // Removing session data
        $this->load->helper('url');
        $this->load->helper('file');
        delete_files('download_files', TRUE);
        $this->session->unset_userdata('session_admin');
        redirect('/admin');
    }

    /**
     * @param String $keyL 
     * @return boolean
     */
    public function key_login($keyL) {
        if (!$this->User->var_exists('mail', $keyL)) {

            $this->form_validation->set_message('key_login', "L'adresse email est introuvable.");
            return FALSE;
        } else {

            $user = new User();
            $user->load_with_key("mail", $this->input->post('mail'));
            if ($user->role !== "admin") {
                $this->form_validation->set_message('key_login', "Ce compte ne correspond pas à un compte administrateur.");
                return FALSE;
            } else if ($this->input->post('password') !== $this->encrypt->decode($user->password)) {

                $this->form_validation->set_message('key_login', "Le mot de passe est incorrect.");
                return FALSE;
            }
        }
        return TRUE;
    }

    /* =========================================================================NEW SESSION USER */

    public function get_session_user($user_id) {
        if (!$this->session->userdata('session_admin')['logged_in']) {
            redirect('/admin');
        } else {
            $this->load->helper('url');
            $this->load->model('User');
            $this->logout_user();
            $user = new User();
            $user->load($user_id);
            $session_data = array(
                'user_id' => $user->user_id,
                'logged_in' => TRUE,
                'mail' => $user->mail
            );
            $url = base_url();
            $this->session->set_userdata('session_login_in', $session_data);

            redirect('/');
        }
    }

    public function add_sms() {
        $this->form_validation->set_rules('nbr_sms', 'Nombre des sms', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $id_user = $this->input->post('id_user');
            $nbr_sms = $this->input->post('nbr_sms');
            $this->load->model('User');
            $user = new User();
            $user->load($id_user);
            $user->nbr_sms = $user->nbr_sms + $nbr_sms;
            $user->update();
            $this->load->model('Historique_sms');
            $historique = new Historique_sms();
            $historique->user_id = $id_user;
            $historique->admin_id = $this->session->userdata('session_admin')['user_id'];
            $historique->NbrSms = $nbr_sms;
            $historique->date_ajout = Date('Y-m-d');
            $historique->insert();
            $this->index();
        }
    }

    public function historique() {

        $this->load->helper('url');
        if (!$this->session->userdata('session_admin')['logged_in']) {
            $this->index();
        } else {
            $users_count = isset($_POST['users_count']) ? $this->input->post('users_count') : 0;
            $user_company = isset($_POST['user_company']) ? $this->input->post('user_company') : '';
            $user_email = isset($_POST['user_email']) ? $this->input->post('user_email') : '';

            $table['historique_table'] = $this->get_historique_table($users_count, $user_company, $user_email);
            $this->load->library(array('form_validation', 'table'));
            $this->load->view('bootstrap/header_admin');
            $this->load->view('admin/users/historique_sms', $table);
            $ajax_url = array(
                'ajax_url' => true,
                'object_services_urls' => json_encode(array()),
                'ajax_sript' => array('admin-users.js')
            );
            $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
        }
    }

    public function get_historique_table($limit, $user_company, $user_email) {
		$table = array();
        $where_array = array();        
        $where = 'users.user_id = historique_sms.user_id and users.company like "%'.$user_company.'%"';
        $email = 'and users.mail like "%'.$user_email.'%"';
        $where_array = array(
            array('where_tag' => $where.' '.$email,
                'where_value' => ''),
        );
        $this->load->model('user');
        $query = $this->Historique_sms->get_with_join('',array(array('table' => 'User')),$where_array, $limit, 0);
        foreach ($query as $historique) {
            $admin = new User();
            $admin->load($historique->admin_id);
            $table[] = array(
                $admin->f_name.' '.$admin->l_name,
                $historique->company,
                $historique->l_name,
                $historique->mail,
                $historique->NbrSms,
                $historique->date_ajout,
            );
        }
        return $table;
    }
    public function transaction(){
        $this->load->helper('url');
        if (!$this->session->userdata('session_admin')['logged_in']) {
            $this->index();
        } else {

            $this->load->library(array('form_validation', 'table'));
            $this->load->view('bootstrap/header_admin');
            $this->load->model('Commande');
            $this->load->model('User');
            $this->load->model('Pack');
            $user_company = isset($_POST['user_company']) ? $this->input->post('user_company') : '';
            $user_email = isset($_POST['user_email']) ? $this->input->post('user_email') : '';
            $table['saved'] = $this->get_saved_table($user_company, $user_email);
            $table['success'] = $this->get_success_table($user_company, $user_email);
            $table['refused'] = $this->get_refused_table($user_company, $user_email);
            $table['canceled'] = $this->get_canceled_table($user_company, $user_email);
            $table['company'] = $user_company;
            $table['mail'] = $user_email;
            $this->load->view('admin/Transaction/transaction',$table );
            $ajax_url = array(
                'ajax_url' => true,
                'object_services_urls' => json_encode(array()),
                'ajax_sript' => array('admin-users.js')
            );
            $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
        }
    }
     public function get_saved_table($user_company, $user_email) {
        $table = array();
        $where = 'and users.company like "%'.$user_company.'%" and users.mail like "%'.$user_email.'%"';
        $where_array = array(
            array('where_tag' => 'commande.state = "saved" '.$where,
                'where_value' => ''),
        );
        $query = $this->Commande->get_with_join('',array(array('table' => 'User')),$where_array, 0, 0);
        foreach ($query as $commande) {
            $pack = new Pack();
            $pack->load($commande->id_pack);
            $table[] = array(
                $commande->company,
                $commande->mail,
                $pack->name,
                $commande->count,
                $commande->amount,
                $commande->date_commande,
            );
        }
        return $table;
    }
    public function get_success_table($user_company, $user_email) {
        $table = array();
        $where = 'and users.company like "%'.$user_company.'%" and users.mail like "%'.$user_email.'%"';
        $where_array = array(
            array('where_tag' => 'commande.state = "Success" '.$where,
                'where_value' => ''),
        );
        $query = $this->Commande->get_with_join('',array(array('table' => 'User')),$where_array, 0, 0);
        foreach ($query as $commande) {
            $pack = new Pack();
            $pack->load($commande->id_pack);
            $table[] = array(
                $commande->company,
                $commande->mail,
                $pack->name,
                $commande->count,
                $commande->amount,
                $commande->date_commande,
            );
        }
        return $table;
    }
    public function get_refused_table($user_company, $user_email) {
        $table = array();
        $where = 'and users.company like "%'.$user_company.'%" and users.mail like "%'.$user_email.'%"';
        $where_array = array(
            array('where_tag' => 'commande.state = "Refused" '.$where,
                'where_value' => ''),
        );
        $query = $this->Commande->get_with_join('',array(array('table' => 'User')),$where_array, 0, 0);
        foreach ($query as $commande) {
            $pack = new Pack();
            $pack->load($commande->id_pack);
            $table[] = array(
                $commande->company,
                $commande->mail,
                $pack->name,
                $commande->count,
                $commande->amount,
                $commande->date_commande,
            );
        }
        return $table;
    }
    public function get_canceled_table($user_company, $user_email) {
        $table = array();
        $where = 'and users.company like "%'.$user_company.'%" and users.mail like "%'.$user_email.'%"';
        $where_array = array(
            array('where_tag' => 'commande.state = "Canceled" '.$where,
                'where_value' => ''),
        );
        $query = $this->Commande->get_with_join('',array(array('table' => 'User')),$where_array, 0, 0);
        foreach ($query as $commande) {
            $pack = new Pack();
            $pack->load($commande->id_pack);
            $table[] = array(
                $commande->company,
                $commande->mail,
                $pack->name,
                $commande->count,
                $commande->amount,
                $commande->date_commande,
            );
        }
        return $table;
    }

    /**
     * logout from web
     */
    public function logout_user() {
        $this->session->unset_userdata('session_login_in');
        $this->session->unset_userdata('session_campaign');
    }

}
