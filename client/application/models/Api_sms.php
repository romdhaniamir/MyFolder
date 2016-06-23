<?php

class Api_sms extends MY_Model {

    private $login = 'cherif.helmi@gma';
    private $password = 'bv9hx20f';

    public $msg;
    public $oadc;
    public $listClient;
    public $companyName;
    public $when;
    public $date;
    
    public $id_company;

    public function envoie(){
		$wsdl = "http://www.orientmobile.com.tn/ws/wsPartner/partner.wsdl";
   	 	$server = new SoapClient($wsdl,array('encoding' => 'UTF-8'));
    	$client = $server->envoieSMS($this->login,$this->password,$this->msg,$this->oadc,$this->listClient,$this->companyName,$this->when,$this->date);
    	return $client;
    }
    public function request(){
		$wsdl = "http://www.orientmobile.com.tn/ws/wsPartner/partner.wsdl";
    	$server = new SoapClient($wsdl,array('encoding' => 'UTF-8'));
    	$client = $server->getDlr($this->login,$this->password,$this->id_company);
    	return $client;
    }	
}