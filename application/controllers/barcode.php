<?php
class Barcode extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{		
		$this->load->view('barcode');
	}	
	
	function test($name=null,$frequency=null,$program=null,$time=null,$days=array())
	{
		$this->load->library('MY_scheduler');
		$this->my_scheduler->create_task($name,$frequency,$program,$time,$days);
		//$this->my_scheduler->delete_task($name);
		//$this->my_scheduler->modify_task($name,$frequency,$program,$time,$days);
		
	}
public function export()
 {
	$this->load->library('Excel_lib');

	$sql = $this->db->get('sales');

	$this->excel_lib->filename = 'sales';
	$this->excel_lib->make_from_db($sql);
}
}
?>