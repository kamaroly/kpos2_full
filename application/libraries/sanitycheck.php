<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sanitycheck Library Class
 *
 * @package     MyFina
 * @subpackage  Libraries
 * @author      Jamalulkhair Khairedin (jalte)
 * @copyright   Copyright (c) 2007, www.ridinglinux.org
 * @license		http://myfina.ridinglinux.org/public/myfina_license.txt
 * @link 		http://myfina.ridinglinux.org
 * @version 	0.1
 *
 */

class Sanitycheck
{

	function Sanitycheck()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Myfinamodel');

	}

	function check_profile($username) {
		$this->CI->Myfinamodel->create_base_tables();
		if(!$this->CI->Myfinamodel->user_tables_exist($username)){
			$this->CI->Myfinamodel->create_profile($username);
		}
	}
}
?>