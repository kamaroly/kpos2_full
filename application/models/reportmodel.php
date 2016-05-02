<?php

/**
 * Report Model Class
 *
 * @package     MyFina
 * @subpackage  Models
 * @author      Jamalulkhair Khairedin (jalte)
 * @copyright   Copyright (c) 2007, www.ridinglinux.org
 * @license		http://myfina.ridinglinux.org/public/myfina_license.txt
 * @link 		http://myfina.ridinglinux.org
 * @version 	0.1
 *
 */

class Reportmodel extends CI_Model {

    function  __construct()
    {     
        parent::__construct();
		$CI =& get_instance();
		$this->domain = $CI->config->item('domain');
		$this->dbprefix = $this->db->dbprefix;
		$this->userid = 1;

    }


	function get_income_pie($month, $year)
	{
		$this->db->select('cname,sum(tr_amount) as amount');
		$this->db->from($this->dbprefix.'category');
		$this->db->from($this->dbprefix.'tran_relation');
		$this->db->from($this->dbprefix.'transaction');
		$this->db->where('cincome',1);
		$this->db->where('tr_cid = cid');
		$this->db->where('tr_tranid = tid');
		$this->db->where('month(tdate)', $month);
		$this->db->where('year(tdate)', $year);
		$this->db->group_by('cid');
		$this->db->order_by('amount','asc');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}
	
	function get_monthly_income_pie($month_start=01, $year_start=2006,$end_month=NULL,$end_year=null)
	{
		$this->db->select('cname,sum(tr_amount) as amount');
		$this->db->from($this->dbprefix.'category');
		$this->db->from($this->dbprefix.'tran_relation');
		$this->db->from($this->dbprefix.'transaction');
		$this->db->where('cincome',1);
		$this->db->where('tr_cid = cid');
		$this->db->where('tr_tranid = tid');
		$this->db->where('month(tdate)', $month);
		$this->db->where('year(tdate)', $year);
		$this->db->group_by('cid');
		$this->db->order_by('amount','asc');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function get_expenses_pie($month, $year, $limit=10)
	{
		$this->db->select('cname,cid,camount, sum(tr_amount) as amount');
		$this->db->from($this->dbprefix.'category');
		$this->db->from($this->dbprefix.'tran_relation');
		$this->db->from($this->dbprefix.'transaction');
		$this->db->where('cincome',0);
		$this->db->where('ctype','b');
		$this->db->where('tr_cid = cid');
		$this->db->where('tr_tranid = tid');
		$this->db->where('month(tdate)', $month);
		$this->db->where('year(tdate)', $year);
		$this->db->group_by('cid');
		$this->db->order_by('amount','desc');
		if ($limit != 'all') $this->db->limit($limit);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function get_budget($income = 'all')
	{
		$this->db->select('cname,cid,sum(tr_amount) as amount');
		$this->db->from($this->dbprefix.'category');
		$this->db->from($this->dbprefix.'tran_relation');
		$this->db->from($this->dbprefix.'transaction');
		$this->db->where('cincome',0);
		$this->db->where('ctype','b');
		$this->db->where('tr_cid = cid');
		$this->db->where('tr_tranid = tid');
		$this->db->where('month(tdate)', $month);
		$this->db->where('year(tdate)', $year);
		$this->db->group_by('cid');
		$this->db->order_by('amount','desc');
		if ($limit != 'all') $this->db->limit($limit);
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
	}

	function get_sum_income($month, $year)
	{
		$this->db->select('sum(tr_amount) as amount');
		$this->db->from($this->dbprefix.'category');
		$this->db->from($this->dbprefix.'tran_relation');
		$this->db->from($this->dbprefix.'transaction');
		$this->db->where('cincome',1);
		$this->db->where('ctype','b');
		$this->db->where('tr_cid = cid');
		$this->db->where('tr_tranid = tid');
		$this->db->where('month(tdate)', $month);
		$this->db->where('year(tdate)', $year);
		//$this->db->group_by('cid');
		//$this->db->order_by('amount','asc');
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

	function get_sum_expenses($month, $year)
	{
		$this->db->select('sum(tr_amount) as amount');
		$this->db->from($this->dbprefix.'category');
		$this->db->from($this->dbprefix.'tran_relation');
		$this->db->from($this->dbprefix.'transaction');
		$this->db->where('cincome',0);
		$this->db->where('ctype','b');
		$this->db->where('tr_cid = cid');
		$this->db->where('tr_tranid = tid');
		$this->db->where('month(tdate)', $month);
		$this->db->where('year(tdate)', $year);
		//$this->db->group_by('cid');
		//$this->db->order_by('amount','asc');
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}

}