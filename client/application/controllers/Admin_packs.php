<?php

class Admin_packs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Pack');
        $this->load->library('form_validation');
        $this->load->helper('date');
    }

    public function index() {
        if (!$this->session->userdata('session_admin')['logged_in']) {
            redirect('/admin');
        } else {
            $this->load->library(array('form_validation', 'table'));
            $this->load->view('bootstrap/header_admin');
            $packs_table['packs_table'] = $this->get_packs_table();
            $this->load->view('admin/packs/packs_view', $packs_table);
            $this->get_footer();
        }
    }

    private function get_footer() {
        $ajax_url = array(
            'ajax_url' => true,
            'object_services_urls' => json_encode(array()),
            'ajax_sript' => array('libs/jquery.datetimepicker.full.js','admin_packs.js')
        );
        $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
    }

    public function get_packs_table() {
		$table = array();
        $packs_ob = $this->Pack->get();
        foreach ($packs_ob as $pack) {
            $table[] = array(
                $pack->name,
                $pack->desc,
                $pack->nbr_sms,
                $pack->pays,
                $pack->date_debut,
                $pack->date_fin,
                $pack->price.' DT',
                "<a class='link-user' href='". base_url("index.php")."/admin_packs/delete_pack/{$pack->id_pack}'>  <i class='fa fa-trash'></i></a> "
            );
        }
        return $table;
    }
    public function update_pack(){
        
    }
    public function delete_pack($id){
        
        $pack = new Pack();
        $pack->load($id);
        $pack->delete();
        $this->index();
    }

    public function create_pack() {
        $this->form_validation->set_rules('name', 'Nom', 'required|is_unique[packs.name]');
        $this->form_validation->set_rules('price', 'Prix', 'required');
        $this->form_validation->set_rules('nbr_sms', 'Nombre des sms', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $name = $this->input->post('name');
            $price = $this->input->post('price');
            $nbr_sms = $this->input->post('nbr_sms');
            $pays = $this->input->post('pays');
            $desc = $this->input->post('desc');
            $date_debut = $this->input->post('date_debut');
            $date_fin = $this->input->post('date_fin');
            
            $this->Pack->create($name, $desc, $price,$nbr_sms, $pays, $date_debut, $date_fin);
            $this->index();
        }
    }

}
