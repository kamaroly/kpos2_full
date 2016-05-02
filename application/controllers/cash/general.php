<?php

/**
 * General Controller Class
 *
 * @package     MyFina
 * @subpackage  Controllers
 * @author      Jamalulkhair Khairedin (jalte)
 * @copyright   Copyright (c) 2007, www.ridinglinux.org
 * @license		http://myfina.ridinglinux.org/public/myfina_license.txt
 * @link 		http://myfina.ridinglinux.org
 * @version 	0.1
 *
 */
require_once (APPPATH."controllers/secure_area.php");
class General extends secure_area
 {

	var $datestring = "Year: %Y Month: %m Day: %d - %h:%i %a";

  function  __construct()
    {     
        parent::__construct();
	    $time = time();
	    $this->today_date = mdate($this->datestring, $time);

		

		$CI =& get_instance();
		
		$this->baseurl = base_url();
		$this->show_debug = $CI->config->item('show_debug');
		$this->myfina_version = $CI->config->item('myfina_version');
		if ($CI->config->item('index_page') == '') {
			$this->basepath = '';
		}
		else {
			$this->basepath = base_url().$CI->config->item('index_page');
		}	
	}

	function index()
	{
		$data['body'] = $this->load->view('cash/home', '', TRUE);
		$data['page_title'] = "Home";
		$this->load->view('cash/page', $data);

	}
	
	function credit()
	{
		$data = array();
		$data['page_title'] = "Credits";
		$data['body'] = $this->load->view('cash/credit', $data, TRUE);
		$this->load->view('cash/page', $data);
	}
	
	function contact()
	{
		$data = array();
		$data['page_title'] = "Contact Us";
		$data['content'] = "For any type of enquiries, please email to <br><br><img src=\"/images/contact.gif\" alt=\"Contact Us\">";
		$data['body'] = $this->load->view('cash/generic', $data, TRUE);
		$data['top_menu'] = $this->load->view('cash/top_menu','',TRUE);
		$data['left_menu'] = $this->load->view('cash/left_menu', '', TRUE);
		$this->load->view('cash/page', $data);
	}

	function userinfo($userid)
	{
		if (!is_numeric($userid)) { 
			$msg = "Invalid User";
			flashMsg($msg);
			redirect('', 'location');
		}
		$data['user_prof'] = $this->Malayolgamodel->user_profile($userid);
		if (!$data['user_prof']) { 
			$msg = "Invalid User";
			flashMsg($msg);
			redirect('', 'location');
		}
		$this->load->helper( 'gravatar' ); 
		$data['song_list'] = $this->Malayolgamodel->get_song_list_user($userid);
		$data['video_list'] = $this->Malayolgamodel->get_video_list_user($userid, TRUE);

		$data['page_title'] = "Member Info - ".$data['user_prof']['user_name'];
		$data['body'] = $this->load->view('cash/public_profile', $data, TRUE);
		$data['top_menu'] = $this->load->view('cash/top_menu','',TRUE);
		$data['left_menu'] = $this->load->view('cash/left_menu', '', TRUE);
		$this->load->view('cash/page', $data);
	}
	
	function contributors()
	{
		$data['top_tabber'] = $this->Malayolgamodel->top_tabber();
		$data['top_video_contributor'] = $this->Malayolgamodel->top_video_contributor();
		$this->load->helper( 'gravatar' ); 
		$data['page_title'] = "Top Contributors";
		$data['body'] = $this->load->view('cash/contributors', $data, TRUE);
		$data['top_menu'] = $this->load->view('cash/top_menu','',TRUE);
		$data['left_menu'] = $this->load->view('cash/left_menu', '', TRUE);
		$this->load->view('cash/page', $data);
	}
	
}
?>