<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	} 

	

	function new_camp($company= '' ,$email_user='',$name_compagne='',$email='')
	{
		$email_msg		=	"Nouvelle compagne sms !<br />";
		$email_msg		.=	"raison sociale :".$company."<br />";
		$email_msg		.=	"email : ".$email_user."<br />";
		$email_msg		.=	"nom de la campange : ".$name_compagne."<br />";
		$email_sub		=	"Nouvelle compagne (mysm.tn System)";
		$email_to		=	$email;


		$this->do_email($email_msg , $email_sub , $email_to);
	}
	function new_test($name_user= '' ,$email_user='',$name_compagne='',$email='')
	{
		$email_msg		=	"Nouvelle compagne sms !<br />";
		$email_msg		.=	"user name ".$name_user."<br />";
		$email_msg		.=	"email : ".$email_user."<br />";
		$email_msg		.=	"nom de la campange : ".$name_compagne."<br />";
		$email_sub		=	"Nouvelle compagne (mysm.tn System)";
		$email_to		=	'dahmen.com@gmail.com';


		$this->do_email($email_msg , $email_sub , $email_to);
	}

	
	
	/***custom email sender****/
	function do_email($msg=NULL, $sub=NULL, $to=NULL, $from=NULL)
	{
		$config = array();
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mail.mysms.tn';
		$config['smtp_port'] = '25';
		$config['smtp_timeout'] = '30';
		$config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';

		$this->load->library('email');

		$this->email->initialize($config);

		$system_name	=	'Mysms.tn';
		$from="sales@mysms.tn";
		$this->email->from($from, $system_name);
		$this->email->from($from, $system_name);
		$this->email->to($to);
		$this->email->subject($sub);
		
		
		$this->email->message($msg);
		
		$this->email->send();
		
	echo $this->email->print_debugger();
	}
}

