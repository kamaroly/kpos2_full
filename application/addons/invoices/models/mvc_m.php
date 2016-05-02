<?php
Class Mvc_m extends CI_Model
{
	function get_mvc()
	{
		$query=$this->db->get('mvc');
		return $query->result();
	}
}