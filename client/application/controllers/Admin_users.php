<?php

class Admin_users extends CI_Controller {

    /**
     * construct
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('User');
        $this->load->library('form_validation');
    }

    /* =========================================================================INDEX */

    public function index() {
        $this->load->helper('url');
        if (!$this->session->userdata('session_admin')['logged_in']) {
            redirect('/admin');
        } else {
            $this->load->library(array('form_validation', 'table'));
            $this->load->view('bootstrap/header_admin');
            $users_count = isset($_POST['users_count']) ? $this->input->post('users_count') : 0;
            $user_company = isset($_POST['user_company']) ? $this->input->post('user_company') : '';
            $user_email = isset($_POST['user_email']) ? $this->input->post('user_email') : '';
            $users_table = $this->get_users_table($users_count, $user_company, $user_email);
            $this->load->view('admin/users/all_users_view', array("users_table" => $users_table));
            $this->get_footer();
        }
    }

    private function get_users_table($limit, $user_company, $user_email) {

        $table = array();
        $where_array = array();
        
        $company = 'company like "%'.$user_company.'%"';
        $email = 'mail like "%'.$user_email.'%"';
        $where_array = array(
            array('where_tag' => $company.'and '.$email,
                'where_value' => ''),
        );
        
        $users_ob = $this->User->get($limit, 0, $where_array);
        foreach ($users_ob as $user) {
            $table[] = array(
                $user->date_created,
                $user->f_name,
                $user->l_name,
                $user->mail,
                $user->company,
                $user->phone_number,
                "<a class='link-user'href='get_session_user/{$user->user_id}' TARGET='_blank'>Accéder  </a> <i class='fa fa-angle-double-right'></i>",
                $user->nbr_sms,
                "<form action='" . base_url('index.php') . "/admin/add_sms/' method='POST' class='form-inline'>
                    <input type='number' class='form-control' name='nbr_sms' style='width: 80px;' required>
                    <input type='hidden' value='{$user->user_id}' name='id_user'>
                    <input type='submit' value='Ajouter' class='btn btn-primary'>
                </form>",
            );
        }
        return $table;
    }

    /* =========================================================================FUNCTIONS */

    /**
     * get_footer
     */
    private function get_footer() {
        $ajax_url = array(
            'ajax_url' => true,
            'object_services_urls' => json_encode(array()),
            'ajax_sript' => array('admin-users.js')
        );
        $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
    }

}
