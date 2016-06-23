<?php

class Campaigns extends CI_Controller {

    public $session_local = array();
    public $user_id;

/**
 * construct
 */
public function __construct() {
    parent::__construct();
    $this->session_local = $this->session->userdata('session_login_in');
    $this->user_id = $this->session_local['user_id'];
}

/* =========================================================================INDEX */

public function index() {
    $this->load->helper('url');
    $this->load->library(array('form_validation', 'table'));
    if (!$this->session_local['logged_in']) {
        redirect('#');
    } else {
        $role = isset($_POST['role-camp']) ? $this->input->post('role-camp') : 'saved';
        $deleted = isset($_POST['delete']) ? $this->input->post('delete') : FALSE;
        $campaign_count = isset($_POST['campaign_count']) ? $this->input->post('campaign_count') : 10;
        $this->view($role, $campaign_count, $deleted);
    }
}

/* =========================================================================VIEW CAMP LIST */

/**
 * function view campaign  lists
 * @param type $role
 */
public function view($role, $limit = 0, $deleted = FALSE) {
    $this->load->helper('url');
    $this->load->model('Camp_sms');
    $this->load->library(array('form_validation', 'table'));
    if ($deleted) {
        $this->delete($deleted);
    }
    $this->load->view('bootstrap/header');
    $campaigns_saved = $this->get_campaigns_table($role, 0, $limit);
    $campaigns_done = $this->get_campaigns_table_termine('', 0, $limit);
    $campaigns_progress = $this->get_campaigns_table_progress('', 0, $limit);
    $campaigns_echec = $this->get_campaigns_table_echec('echec', 0, $limit);

    $this->load->view('campaigns_view/campaigns_view', array("campaigns_saved" => $campaigns_saved,"campaigns_done" => $campaigns_done ,"campaigns_progress" => $campaigns_progress,"campaigns_echec" => $campaigns_echec,"role" => $role));
    $this->get_footer();
}

/**
 * get campaigns
 * @param int id
 * @return lists array
 */
private function get_campaigns_table($role, $offset, $limit) {
    $this->load->model('Camp_sms');
    $table = array();
    $where_array = array(array(
        'where_tag' => "user_id",
        'where_value' => $this->user_id),
    array(
        'where_tag' => "state",
        'where_value' => $role),
    );
    $object_camp = $this->Camp_sms->get($limit, 0, $where_array);
    foreach ($object_camp as $camp) {
        $table[] = array(
            $camp->name_camp,
            $camp->name_sender,
            $camp->date_created,
            $camp->contacts_count,
            $camp->date_start_send,
            '<a data-toggle="modal" id="modal-delete-camp-btn" data-target="#add-list-modal" data-id="' . $camp->sms_id . '"><i class="fa fa-trash-o"></i></a><a href="' . base_url() . 'index.php/sms/3/' . $camp->sms_id . '"><i class="fa fa-pencil-square-o"></i></a>',
            );
    }
    return $table;
}

private function get_campaigns_table_termine($role, $offset, $limit) {
    $this->load->model('Camp_sms');
    $table = array();
    $where_array = array(array(
        'where_tag' => "user_id",
        'where_value' => $this->user_id)
    );
    $object_camp = $this->Camp_sms->get($limit, 0, $where_array);
    foreach ($object_camp as $camp) {
        if($camp->id_envoie > 0){
            $result = $this->request($camp->id_envoie);
            if($result[0]['status'] === "sent" || $camp->state === "done" ){
                if($camp->state !== "done"){
                    $campaign = new Camp_sms();
                    $campaign->load($camp->sms_id);
                    $campaign->state = "done";
                    $campaign->update(); 
                    $user_id = $this->session_local['user_id'];
                    $this->load->model('user');
                    $user = new user();
                    $user->load($user_id);
                    if($camp->state === "progress"){
                        $user->nbr_sms = $user->nbr_sms + $result[0]['nbRefu'];
                    }else{
                        $user->nbr_sms = $user->nbr_sms - $result[0]['nbReceved'];
                    }
                    $user->update();
                }
                $table[] = array(
                    $camp->name_camp,
                    $camp->name_sender,
                    $result[0]['nbEnvoi'],
                    $result[0]['nbReceved'],
                    $camp->date_start_send,
                    '<a href="'.base_url('').'index.php/campaigns/statistique/'. $camp->id_envoie . '"><i class="fa fa-bar-chart"></i></a>'
                );
            }
        }
    }
    return $table;
}
private function get_campaigns_table_progress($role, $offset, $limit) {
    $this->load->model('Camp_sms');
    $table = array();
    $where_array = array(array(
        'where_tag' => "user_id",
        'where_value' => $this->user_id)
    );
    $object_camp = $this->Camp_sms->get($limit, 0, $where_array);
    foreach ($object_camp as $camp) {
        if($camp->id_envoie > 0){
            $result = $this->request($camp->id_envoie);
            $DetailDlr = $result[0]['DetailDlr'];
            $nbrNum = $result[0]['nbEnvoi']; 
            $progress = 0;
            for ($i=0; $i < $nbrNum; $i++) { 

                if($DetailDlr[$i]['dlr'] === "0" || $DetailDlr[$i]['dlr'] === "8"){                    
                    $progress = 1;
                }
            }
            if($progress === 1 || $camp->state === "progress" ){
                $user_id = $this->session_local['user_id'];
                $this->load->model('user');
                $user = new user();
                $user->load($user_id);
                if($camp->state !== "progress"){
                    $campaign = new Camp_sms();
                    $campaign->load($camp->sms_id);
                    $campaign->state = "progress";
                    $campaign->update();
                    
                    $user->nbr_sms = $user->nbr_sms - $result[0]['nbEnvoi'];
                    $user->update();
                }
                if( $camp->state === "progress" && $result[0]['status'] === "sent"){
                    $user->nbr_sms = $user->nbr_sms + $result[0]['nbRefu'];
                    $user->update();
                }
                
                $table[] = array(
                    $camp->name_camp,
                    $camp->name_sender,
                    $result[0]['nbEnvoi'],
                    $result[0]['nbReceved'],
                    $camp->date_start_send,
                    );
            }
        }
    }
    return $table;
}
private function get_campaigns_table_echec($role, $offset, $limit) {
    $this->load->model('Camp_sms');
    $table = array();
    $where_array = array(array(
        'where_tag' => "user_id",
        'where_value' => $this->user_id),
    array(
        'where_tag' => "state",
        'where_value' => $role),
    );
    $object_camp = $this->Camp_sms->get($limit, 0, $where_array);
    foreach ($object_camp as $camp) {
        $table[] = array(
            $camp->name_camp,
            $camp->name_sender,
            $camp->date_created,
            $camp->contacts_count,
            '<a data-toggle="modal" id="modal-delete-camp-btn" data-target="#add-list-modal" data-id="' . $camp->sms_id . '"><i class="fa fa-trash-o"></i></a><a href="' . base_url() . 'index.php/sms/3/' . $camp->sms_id . '"><i class="fa fa-pencil-square-o"></i></a>',
            );
    }
    return $table;
}
/* =========================================================================DELETE CAMP  */

/**
 * function view campaign  lists
 * @param type $role
 */
public function delete($id) {
    $camp = new Camp_sms();
    $camp->load($id);
    $camp->delete();
    $this->load->view("alert_view", array("message" => " Campagne a été supprimer.", 'alert' => 'alert-success'));
}

public function request($id){
    $this->load->model('Api_sms');
    $api_sms= new Api_sms();
    $api_sms->id_company = $id;
    $result = $api_sms->request();
    $request = json_decode($result,true);
    return $request;
}
public function statistique($id){
    $this->load->helper('url');
    $this->load->model('Camp_sms');
    $request = $this->request($id);
    $DetailDlr = $request[0]['DetailDlr'];
    if (!$this->session_local['logged_in']) {
        redirect('#');
    } else {
        $this->load->view('bootstrap/header');
        $this->load->view('campaigns_view/campaign_statistic', array('id_envoie' => $id , 'request'=> $request[0], 'detailDlr' => $DetailDlr));
        $this->get_footer();
    }
}

/* =========================================================================FUNCTIONS */

/**
 * get_footer
 */
private function get_footer() {
    $ajax_url = array(
        'ajax_url' => true,
        'object_services_urls' => json_encode(array()),
        'ajax_sript' => array('campaigns.js')
        );
    $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
}

}
