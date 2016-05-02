<?php

/**
* SENDS EMAIL WITH GMAIL
*/
class Email extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index() 
	{	


		$config = Array(
		
				'protocol' => 'smtp',
		
				'smtp_host' => 'ssl://smtp.googlemail.com',
		
				'smtp_port' => 465,
		
				'smtp_user' => 'lambert@huguka.com',
		
				'smtp_pass' => 'kamalo2013',
		
				'mailtype' => 'html',
		
				'charset' => 'iso-8859-1',
		
				'wordwrap' => TRUE
		
		);
		
		$this->load->library('email', $config);
		
		$this->email->set_newline("\r\n");
		
		
		$this->email->from('dixanta@creatorsnepal.com');
		
		$this->email->to('kamaroly@gmail.com');
		
		$this->email->subject('This is an email test');
		
		$this->email->message('It is working. Great!');
		
		
		if($this->email->send())
		
		{
		
			echo 'Your email was sent, fool.';
		
		}
		
		
		else
		
		{
		
			show_error($this->email->print_debugger());
		
		}
		
		
	}
}

?>
      
