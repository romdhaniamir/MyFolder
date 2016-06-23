<?php

class Profile extends CI_Controller {

    public $session_local = array();
    public $company = "";
    public $user_id = '';
    public $session_admin = false;

    /**
     * construct
     */
    public function __construct() {
        parent::__construct();
        $this->session_local = $this->session->userdata('session_login_in');
        $this->user_id = $this->session_local['user_id'];
        $this->load->library(array('form_validation', 'encrypt', 'table'));
        if ($this->session->userdata('session_admin')['logged_in']) {
            $this->session_admin = TRUE;
        }
    }

    /* ========================================================================== Index page */

    /**
     * Index page for web controller.
     */
    public function index() {
        $this->load->helper('url');
        if (!$this->session_local['logged_in']) {
            redirect('#');
        } else {
            if (isset($_POST['add-sender'])) {
                $this->insert_name_sender();
            }
            
            $this->load->view('bootstrap/header');
            $this->load->model('User');
            $user = new User();
            $user->load($this->session_local['user_id']);
            if (isset($_POST['update-passe'])) {
                $this->update_Password($user);
            }if (isset($_POST['update-mail'])) {
                $this->update_Mail($user);
            }

            $senders = $this->get_list_sender();
            $this->load->view('profile_views/profile_view', array("user" => $user, "senders" => $senders, 'nbr_sms'=> $user->nbr_sms, "session_admin" => $this->session_admin));
            $this->get_footer();
        }
    }

    /**
     * 
     * @param type $user
     * @param type $senders
     * @return boolean
     */
    private function update_Mail($user) {
        $this->form_validation->set_rules($this->array_Rulse_For_Update_Email());
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {
            return FALSE;
        } else {
            $user->mail = $this->input->post('mail');
            $user->update();
            $this->auto_Login($user);
            $this->load->view("alert_view", array("message" => "Votre adresse mail a été modifier ", 'alert' => 'alert-success'));
            return TRUE;
        }
    }

    private function array_Rulse_For_Update_Email() {

        return array(array(
                'field' => 'mail',
                'rules' => 'required|valid_email|callback_key_exist_update',
            ),
            array(
                'field' => 'this-password',
                'label' => 'mot de passe',
                'rules' => 'required|min_length[6]|callback_password',
            ),
        );
    }

    /**
     * 
     * @param object $user
     * @param array $senders
     * @return boolean
     */
    private function update_Password($user) {
        $this->form_validation->set_rules($this->array_Rulse_For_Update_Passeword());
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {
            return false;
        } else {
            $user->password = $this->encrypt->encode($this->input->post('password'));
            $user->update();
            $this->load->view("alert_view", array("message" => "Votre mot de passe a été modifier ", 'alert' => 'alert-success'));
            return true;
        }
    }

    private function array_Rulse_For_Update_Passeword() {
        return array(
            array(
                'field' => 'this-password',
                'label' => 'mot de passe',
                'rules' => 'required|min_length[6]|callback_password',
            ),
            array(
                'field' => 'password',
                'label' => 'mot de passe',
                'rules' => 'required|min_length[6]|callback_password_validation',
            ),
            array(
                'field' => 'password2',
                'label' => 'confirmation du mot de passe',
                'rules' => 'required',
            ),
        );
    }

