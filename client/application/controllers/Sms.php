    <?php

    class Sms extends CI_Controller {

        public $session_local = array();
        public $camp_id =0;
        public $numbers_to_send = array();
        public $total_count_contact = 0;
        public $campaign_detail = array(
            "name" => '',
            "sender" => '',
            "sms" => '',
            "all_contacts" => False,
            "all_contacts_count" => 0,
            "contacts_count" => 0,
            "lists" => array(),
            );

    /**
     * construct
     */
    public function __construct() {
        parent::__construct();
        $this->session_local = $this->session->userdata('session_login_in');
        $this->total_count_contact = 0;
        $this->campaign_detail = $this->session->userdata('session_campaign');
        $this->load->library('form_validation');
    }

    /* ========================================================================== Index page */

    /**
     * Index page for web controller.
     */
    public function index($step = 1, $camp_id = 0) {
        $this->camp_id=$camp_id;
        if ($camp_id) {
            $this->save_campaign();
            $this->load_camp($camp_id);
        }
        
        $this->load->helper('url');
        $this->load->library(array('form_validation', 'table'));
        $step = $step;
        if (!$this->session_local['logged_in']) {
            redirect('#');
        } else {
            if (isset($_POST['campaign'])) {
                if ($this->step1_sms_sending($camp_id))
                    $step = 2;
            }elseif (isset($_POST['selected-count-contacts'])) {
                if ($this->step2_sms_sending($camp_id))
                    {$step = 3; }
                else
                    {$step = 2; }
            }elseif(isset($_POST['submit-step3-sms']))
            {   
                $date = date('Y-m-d H:i:s');
                $when = 1;
                if($this->input->post('check_date') === '1'){
                    $date = date($this->input->post('date_envoie'));
                    $when = 0;
                }
                $this->save_send($when,$date);
                return 0;
            }    
            $table_list = $this->get_lists($this->session_local['user_id']);
            $this->load->view('bootstrap/header');
            $list_sender = $this->get_list_sender($this->session_local['user_id']);
            $name = $this->campaign_detail['name'];
            $sender = $this->campaign_detail['sender'];
            $sms = $this->campaign_detail['sms'];
            if (isset($this->campaign_detail['contacts_count'])) {
                $contacts_count = $this->campaign_detail['contacts_count'];

            } else {
                $contacts_count = 0;
            }
            if ($step == 0) {
                $this->reset_data();
                redirect('/sms');
            }
            if ((!$contacts_count) && ($step == 3))
                $step = 2;
            if (!$name)
                $step = 1;
            if (isset($this->campaign_detail['all_contacts'])) {
                $all_contacts = $this->campaign_detail['all_contacts'] ? 'checked' : '';
                $all_contacts_count = $this->campaign_detail['contacts_count'] ? $this->campaign_detail['contacts_count'] : 0;
            } else {
                $all_contacts = 0;
                $all_contacts_count = 0;
            }
            switch ($step) {
                case 1:
                $this->load->view('sms_view/sms_view', array("list_sender" => $list_sender, "name" => $name, "sender" => $sender, "sms" => $sms, "total_count_step" => $contacts_count, "camp_id" => $camp_id));
                break;
                case 2:
                $this->load->view('sms_view/sms_step_2_view', array("lists" => $table_list, "total_count_contact" => $this->total_count_contact, "all" => $all_contacts, "all_count" => $all_contacts_count , "name" => $name, "sender" => $sender, "sms" => $sms, "total_count_step" => $contacts_count, "camp_id" => $camp_id));
                break;
                case 3:

                $this->load->view('sms_view/sms_step_3_view', array("name" => $name, "sender" => $sender, "sms" => $sms, "total_count_step" => $contacts_count, "camp_id" => $camp_id));
                break;
            }
        }
        $ajax_url = array(
            'ajax_url' => TRUE,
            'object_services_urls' => json_encode(array()),
            'ajax_sript' => array('libs/jquery.datetimepicker.full.js', 'sms.js')
            );
        $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
    }


    /*     * ****************************************************************************************load campaign */

    /**
     * 
     * @param int $camp_id
     */
    private function load_camp($camp_id) {
        $this->load->model('Camp_sms');
        $camp = new Camp_sms();
        $camp->load($camp_id);
        if (isset($camp->name_sender)) {
            $campaign_detail = array(
                "name" => $camp->name_camp,
                "sender" => $camp->name_sender,
                "sms" => $camp->sms_text,
                "all_contacts" => $camp->all_contacts,
                "all_contacts_count" => $camp->all_contacts_count,
                "contacts_count" => $camp->contacts_count,
                "lists" => json_decode($camp->lists, true), //form array
                );
            $this->campaign_detail = $campaign_detail;
        }
    }

    /* 0.01*****************************************************************************************Step 1 */

    /**
     * 
     * @param int $camp_id
     * @return boolean
     */
    public function step1_sms_sending($camp_id) {
        $this->form_validation->set_rules(
            array(
                array(
                    'field' => 'name-sender',
                    'label' => 'Expéditeur',
                    'rules' => 'required',
                    ), array(
                    'field' => 'campaign',
                    'label' => 'Campagne',
                    'rules' => 'required',
                    ), array(
                    'field' => 'sms-text',
                    'label' => 'SMS',
                    'rules' => 'required',)
                    ));
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {
            return false;
        } else {
            $save = $this->input->post('save');
            $this->campaign_detail['name'] = $this->input->post('campaign');
            $this->campaign_detail['sender'] = $this->input->post('name-sender');
            $this->campaign_detail['sms'] = $this->input->post('sms-text');
            $session_campaign = $this->campaign_detail;
            $this->session->set_userdata('session_campaign', $session_campaign);
            if ($save) {
                if ($camp_id) {
                    $this->update_campaign($camp_id);
                } else {
                    $this->save_campaign();
                }
                $this->reset_data();
                redirect('campaigns', 'refesh');
            }
            return true;
        }
    }

    /* 0.02*****************************************************************************************Step 2 */

    /**
     * 
     * @param int $camp_id
     * @return boolean
     */
    public function step2_sms_sending($camp_id) {
        $this->form_validation->set_rules(
            array(
                array(
                    'field' => 'selected-count-contacts',
                    'label' => '',
                    'rules' => 'required|callback_check_number',
                    )
                ));
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        if (!$this->form_validation->run()) {
            return FALSE;
        } else {
            $all_contacts_count = $this->input->post('all-contacts');
            $lists = $this->input->post('chk[]');
            $lists_count = $this->input->post('list-count[]');
            $lists_max = $this->input->post('list-max[]');
            $count_contacts = $this->input->post('count-contacts');
            $number_list = sizeof($lists);

            if ($all_contacts_count) {
                $this->clear_session_list();
                $this->update_session(TRUE, $count_contacts);
                $this->numbers_to_send=array();

                $res = $this->save_all_lists($lists, $lists_max);
                $this->update_session(TRUE, $count_contacts);
                shuffle($res);
                $res = array_slice($res, 0, $count_contacts );
                ////////////////////////////////////debug
                foreach ($res as $row) {
                    //echo $this->console("all-numbers", $row);
                    $this->numbers_to_send[]=$row;
                }
            } else {
                $this->clear_session_list();
                $this->campaign_detail['lists'] = array();
                $total_count = 0;
                $this->numbers_to_send=array();
                foreach ($lists as $key => $value) {
                    $count = $lists_count[$value];
                    $max = $lists_max[$value];
                    $id_list = $lists[$key];
                    $this->save_lists($id_list, $count);

                    if ($count) {
                        if ($count != $max) {
                            $total_count+=$count;
                        } else {
                            $total_count+=$count;
                        }
                    }
                }

                $this->update_session(FALSE, 0, $total_count);
            }
            $save = $this->input->post('save');
            if ($save) {
                if ($camp_id) {
                    $this->update_campaign($camp_id);
                } else {
                    $this->save_campaign();
                }

                $this->reset_data();
                redirect('campaigns', 'refesh');
            }
            return TRUE;
        }
    }
    /**
     * generate_contacts_form_list
     */
    private function generate_contacts_form_list() {
        $all_contacts = $this->campaign_detail['all_contacts'] ;
        $count_contacts = $this->campaign_detail["contacts_count"];
        $lists = $this->campaign_detail['lists'];
        $res=array();
        if ($all_contacts) {
            $this->numbers_to_send=array();
            
            foreach ($lists as $row) {
                $ar = $this->get_contacts_list_random($row["id"], 0, FALSE);
                $res = array_merge($res, $ar);
            }
            shuffle($res);
            $res = array_slice($res, 0, $count_contacts );
            foreach ($res as $row) {
                $this->numbers_to_send[]=$row;
            }
        } else {
            foreach ($lists as  $row) {
                $this->numbers_to_send=array_merge($this->numbers_to_send,$this->get_contacts_list_random($row["id"], $row["count"], TRUE));
            }
        }
    }

    /**
     * clear_session_list
     */
    private function clear_session_list() {
        $this->campaign_detail['lists'] = array();
        $this->update_session();
    }

    /**
     * save_all_lists contacts
     * @param array $list
     * @param array $lists_max
     * @return array Description
     */
    private function save_all_lists($lists, $lists_max) {
        $res = array();
        foreach ($lists as $key => $value) {
            $max = $lists_max[$value];
            $id_list = $lists[$key];
            if ($max) {
                $this->save_lists($id_list, $max);
            }
        }
        return $res;
    }

    /**
     * save_lists
     * @param type $id
     * @param type $count
     */
    private function save_lists($id, $count) {
        $this->campaign_detail['lists'][] = array('id' => $id, 'count' => $count);
    }

    /**
     * update_session
     */
    private function update_session($all = FALSE, $all_contacts_count = 0, $contacts_count = 0) {

        $this->campaign_detail['all_contacts'] = $all;

        if ($all)
            $this->campaign_detail['contacts_count'] = $all_contacts_count;
        else
            $this->campaign_detail['contacts_count'] = $contacts_count;

        $this->session->set_userdata('session_campaign', $this->campaign_detail);
    }

    private function get_lists($id_user, $list_id = "") {
        $this->load->model('List_contact');
        $lists_table = array();
        //$list=$campaign_detail['list'];
        $ar_where = array(array('where_tag' => "user_id" ,'where_value' =>  $id_user));
        $c_view = $this->List_contact->get(0, 0, $ar_where);
        foreach ($c_view as $list) {
            $checked = '';
            $id = $list->list_id;
            $count_number = $this->get_number_rows('Contact', "list_id", $id);
            $this->total_count_contact += $count_number;
            $count = $this->slected_list($id);
            if ($count) {
                $checked = 'checked';
                if ($count > $count_number)
                    $count = $count_number;
            }
            $lists_table[] = array(
                '<input type="checkbox" class="list-contact" name="chk[]" value="' . $id . '"  id="' . $id . '" ' . $checked . ' > <label for="' . $id . '" class="lab-liste"> ' . $list->name . '</label> ',
                '<input class="form-control number-of-list number" data-id="' . $id . '"  name="list-count['.$id.']" value="' . $count . '" data-max="' . $count_number . '" readonly> / <input name="list-max['.$id.']" value=' . $count_number . ' class="form-control count-list" readonly>'
                );
        }
        return $lists_table;
    }

    private function slected_list($id) {
        $lists = isset($this->campaign_detail['lists']) ? $this->campaign_detail['lists'] : array();
        if (sizeof($lists))
            foreach ($lists as $list) {
                if ($list['id'] == $id)
                    return $list['count'];
            }
            return 0;
        }

    /**
     * @param sting $model_name
     * @param string $key_id
     * @param int $list_id
     * @return int
     */
    private function get_number_rows($model_name, $key_id, $list_id) {
        $this->load->model($model_name);
        $ar_where = array(array(
            'where_tag' => $key_id ,
            'where_value' => $list_id),
        array( 'where_tag' => 'trash' ,
            'where_value' => FALSE
            ));
        $number_rows = $this->Contact->get_count($ar_where);
        return $number_rows;
    }

    /**
     * get lists  of names_sender
     * @param int id
     * @return lists String
     */
    private function get_list_sender($id_user) {
        $this->load->model('Name_sender');
        $ar_where = array(array(
            'where_tag' =>"user_id" ,
            'where_value' => $id_user
            ));
        $senders = $this->Name_sender->get(0, 0, $ar_where);
        $options = '';
        foreach ($senders as $sender) {
            $options.='<option>' . $sender->name_sender . '</option>';
        }
        return $options;
    }

    /**
     * get contact list
     * @param int id_list
     * @param int count limit
     * @param Boolean random 
     */
    private function get_contacts_list_random($id_list, $count, $random) {
        $this->load->model('Contact');
        $ar_where = array(array(
            'where_tag' =>'list_id' ,
            'where_value' => $id_list),
        array('where_tag' =>'trash',
            'where_value' => FALSE
            ));
        $res = array();
        $objects = $this->Contact->get_random_php("phone_number", 0, 0, $ar_where, $count, $random);
        foreach ($objects as $row) {
            $res[] = $row->phone_number;
        }
        return $res;
    }

    /*  0.c2    * ************************************************* Rules Callback  step 2 * */

    public function check_number() {
        $use_all_contacts = $this->input->post('all-contacts');
        $count_contacts = $this->input->post('count-contacts');
        $selected_contacts = $this->input->post('selected-count-contacts');
        $error_msg = "Veuillez vérifier le nombre de contacts sélectionnés!";
        if ($use_all_contacts) {
            if (!$count_contacts) {
                $this->form_validation->set_message('check_number', $error_msg);
                return FALSE;
            }
        } else {
            if (!$selected_contacts) {
                $this->form_validation->set_message('check_number', $error_msg);
                return FALSE;
            }
        }
        return TRUE;
    }

    /*  0.04   * *****************************************************************************************Step 4 */

    /**
     * 
     * @param int $camp_id
     */
    private function update_campaign($camp_id) {
        if ($this->campaign_detail['name'] != '') {
            $this->load->model('Camp_sms');
            $camp_sms = new Camp_sms();
            $camp_sms->load($camp_id);
            $camp_sms->user_id = $this->session_local['user_id'];
            $camp_sms->name_camp = $this->campaign_detail['name'];
            $camp_sms->name_sender = $this->campaign_detail['sender'];
            $camp_sms->sms_text = $this->campaign_detail['sms'];
            $camp_sms->date_created = date("Y-m-d H:i:s");
            $camp_sms->state = "saved";
            if (isset($this->campaign_detail['lists'])) {
                if (sizeof($this->campaign_detail['lists'])) {
                    $camp_sms->lists = json_encode($this->campaign_detail['lists']);
                    $camp_sms->contacts_count = $this->campaign_detail['contacts_count'];
                    $camp_sms->all_contacts = $this->campaign_detail['all_contacts'];
                    $camp_sms->all_contacts_count = $this->all_contacts_count['sms'];
                }
            }
            $camp_sms->update();
        }
    }

    private function save_campaign() {
        if ($this->campaign_detail['name'] != '') {
            $this->load->model('Camp_sms');
            $camp_sms = new Camp_sms();
            $camp_sms->user_id = $this->session_local['user_id'];
            $camp_sms->name_camp = $this->campaign_detail['name'];
            $camp_sms->name_sender = $this->campaign_detail['sender'];
            $camp_sms->sms_text = $this->campaign_detail['sms'];
            $camp_sms->date_created = date("Y-m-d H:i:s");
            $camp_sms->state = "saved";
            $camp_sms->id_envoie = 0;
            if (isset($this->campaign_detail['lists'])) {
                if (sizeof($this->campaign_detail['lists'])) {
                    $camp_sms->lists = json_encode($this->campaign_detail['lists']);
                    $camp_sms->contacts_count = $this->campaign_detail['contacts_count'];
                    $camp_sms->all_contacts = $this->campaign_detail['all_contacts'];
                    //$camp_sms->all_contacts_count = $this->campaign_detail['all_contacts_count'];
                }

                //echo $this->camp_id;
                if(!$this->camp_id){
                    $camp_sms->insert();
                    return $this->db->insert_id();
                }else{
                    $camp_sms->sms_id=$this->camp_id;
                    $camp_sms->update();
                    return $this->camp_id;
                }
            }
        }
    }

    /* 0.00*****************************************************************************************function */

    private function console($name, $value) {
        echo'<script>console.log("' . $name . '","' . $value . '")</script>';
    }

    private function reset_data() {
        if ($this->session->userdata('session_campaign')) {
            $this->session->unset_userdata('session_campaign');
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function envoie_test(){
        $num = $this->input->get('num');
        $campaign = $this->input->get('campaign');
        $oadc = $this->input->get('oadc');
        $msg = $this->input->get('msg');
        $date = date('Y-m-d H:i:s');

        $this->load->model('Api_sms');
        $api_sms= new Api_sms();
        $api_sms->msg = $msg;
        $api_sms->oadc = $oadc;
        $api_sms->listClient = $num;
        $api_sms->campanyName = $campaign;
        $api_sms->when = 1;
        $api_sms->date = $date;
        $result = $api_sms->envoie();
        $resultt = json_decode($result,true);
        if( $resultt['status'] === 'ok'){
            $api_sms->id_company = $resultt['content'];
            $request = $api_sms->request();
            $reponse = json_decode($request,true);
            if($reponse[0]['nbRefu'] === 0){
                echo "<p style='color : green;'>Votre sms test a été envoyé avec succès.</p>";
            }else{
                echo "<p style='color : red;'>Votre sms test n'a pas été envoyé, veuillez tester avec un autre numéro.</p>";
            }
        }else{
            echo "<p style='color : red;'>Une erreur s'est produite veuillez réessayer plus tard.</p>";
        }
    }

    public function resend($id,$nums){  
        //$id_envoi = $this->input->post('id');
        //$nums = $this->input->post('nums');
        echo $id.' '.$nums;
    }

    public function envoieSMS($msg, $oadc, $listClient, $camp_name, $when, $date, $id_camp){   
        $this->load->model('Api_sms');

        $api_sms= new Api_sms();
        $api_sms->msg = $msg;
        $api_sms->oadc = $oadc;
        $api_sms->listClient = $listClient;
        $api_sms->campanyName = $camp_name;
        $api_sms->when = $when;
        $api_sms->date = $date;
        $result = $api_sms->envoie();
        $resultt = json_decode($result,true);
        if($resultt['status'] === 'ko'){
            $this->load->model('Camp_sms');
            $camp = new camp_sms();
            $camp->load($id_camp);
            $camp->state = 'echec';
            $camp->update();
            return false;
        }else{
            $this->load->library('session');
            $this->load->model('camp_sms');
            $date = date('Y-m-d H:i:s');
            $camp = new Camp_sms();
            $camp->load($id_camp);
            $camp->id_envoie = $resultt['content']; 
            $camp->date_start_send = $date;
            if($when == 0){
                $camp->state = "progress";
            }
            $camp->update();
            return true;
        }
    }
    /*     * ****************************************************************************************load save & send */

    /**
     */
    private function save_send($when,$date_envoie) {
        $this->generate_contacts_form_list();
        $oadc = $this->campaign_detail['sender'];
        $name = $this->campaign_detail['name'];
        $msg = $this->campaign_detail['sms'];
        $all_contacts_count = $this->campaign_detail['contacts_count'] ;
        $id_camp = $this->save_campaign();
        $listClient ="";
        foreach ($this->numbers_to_send as $key => $value) {
            if($key == 0){
                $listClient = $value;
            }else{
                $listClient = $listClient.','.$value;
            }
        }

        /*  Test sur le nombre des sms  */
        $user_id = $this->session_local['user_id'];
        $this->load->model('user');
        $user = new user();
        $user->load($user_id);
        if($all_contacts_count > $user->nbr_sms){
            $this->load->view('bootstrap/header');
            $this->load->view("alert_view", array("message" => " Votre solde des sms est insuffisant.", 'alert' => 'alert-danger'));
            $this->get_footer();
            return false;
        }

        $this->load->view('bootstrap/header');
        if($this->envoieSMS($msg, $oadc, $listClient , $name, $when,$date_envoie,$id_camp)){
            if($when == 0){
                $user_id = $this->session_local['user_id'];
                $this->load->model('user');
                $user = new user();
                $user->load($user_id);
                $user->nbr_sms = $user->nbr_sms - $all_contacts_count;
                $user->update();
            }
            $this->load->view("alert_view", array("message" => " Votre Campagne a été envoyée.", 'alert' => 'alert-success')); 
            redirect('campaigns');
        }else{
            $this->load->view("alert_view", array("message" => " Une erreur s'est produite.", 'alert' => 'alert-danger'));
            redirect('campaigns');
        }
        $this->get_footer();
        
    }
    private function get_footer() {
        $ajax_url = array(
            'ajax_url' => true,
            'object_services_urls' => json_encode(array()),
            'ajax_sript' => array()
            );
        $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
    }
}
