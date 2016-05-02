<?php

class Migrate extends CI_Controller
{
	public  function __construct()
	{
		parent::__construct();
		$this->load->library('migration');
	}
	
    public function index()
    {
    	
        if ($this->migration->latest() === FALSE)
        {
               echo $this->migration->error_string();
        }
        else
        {
        	$this->load->helper('URL');
        	echo 'Migration done successfully. Click '.anchor('home','Home').' to continue';
        }
    }
     
    
    
    public function down()
    {
    	$this->migration->version();
    }
}