    /**
     * @param String $pass1 
     * @return boolean
     */
    public function password($pass) {
        $user = new User();
        $user->load($this->session_local['user_id']);
        if ($pass !== $this->encrypt->decode($user->password)) {
            $this->form_validation->set_message('password', 'Le mot de passe est incorrect.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @param String $pass1 
     * @return boolean
     */
    public function password_validation($pass1) {
        if ($this->input->post('password2') !== "$pass1") {
            $this->form_validation->set_message('password_validation', 'Les mots de passes ne sont pas identiques.');
            return FALSE;
        }
        return TRUE;
    }

    /* ========================================================================== update page */

    /**
     * page update
     */
    public function update() {
        $this->load->helper('url');

        if (!$this->session_local['logged_in']) {
            redirect('#');
        } else {
            $this->load->view('bootstrap/header');
            $this->load->library('form_validation');
            $this->load->model('User');
            $user = new User();
            $user->load($this->session_local['user_id']);
            $this->company = $user->company;
            $this->form_validation->set_rules($this->array_Rulse_For_Update());
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            if (!$this->form_validation->run()) {
                $this->load->view('profile_views/profile_update_view', array("user" => $user));
            } else {
                $this->update_User($user);

                $this->load->view("alert_view", array("message" => "Votre profile a été Modifier ", 'alert' => 'alert-success'));
                $this->load->view('profile_views/profile_update_view', array("user" => $user));
            }
            $ajax_url = array('ajax_url' => False);
            $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
        }
    }

    /**
     * auto login after update
     * @param object $user 
     */
    private function auto_Login($user) {
        $this->session->unset_userdata('session_login_in');
        $session_data = array(
            'user_id' => $user->user_id,
            'logged_in' => TRUE,
            'mail' => $user->mail
        );
        $this->session->set_userdata('session_login_in', $session_data);
    }

    /**
     * @param object $user 
     */
    private function update_User($user) {
        $user->l_name = $this->input->post('l_name');
        $user->f_name = $this->input->post('f_name');
        $user->country = $this->input->post('country');
        $user->state = $this->input->post('state');
        $user->company = $this->input->post('company');
        $user->address = $this->input->post('address');
        $user->function = $this->input->post('function');
        $user->phone_number = $this->input->post('phone_number');
        $user->date_modified = date("Y-m-d H:i:s");
        $user->update();
    }

    /**
     * function get rules array
     * @return array
     */
    private function array_Rulse_For_Update() {
        return array(array(
                'field' => 'f_name',
                'label' => 'prènom',
                'rules' => 'required|min_length[3]',
            ),
            array(
                'field' => 'l_name',
                'label' => 'nom',
                'rules' => 'required|min_length[3]',
            ),
            array(
                'field' => 'phone_number',
                'label' => 'Numéro téléphone',
                'rules' => 'required|min_length[8]|numeric',
            ),
            array(
                'field' => 'address',
                'label' => 'Adresse',
                'rules' => 'required|min_length[8]',
            ),
            array(
                'field' => 'function',
                'label' => 'Fonction',
                'rules' => 'required|min_length[5]',
            ),
            array(
                'field' => 'state',
                'label' => 'Ville',
                'rules' => 'required|min_length[3]',
            ),
            array(
                'field' => 'company',
                'label' => 'raison sociale',
                'rules' => 'required|min_length[3]|callback_key_exist_update_company',
            ),
        );
    }

    /*     * ************************************ Rules Callback ************** */

    /**
     * function check mail if exist
     * @param String $key  
     * @return boolean
     */
    public function key_exist_update($key) {
        //require model User
        if ($this->User->var_exists('mail', $key) && ($key != $this->session_local['mail'])) {
            $this->form_validation->set_message('key_exist_update', "L'adresse email existe déjà.");
            return FALSE;
        }
        return TRUE;
    }

    public function key_exist_update_company($key) {
        //require model User
        if ($this->User->var_exists('company', $key) && ($key != $this->company)) {
            $this->form_validation->set_message('key_exist_update_company', "Raison sociale existe déjà.");
            return FALSE;
        }
        return TRUE;
    }


    /* =========================================================================VIEW CAMP LIST */


    /**
     * get lists  of names_sender
     * @param int id
     * @return lists array
     */
    private function get_list_sender() {
        $this->load->model('Name_sender');
        $lists_table = array();
        $where_array = array(array(
                'where_tag' => "user_id",
                'where_value' => $this->user_id
        ));
        $senders = $this->Name_sender->get(0, 0, $where_array);

        foreach ($senders as $sender) {
            $delete = '';
            if($this->session_admin){
                $delete =  '<a href="' . base_url() . 'index.php/profile/delete_sender/' . $sender->sender_id . '"><i class="fa fa-trash-o"></i></a>';
            }
            $lists_table[] = array(
                $sender->name_sender,
                $delete                  
            );
        }
        return $lists_table;
    }

    /**
     * @param object $user
     * @param array $senders
     * @return boolean
     */
    private function insert_name_sender() {
        $this->load->model('Name_sender');
        $this->form_validation->set_rules($this->array_Rulse_For_Inser_Sender());
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {
            return FALSE;
        } else {
            $sender = new Name_sender();
            $sender->user_id = $this->user_id;
            $sender->name_sender = $this->input->post('sender_name');
            $sender->insert();
            $senders = $this->get_list_sender();
            $this->load->view("alert_view", array("message" => "Nom expéditeur a été ajouter ", 'alert' => 'alert-success'));
            return TRUE;
        }
    }

    private function array_Rulse_For_Inser_Sender() {
        return array(
            array(
                'field' => 'sender_name',
                'label' => 'Nom expéditeur',
                'rules' => 'required|max_length[11]|callback_key_exists',
            )
        );
    }

    /**
     *  Rules Callback
     * @param String $key 
     * @return boolean
     */
    public function key_exists($key) {
        if ($this->Name_sender->var_exists_for_user('name_sender', $key, "user_id", $this->session_local['user_id'])) {

            $this->form_validation->set_message('key_exists', "Nom de la liste existe déjà.");
            return FALSE;
        }
        return TRUE;
    }

    /**
     * delete name sender
     * @param type $id_sender
     */
    public function delete_sender($id_sender) {
        $this->load->helper('url');
        $this->load->model("Name_sender");
        if ($this->Name_sender->var_exists_for_user('sender_id', $id_sender, "user_id", $this->session_local['user_id'])) {
            $sender = new Name_sender();
            $sender->load($id_sender);
            $sender->delete();
        }
        redirect('/profile');
    }
    public function solde_header(){
        $this->load->model('User');
        $user = new User();
        $user->load($this->session_local['user_id']);
        echo  $user->nbr_sms;
    }

    /* =========================================================================FUNCTIONS */

    /**
     * get_footer
     */
    private function get_footer() {
    $ajax_url = array(
        'ajax_url' => true,
        'object_services_urls' => json_encode(array()),
        'ajax_sript' => array('profile.js')
        );
    $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
}
}
