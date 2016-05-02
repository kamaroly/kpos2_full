<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Thickbox extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		//load ci url helper
	}

	public function index()
	{
		$this->load->view('thickbox_view');
		//load views
	}
}

/* End of file thickbox.php */
/* Location: ./application/controllers/thickbox.php */
?>