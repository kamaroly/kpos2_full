<?php
class Secure_area extends CI_Controller 
{
	/*
	Controllers that are considered secure extend Secure_area, optionally a $module_id can
	be set to also check if a user can access a particular module in the system.
	*/
	function __construct($module_id=null)
	{
		parent::__construct();	
		$filename = APPPATH.'config/database.php';
		if (file_exists($filename))
		{
			// or your date as well
			$now = time(); // or your date as well
		
			$your_date = filemtime($filename);
			$datediff = $now - $your_date;
			$days=floor($datediff/(60*60*24));
			/**	
			if ($days>30)
			{
				die('TRIAL VERSION  HAS EXPIRED CLICK HERE TO RENEW IT.');
			}
		   **/
		}
		$this->load->model('Employee');
		if(!$this->Employee->is_logged_in() AND $this->uri->segment(1)!='guest')
		{
			  redirect('login');
		
		}
		if(!$this->Employee->has_permission($module_id,$this->Employee->get_logged_in_employee_info()->person_id) and $cash_module!='cash/report' )
		{
			redirect('no_access/'.$module_id);
		}
		
		//load up global data
		$logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
		$data['allowed_modules']=$this->Module->get_allowed_modules($logged_in_employee_info->person_id);
		$data['user_info']=$logged_in_employee_info;
		
		
		$this->data['allowed_modules']=$data['allowed_modules'];
		$this->data['user_info']=$logged_in_employee_info;
		
				
		$this->load->vars($data);
	}



}
?>