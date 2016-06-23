<?php

class Home extends CI_Controller {

    /**
     * Index page for web controller.
     */
    public function index() {
        $this->load->model('User');
        $user = new User();
        $user->load_with_key("mail", 'ons@mania.tn');
        $user->update();

        $this->load->helper('url');
        if ($this->session->userdata('session_login_in')) {
            redirect('lists_contacts');
        } else {
            $this->load->view('bootstrap/header_login');
            $this->load->library('form_validation');
            $this->load->model('User');
            $this->load->library('encrypt');
            if (isset($_POST['login']))
                $this->login();
            else
                $this->inscription();
        }

        $ajax_url = array('ajax_url' => False);
        $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
    }

    /**
     * login from web
     */
    public function login() {

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
            //var_dump($_SESSION);
            /* echo session_id(); */
            if ($this->session->userdata('session_login_in')) {
                $this->load->view('home_views/home');
            } else {
                $this->load->view('home_views/home_login_view');
            }
        } else {

            $user = new User();
            $user->load_with_key("mail", $this->input->post('mail'));
            $session_data = array(
                'user_id' => $user->user_id,
                'logged_in' => TRUE,
                'mail' => $user->mail
            );
            $this->session->set_userdata('session_login_in', $session_data);
            redirect('/');
            //var_dump($this->session->userdata('session_login_in'));
        }
    }

    /**
     * logout from web
     */
    public function logout() {
        // Removing session data
        $this->load->helper('url');
        $this->load->helper('file');
        //company for path
        $this->load->model('User');
        $user = new User();
        $user->load($this->session->userdata('session_login_in')['user_id']);
        $company = $user->company;
        delete_files('upload_files/mania' , TRUE);
        $this->session->unset_userdata('session_login_in');
        $this->session->unset_userdata('session_campaign');
        
        redirect('/home');
    }

    /**
     * inscription
     */
    public function inscription() {
        $this->load->model('User');
        $this->form_validation->set_rules(array(
            array(
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
                'field' => 'company',
                'label' => 'raison sociale',
                'rules' => 'required|min_length[3]|callback_key_exists_company',
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
            array(
                'field' => 'mail',
                'label' => 'adresse email',
                'rules' => 'required|valid_email|callback_key_exists',
            ),
        ));
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {
            $this->load->view('home_views/home_login_view');
        } else {
            $user = new User();
            $user->mail = $this->input->post('mail');
            $user->l_name = $this->input->post('l_name');
            $user->f_name = $this->input->post('f_name');
            $user->company = $this->input->post('company');
            $user->role = "user";
            $user->password = $this->encrypt->encode($this->input->post('password'));
            $user->date_created = date("Y-m-d H:i:s");
            $user->access = TRUE;
            $user->nbr_sms = 0 ;
            $user->insert();
            if (!is_dir('upload_files/' . $user->company)) {
                mkdir('upload_files/' . $user->company, 0777, TRUE);
            }
            /* get view success */
            $this->load->view('home_views/inscription_success_view');
        }
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

    public function test_decode() {
        $msgtest = $this->input->post('password');
        if ($msgtest) {
            $encrypted_string = $this->encrypt->encode($msgtest);
            echo $encrypted_string;
            $plaintext_string = $this->encrypt->decode($encrypted_string);
            echo $plaintext_string;
        }
    }

    /**
     * @param String $key 
     * @return boolean
     */
    public function key_exists($key) {
        if ($this->User->var_exists('mail', $key)) {

            $this->form_validation->set_message('key_exists', "L'adresse email existe déjà.");
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @param String $key 
     * @return boolean
     */
    public function key_exists_company($key) {
        if ($this->User->var_exists('company', $key)) {
            $this->form_validation->set_message('key_exists_company', "Raison sociale existe déjà.");
            return FALSE;
        }
        return TRUE;
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

            if ($this->input->post('password') !== $this->encrypt->decode($user->password)) {

                $this->form_validation->set_message('key_login', "Le mot de passe est incorrect.");
                return FALSE;
            }
        }
        return TRUE;
    }

}
