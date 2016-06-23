<?php

class Achats extends CI_Controller {

    public $session_local = array();

    public function __construct() {
        parent::__construct();
        $this->session_local = $this->session->userdata('session_login_in');

    }

    public function index() {
        $this->load->helper('url');

        if (!$this->session_local['logged_in']) {
            redirect('#');
        } else {
            $this->load->library('table');
            $this->load->view('bootstrap/header');
            $packs_table['packs_table'] = $this->get_packs();
            $this->load->view('Achat/packs_achat', $packs_table);
        
            $ajax_url = array(
                'ajax_url' => true,
                'object_services_urls' => json_encode(array()),
                'ajax_sript' => array('achat.js','libs/nouislider.min.js')
            );
            $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
        }
    }
    public function payment(){
        $this->load->helper('url');

        if (!$this->session_local['logged_in']) {
            redirect('#');
        } else {
            $this->load->view('bootstrap/header');
            $this->load->view('payment/paiement');
        
            $ajax_url = array(
                'ajax_url' => true,
                'object_services_urls' => json_encode(array()),
                'ajax_sript' => array('')
            );
            $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
        }
    }

    public function get_form($amount,$count,$id_pack){
        $this->load->model('User');
        $user = new User();
        $user->load($this->session_local['user_id']);
        $this->load->model('Commande');
        $commande = new Commande();
        $user_id = $this->session_local['user_id'];
        $date = date('Y-m-d H:i:s');
        $commande->user_id = $user_id;
        $commande->id_pack =$id_pack;
        $commande->date_commande = $date;
        $commande->amount = $amount;
        $commande->count = $count;
        $commande->state = 'saved';
        $orderID = $commande->insert();
        $amountt = $amount * 1000;
        $NumSite = 'MAR449';
        $Password = 'tc#mrO34';
        $Devise = 'TND';
        $signature = sha1($NumSite.$Password.$orderID.$amountt.$Devise);

        $this->load->model('Pack');
        $pack = new Pack();
        $pack->load($id_pack);
        if((int)$pack->price  === (int)$amount / (int)$count || $id_pack === '0') {            
            echo '<FORM name="paiment" id="paiment_form" method="POST" action="https://www.gpgcheckout.com/Paiement/Validation_paiement.php" >
                   <input type="hidden" name="NumSite" value="'.$NumSite.'">
                   <input type="hidden" name="Password" value="'.md5($Password).'">
                   <input type="hidden" name="orderID"  value="'.$orderID.'">
                   <input type="hidden" name="Amount" value="'.$amountt.'">
                   <input type="hidden" name="Currency" value="'.$Devise.'">
                   <input type="hidden" name="Language" value="fr">
                   <input type="hidden" name="EMAIL" value="'.$user->mail.'"> 
                   <input type="hidden" name="CustLastName" value="'.$user->l_name.'">
                   <input type="hidden" name="CustFirstName" value="'.$user->f_name.'">
                   <input type="hidden" name="CustAddress" value="">
                   <input type="hidden" name="CustZIP" value="">
                   <input type="hidden" name="CustCity" value="">
                   <input type="hidden" name="CustCountry" value="">
                   <input type="hidden" name="CustTel" value="'.$user->phone_number.'">
                   <input type="hidden" name="PayementType" value="1">
                   <input type="hidden" name="MerchandSession" value="">
                   <input type="hidden" name="orderProducts" value="Pack '.$id_pack .' * '. $count.'">
                   <input type="hidden" name="signature" value="'.$signature.'">
                   <input type="hidden" name="AmountSecond" value="">
                   <input type="hidden" name="vad" value="254200003">
                   <input type="hidden" name="Terminal" value="003">
                   <input type="hidden" name="TauxConversion" value=" ">
                   <input type="hidden" name="BatchNumber" value=" ">
                   <input type="hidden" name="MerchantReference" value=" ">
                   <input type="hidden" name="Reccu_Num" value="">
                   <input type="hidden" name="Reccu_ExpiryDate " value="">
                   <input type="hidden" name="Reccu_Frecuency " value="">
                </FORM>';
        }else{
            echo "<p style='color: #FF5959;'>Une erreur s'est produite ! </p>";
        }
    }

    public function paiement_process() {
        $state = $this->input->post('TransStatus');
        $PAYID = $this->input->post('PAYID');
        $this->load->model('pack');
        $this->load->model('User');
        $this->load->model('Commande');
        $commande = new Commande(); 
        $commande->load($PAYID);

        switch ($state) {
        case '00':
            $commande->state = 'Success';
            $commande->update();
            $pack = new Pack();
            if($commande->id_pack != 0){
                $pack->load($commande->id_pack);
                $nbr_sms = $pack->nbr_sms * $commande->count;
                $user = new User();
                $user->load($commande->user_id);
                $user->nbr_sms += $nbr_sms;
                $user->update(); 
            }else{
                $nbr_sms = $commande->amount * 20;
                $user = new User();
                $user->load($commande->user_id);
                $user->nbr_sms += $nbr_sms;
                $user->update(); 
            }            
            break;
        case '05':
            $commande->state = 'Refused';
            $commande->update();
            break;
        case '06':
            $commande->state = 'Canceled';
            $commande->update();
            break;
        case '07':
            $commande->state = 'Refunded';
            $commande->update();
            break;
        case '08':
            $commande->state = 'ChargeBack';
            $commande->update();
            break;
        }

        /*$this->load->model('Test');
        $test = new Test();        
        $test->orderId = $PAYID;
        $test->state = $state;
        $test->insert();*/
    }

    public function paiement_OK() {
        $this->load->helper('url');
        $this->load->view('bootstrap/header');
        $this->load->view('Achat/paiement_OK');
        
        $ajax_url = array(
            'ajax_url' => true,
            'object_services_urls' => json_encode(array()),
            'ajax_sript' => array()
        );
        $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
    }

    public function paiement_KO() {
        $this->load->helper('url');
        $this->load->view('bootstrap/header');
        $this->load->view('Achat/paiement_KO');
        
        $ajax_url = array(
            'ajax_url' => true,
            'object_services_urls' => json_encode(array()),
            'ajax_sript' => array()
        );
        $this->load->view('bootstrap/footer', array("ajax_url" => $ajax_url));
    }

    public function get_packs() {
        $table = array();
        $this->load->model('Pack');
        $packs = $this->Pack->get();        
        return $packs;
    }
    public function buy_pack($id_pack){
        $this->load->model('User');
        $user = new User();
        $user->load($this->session_local['user_id']);
        
        $this->load->model('Pack');
        $pack = new Pack();
        $pack->load($id_pack);
        
        $user->nbr_sms =$user->nbr_sms + $pack->nbr_sms;
        $user->update();
        $this->index();
    }
}
