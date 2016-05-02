<?php

/**
 * Myfina Model Class
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

class Myfinamodel extends CI_Model {

 	   function  __construct()
    {     
        parent::__construct();
		$this->dbprefix = $this->db->dbprefix;
		

    }
 /**
  * @author Kamaro Lambert & Jamalukhair
  * @method to return the budget_summary
  * @return unknown
  */
    function budget_summary()
    {
		
		$sql = "SELECT a.cname AS child_name, 
				       a.cid   AS child_id,
				       c.cname AS parent_name, 
				       COALESCE( c.cid, 0 ) AS parent_id,
        		       a.camount,
				       a.cincome, 
				       a.cbalance as cbalance 
				FROM `".$this->dbprefix."category` a 
				LEFT OUTER JOIN `".$this->dbprefix."category_rel` b ON a.cid = b.child_id 
				LEFT OUTER JOIN `".$this->dbprefix."category` c ON b.parent_id = c.cid
				 where a.ctype = 'b' ORDER BY a.cincome desc, c.cname,a.cname";
		$query = $this->db->query($sql);
		$data['new'] = $query->result_array();
		return $data;
    }
	
 
    function get_user($login)
    {
		$query = $this->db->query("SELECT userid FROM user where login = '$login'");		
        $data = $query->row_array();
		if ($data) return $data['userid'];
    }
 /**
  * @author Kamaro Lambert & Jamalukhair
  * @method to return the budget_summary
  * @return varchar
  * @params int cid
  * 
  */
    function get_category_name($cid)
    {
		$query = $this->db->query("SELECT cname FROM `".$this->dbprefix."category` where cid = '$cid'");		
        $data = $query->row_array();
		if ($data) return $data['cname'];
    }

    /**
     * @author Kamaro Lambert & Jamalukhair
     * @method to return the the category information
     * @return strint
     * @params int cid
     *
     */
    function get_category_info($cid)
    {
		$query = $this->db->query("SELECT * FROM `".$this->dbprefix."category` where cid = '$cid'");		
        $data = $query->row_array();
		if ($data) return $data;
    }
    /**
     * @author Kamaro Lambert & Jamalukhair
     * @method to return the transaction info
     * @return array
     * @params int transactionid
     *
     */
	function get_transaction_info($tid)
	{
		$this->db->select('*');
		$this->db->from($this->dbprefix.'transaction');
		$this->db->where("tid", $tid);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
	}
	
    function update_last_login($insert)
    {
		//$this->db->where('uid', $insert['uid']);
		//$this->db->where('pkey', $insert['pkey']);
		//$this->db->update('profile', $insert); 
    }

	function create_base_tables()
	{
		if (!$this->db->table_exists('account_type'))
		{
			$sql2 = "CREATE TABLE IF NOT EXISTS `account_type` (
					`acc_id` tinyint(10) NOT NULL auto_increment,
					`acc_name` varchar(20) NOT NULL,
					`acc_credit` tinyint(1) NOT NULL default '0',
					PRIMARY KEY  (`acc_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;
				";
			$this->db->query($sql2);	
			$sql3 = "INSERT INTO `account_type` (`acc_id`, `acc_name`, `acc_credit`) VALUES 
					(1, 'Cash', 0),
					(2, 'Saving', 0),
					(3, 'Investment', 0);
				";
				//(4, 'Liability', 1);
			$this->db->query($sql3);	
		}
		$sql4 = "CREATE TABLE IF NOT EXISTS `profile` (
			`uid` int(7) NOT NULL,
			`pkey` varchar(30) NOT NULL,
			`pvalue` varchar(30) NOT NULL,
			PRIMARY KEY  (`uid`,`pkey`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;
			";
		$this->db->query($sql4);	

	}
	
	function user_tables_exist($username)
	{
		if ($this->db->table_exists($username."_category"))
		{
			return TRUE;
		}
		else return FALSE;
	}

    function create_user_tables()
    {
		if (!$this->db->table_exists('account_type'))
		{
			$sqlc = "CREATE TABLE IF NOT EXISTS `profile` (
				`uid` int(7) NOT NULL,
				`pkey` varchar(30) NOT NULL,
				`pvalue` varchar(30) NOT NULL,
				PRIMARY KEY  (`uid`,`pkey`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;
				";
			$this->db->query($sqlc);	
		}
		$sql1 = "CREATE TABLE `".$this->dbprefix."category` (
				`cid` bigint(10) NOT NULL auto_increment,
				`cname` varchar(100) NOT NULL,
				`camount` float(9,2) NOT NULL default '0.00',
				`cbalance` float(9,2) NOT NULL default '0.00',
				`cincome` tinyint(1) NOT NULL,
				`ctype` varchar(1) NOT NULL,
				`cparent` varchar(1) NOT NULL,
				PRIMARY KEY  (`cid`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;
				";
		$this->db->query($sql1);
		
		$sql3 = "CREATE TABLE `".$this->dbprefix."category_rel` (
				`child_id` bigint(10) NOT NULL,
				`parent_id` bigint(10) default NULL,
				PRIMARY KEY  (`child_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;
				";
		$this->db->query($sql3);

		$sql4 = "CREATE TABLE `".$this->dbprefix."transaction` (
				`tid` bigint(20) NOT NULL auto_increment,
				`tdesc` varchar(100) NOT NULL,
				`tamount` float(9,2) NOT NULL,
				`tdate` date NOT NULL,
				`trelation` int(9) NOT NULL,
				`tmemo` text NOT NULL,
				PRIMARY KEY  (`tid`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;
				";
		$this->db->query($sql4);
		$sql5 = "CREATE TABLE `".$this->dbprefix."tran_relation` (
				`tr_id` int(7) NOT NULL auto_increment,
				`tr_cid` int(7) NOT NULL,
				`tr_tranid` int(7) NOT NULL,
				`tr_couple` int(7) NOT NULL,
				`tr_amount` float(9,2) NOT NULL,
				`tr_index` int(7) NOT NULL,
				`tr_acbalance` float(9,2) NOT NULL,
				PRIMARY KEY  (`tr_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;
				";
			$this->db->query($sql5);
		$sql6 = "CREATE TABLE `".$this->dbprefix."predefined` (
				`pid` int(3) NOT NULL auto_increment,
				`ptfrom` int(3) NOT NULL,
				`ptto` int(3) NOT NULL,
				`ptdesc` varchar(100) NOT NULL,
				`ptamount` float(9,2) NOT NULL,
				`ptmemo` text NOT NULL,
				PRIMARY KEY  (`pid`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;
				";
		$this->db->query($sql6);
    }

    function create_profile($insert)
    {
		$this->create_user_tables();
		//create sample budget items
		$parent_id = $this->insert_budget_item("INCOME","b",1);
			$this->insert_budget_item("Salary","b",1,3000,$parent_id);
			$this->insert_budget_item("Investment Income","b",1,0,$parent_id);
			$this->insert_budget_item("Internet Income","b",1,200,$parent_id);
			$this->insert_budget_item("Other Income","b",1,0,$parent_id);
		$parent_id = $this->insert_budget_item("EXPENSES","b",0);
			$parent_id2 = $this->insert_budget_item("Education","b",0,0,$parent_id);
				$this->insert_budget_item("Stationary","b",0,20,$parent_id2);
				$this->insert_budget_item("Tution Fee","b",0,80,$parent_id2);
			$parent_id2 = $this->insert_budget_item("Auto","b",0,0,$parent_id);
				$this->insert_budget_item("Fuel","b",0,300,$parent_id2);
				$this->insert_budget_item("Maintenance","b",0,100,$parent_id2);
			$parent_id2 = $this->insert_budget_item("Daily Expenses","b",0,0,$parent_id);
				$this->insert_budget_item("Lunch","b",0,300,$parent_id2);
			$parent_id2 = $this->insert_budget_item("Utility Bills","b",0,0,$parent_id);
				$this->insert_budget_item("Electricity","b",0,100,$parent_id2);
				$this->insert_budget_item("Water","b",0,30,$parent_id2);
				$this->insert_budget_item("Telephone (Fixed)","b",0,100,$parent_id2);
				$this->insert_budget_item("Telephone (Handphone)","b",0,60,$parent_id2);
				$this->insert_budget_item("Internet","b",0,77,$parent_id2);
			$parent_id2 = $this->insert_budget_item("Loans","b",0,0,$parent_id);
				$this->insert_budget_item("Car Loan","b",0,500,$parent_id2);
				$this->insert_budget_item("Housing Loan","b",0,700,$parent_id2);
		//create sample account items
		$this->add_account(array("cname" => "My Wallet", "cparent" => 1, "ctype" => "a"));
		$this->add_account(array("cname" => "Bank ABC Saving Account", "cparent" => 2, "ctype" => "a"));
		$this->add_account(array("cname" => "Unit Trust XYZ", "cparent" => 3, "ctype" => "a"));
		//return $id;
		
    }

    function insert_budget_item($cname,$ctype,$cincome,$camount=0,$parent_id=null)
    {
		$insert = array("cname" => $cname, "ctype" => $ctype, "cincome" => $cincome, "camount" => $camount);
		$this->db->insert($this->dbprefix.'category', $insert);
		$child_id = $this->db->insert_id();
		$insert2 = array("parent_id" => $parent_id, "child_id" => $child_id);
		$this->db->insert($this->dbprefix.'category_rel', $insert2);
		return $child_id; 
    }

    function insert_transaction($insert)
    {
		$this->db->insert($this->dbprefix.'transaction', $insert);
		return $this->db->insert_id();
    }

    function insert_predefined($insert)
    {
		$this->db->insert($this->dbprefix.'predefined', $insert);
		return $this->db->insert_id();
    }

    function shift_index($tr_index, $tr_cid)
    {
		//$this->db->where("tr_index >= $tr_index");
		//$this->db->where("tr_cid", $tr_cid);
		//$this->db->update($this->dbprefix.'tran_relation', 'tr_index = tr_index + 1');
		$sql = "UPDATE ".$this->dbprefix."tran_relation set tr_index = tr_index + 1 where tr_index >= $tr_index and tr_cid = $tr_cid";
		$this->db->query($sql);
    }

    function shift_index_reduce($tr_index, $tr_cid)
    {
		$sql = "UPDATE ".$this->dbprefix."tran_relation set tr_index = tr_index - 1 where tr_index > $tr_index and tr_cid = $tr_cid";
		$this->db->query($sql);
    }

    function shift_next_data_balance($tr_index, $tr_cid, $tr_amount)
    {
		$sql = "UPDATE ".$this->dbprefix."tran_relation set tr_acbalance = tr_acbalance + $tr_amount where tr_index > $tr_index and tr_cid = $tr_cid";
		$this->db->query($sql);
    }

    function sum_acc_amount()
    {
		$this->db->select('tr_cid, sum(tr_amount) balance');
		$this->db->from($this->dbprefix.'tran_relation');
		$this->db->group_by("tr_cid");
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
    }

    function sum_budget_amount($month,$year)
    {
		$data = array();
		$this->db->select('tr_cid,sum(tr_amount) as month_total');
		$this->db->from($this->dbprefix.'transaction ,'.$this->dbprefix.'tran_relation');
		$this->db->where('tid = tr_tranid');
		$this->db->where("month(tdate) = $month");
		$this->db->where("year(tdate) = $year");
		$this->db->group_by("tr_cid");
		$query = $this->db->get();
		$aa = $query->result_array();
		foreach ($aa as $v){
			$k = $v['tr_cid'];
			$data['per_month'][$k] = $v['month_total'];
		}
		$this->db->select('tr_cid,sum(tr_amount) as overall');
		$this->db->from($this->dbprefix.'transaction ,'.$this->dbprefix.'tran_relation');
		$this->db->where('tid = tr_tranid');
		$this->db->group_by("tr_cid");
		$query = $this->db->get();
		$bb = $query->result_array();
		foreach ($bb as $v1){
			$k1 = $v1['tr_cid'];
			$data['overall'][$k1] = $v1['overall'];
		}
		return $data;
     }

    function update_acc_balance($cid)
    {
		$this->db->select('sum(tr_amount) cbalance');
		$this->db->from($this->dbprefix.'tran_relation');
		$this->db->where("tr_cid",$cid);
		$query = $this->db->get();
		$insert = $query->row_array();
		$this->db->where('cid', $cid);
		$this->db->update($this->dbprefix.'category', $insert);
    }

    function insert_tran_relation($insert)
    {
		$this->db->insert($this->dbprefix.'tran_relation', $insert);
		return $this->db->insert_id();
    }
	
    function highest_tr_index($cid, $date)
    {
		$this->db->select('max(tr_index) i');
		$this->db->from($this->dbprefix.'tran_relation , '.$this->dbprefix.'transaction');
		$this->db->where('tr_cid', $cid);
		$this->db->where('tid = tr_tranid');
		$this->db->where('tdate', $date);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data['i'];
    }
	
    function prev_data($cid, $date)
    {
		$this->db->select('tr_id,tr_index,tr_cid,tr_acbalance,tr_amount');
		$this->db->from($this->dbprefix.'tran_relation , '.$this->dbprefix.'transaction');
		$this->db->where('tr_cid', $cid);
		$this->db->where('tid = tr_tranid');
		$this->db->where("tdate <= '$date'");
		$this->db->order_by('tr_index desc');
		$this->db->limit(1);
		$query = $this->db->get();	
		$data = $query->row_array();
		return $data;
    }

    function add_account($insert)
    {
		$this->db->insert($this->dbprefix.'category', $insert);
    }

    function edit_budget_item($cid,$insert='',$parent=null)
    {
		if (is_array($insert)){
			$this->db->where('cid', $cid);
			$this->db->update($this->dbprefix.'category', $insert);
		}     
		if (is_array($parent)){
			$this->db->where('child_id', $cid);
			$this->db->update($this->dbprefix.'category_rel', $parent);
		}     
	}

    function edit_account($insert)
    {
		if (is_array($insert)){
			$this->db->where('cid', $insert['cid']);
			$this->db->update($this->dbprefix.'category', $insert);
		}
	}

    function update_transaction($insert)
    {
		if (is_array($insert)){
			$this->db->where('tid', $insert['tid']);
			$this->db->update($this->dbprefix.'transaction', $insert);
		}
	}

    function get_budget_item($cid)
    {
//		$query = $this->db->query("select cid,cname,cincome,camount,parent_id as cparent from budget left join budget_rel on cid=child_id where cid = '$cid'");		
		$this->db->select('cid,cname,cincome,camount,cbalance,parent_id as cparent');
		$this->db->from($this->dbprefix.'category');
		$this->db->join($this->dbprefix.'category_rel', 'cid = child_id', 'left');
		$this->db->where('cid', $cid);
		$query = $this->db->get();
		$data = $query->row_array();
		$this->db->select('child_id');
		$this->db->from($this->dbprefix.'category_rel');
		$this->db->where('parent_id', $cid);
		$this->db->limit(1);
		$query2 = $this->db->get();
		if($query2->row_array()) $data['gotchild'] = 1;
		return $data;
    }

    function get_account_item($cid)
    {
		$this->db->select('cid,cname,camount,cbalance,cparent,cname,acc_name,acc_credit');
		$this->db->from($this->dbprefix.'category,account_type');
		$this->db->where('acc_id = cparent');
		$this->db->where('cid', $cid);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
    }

    function get_tr_list($tid)
    {
		$this->db->select('tr_id,tr_index,tr_cid,tr_amount');
		$this->db->from($this->dbprefix.'tran_relation');
		$this->db->where('tr_tranid', $tid);
		$this->db->order_by('tr_amount');
		$query = $this->db->get();
		$data = $query->result_array();
		return $data;
    }
  /**
   * @author Kamaro Lambert
   * @name   list_accounts()
   * @param  array() $not_in_account
   * @return multitype:multitype: array
   */
    function list_accounts($not_in_account=null)
    {
		$data['asset'] = array();
		$data['liability'] = array();
		$this->db->select('cid,cname,camount,cbalance,cparent,cname,acc_name,acc_credit');
		$this->db->from($this->dbprefix.'category,account_type');
		//check if user has passed the acounts to ignore
		if($not_in_account!=null)
		{
			foreach ($not_in_account as $accounts=>$account_id)
			  {
			   $this->db->where("cid != $account_id");
			  }
		}
		
		$this->db->where('acc_id = cparent');
		$this->db->where('acc_credit', 0);
		$this->db->where('ctype', "a");
		$this->db->order_by('acc_name asc, cname asc'); 
		$query = $this->db->get();
		$data['asset'] = $query->result_array();
		$this->db->select('cid,cname,camount,cbalance,cparent,cname,acc_name,acc_credit');
		$this->db->from($this->dbprefix.'category,account_type');
		$this->db->where('acc_id = cparent');
		$this->db->where('acc_credit', 1);
		$this->db->where('ctype', "a");
		$this->db->order_by('acc_name asc, cname asc');
		$query = $this->db->get();
		$data['liability'] = $query->result_array();
        return $data;
	}

	/**
	 * @Author Kamaro Lambert
	 * @method list_accountsby_parent()
	 * @return int $parentid
	 */
	public function list_accountsby_parent($parentid=0)
	{
		$accounts_array=array();
		$this->db->select('cid,cname');
		$this->db->from($this->dbprefix.'category,account_type');
		$this->db->where('acc_id = cparent');
		$this->db->where('cparent',$parentid);
		$this->db->where('ctype', "a");
		$this->db->order_by('acc_name asc, cname asc');
		$query = $this->db->get();
		foreach ($query->result_array() as $accounts)
		{
			$accounts_array[$accounts['cid']]=$accounts['cname'];
		}
		return $accounts_array;
	}
	
	/**
	 * @Author Kamaro Lambert
	 * @name list_accountsby_parent()
	 * @method to get the name of the account
	 * @return int $parentid
	 */
	function list_accountsby_id($accid=0)
	{
		
		$this->db->select('cname');
		$this->db->from($this->dbprefix.'category');
		$this->db->where('cid',$accid);
		$query = $this->db->get();
		
		return $query->row();
	}
	
	
    function list_accounts_sum()
    {
		$data = array();
		$this->db->select('sum(camount)+sum(cbalance) as sum,acc_name');
		$this->db->from($this->dbprefix.'category,account_type');
		$this->db->where('acc_id = cparent');
		$this->db->where('acc_credit', 0);
		$this->db->where('ctype', "a");
		$this->db->group_by('cparent'); 
		$this->db->order_by('acc_name asc'); 
		$query = $this->db->get();
		$data = $query->result_array();
        return $data;
	}

    function list_transactions($cid='all',$tyear=0,$tmonth=0,$limit=20,$offset=0)
    {
		$this->db->select('tid,tdesc,tdate,tmemo,tr_cid, tr_index, tr_couple, tr_amount, tr_acbalance, a.cname as cname, a.ctype as ctype, b.cname as couplename, b.ctype as coupletype');
		$this->db->from($this->dbprefix.'transaction, '.$this->dbprefix.'tran_relation');
		$this->db->join($this->dbprefix.'category as a', 'tr_cid = a.cid', 'left');
		$this->db->join($this->dbprefix.'category as b', 'tr_couple = b.cid', 'left');
		$this->db->where('tid = tr_tranid');
		
		if ($tyear != 0) 
		{
			$this->db->where("year(tdate) = $tyear");
		}
		
		if ($tyear != 0 && $tmonth != 0) 
		{
			$this->db->where("month(tdate) = $tmonth");
		}
		if ($cid == 'all') 
		{
			$this->db->where('tr_amount > 0');
		}
		else 
		{
			$this->db->where('tr_cid', $cid);
		}
		$this->db->limit($limit,$offset);
		$this->db->order_by('tdate desc,tr_index desc'); 
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name   count_transactions()
	 * 
	 */
	function count_transactions($cid='all',$tyear=0,$tmonth=0)
	{
		$this->db->select('tid,tdesc,tdate,tmemo,tr_cid, tr_index, tr_couple, tr_amount, tr_acbalance, a.cname as cname, a.ctype as ctype, b.cname as couplename, b.ctype as coupletype');
		$this->db->from($this->dbprefix.'transaction, '.$this->dbprefix.'tran_relation');
		$this->db->join($this->dbprefix.'category as a', 'tr_cid = a.cid', 'left');
		$this->db->join($this->dbprefix.'category as b', 'tr_couple = b.cid', 'left');
		$this->db->where('tid = tr_tranid');
	
		if ($tyear != 0)
		{
			$this->db->where("year(tdate) = $tyear");
		}
	
		if ($tyear != 0 && $tmonth != 0)
		{
			$this->db->where("month(tdate) = $tmonth");
		}
		if ($cid == 'all')
		{
			$this->db->where('tr_amount > 0');
		}
		else
		{
			$this->db->where('tr_cid', $cid);
		}
		
		$this->db->order_by('tdate asc,tr_index asc');
		$query = $this->db->get();
		return $query->num_rows();
	}

    function minmax_year()
    {
		$this->db->select('MIN(YEAR(tdate)) as minyear, MAX(YEAR(tdate)) as maxyear');
		$this->db->from($this->dbprefix.'transaction');
		$query = $this->db->get();
		return $query->row_array();
	}

    function sum_amount($cid,$month=0,$year=0)
    {
		$this->db->select('SUM(tr_amount) as sum_amount');
		$this->db->from($this->dbprefix.'transaction, '.$this->dbprefix.'tran_relation');
		$this->db->where('tid = tr_tranid');
		$this->db->where('tr_cid',$cid);
		if ($month != 0 && $year != 0) {
			$this->db->where('year(tdate)',$year);
			$this->db->where('month(tdate)',$month);
		}
		$query = $this->db->get();
		$sum = $query->row_array();
		return $sum['sum_amount'];
	}

    function latest_balance($cid)
    {
		$this->db->select('tr_acbalance');
		$this->db->from($this->dbprefix.'transaction, '.$this->dbprefix.'tran_relation');
		$this->db->where('tid = tr_tranid');
		$this->db->where('tr_cid',$cid);
		$this->db->order_by('tdate', 'desc');
		$this->db->order_by('tid', 'desc');
		$this->db->limit(1,0);
		$query = $this->db->get();
		$sum = $query->row_array();
		
		return $sum['tr_acbalance'];
	}

    function list_transactions_bak($cid='all',$per_page,$start=0,$tyear=0,$tmonth=0)
    {
		$this->db->select('tid,tdesc,tdate,tmemo,tr_cid, tr_index, tr_couple, tr_amount, tr_acbalance, a.cname as cname, a.ctype as ctype, b.cname as couplename, b.ctype as coupletype');
		$this->db->from($this->dbprefix.'transaction, '.$this->dbprefix.'tran_relation');
		$this->db->join($this->dbprefix.'category as a', 'tr_cid = a.cid', 'left');
		$this->db->join($this->dbprefix.'category as b', 'tr_couple = b.cid', 'left');
		//$this->db->join($this->dbprefix.'budget as b1', 't_bgt_from = b1.cid', 'left');
		//$this->db->join($this->dbprefix.'budget as b2', 't_bgt_to = b2.cid', 'left');

		$this->db->where('tid = tr_tranid');
		if ($tyear != 0) $this->db->where("year(tdate) = $tyear");
		if ($tyear != 0 && $tmonth != 0) $this->db->where("month(tdate) = $tmonth");
		if ($cid == 'all') $this->db->where('tr_amount > 0');
		else $this->db->where('tr_cid', $cid);
		//,'.$this->dbprefix.'accounts,'.$this->dbprefix.'category'

		//$this->db->where('t_acc_from = cid');
		//$this->db->where('t_acc_to = cid');
		//$this->db->where('t_bgt_from = cid');
		//$this->db->where('t_bgt_to = cid');
		//$query = $this->db->get('dbmail_users', $per_page,$start);
		$this->db->order_by('tdate asc,tr_index asc'); 
		$this->db->limit($per_page, $start);
		$query = $this->db->get();
		return $query->result_array();
	}
	
    function count_transaction_row($cid = 'all')
    {
		$this->db->select('count(tid) as cnt');
		$this->db->from($this->dbprefix.'transaction, '.$this->dbprefix.'tran_relation');
		$this->db->where('tid = tr_tranid');
		if ($cid == 'all') $this->db->where('tr_amount > 0');
		else $this->db->where('tr_cid', $cid);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data['cnt'];
    }

    function is_exist_predefined($pid)
    {
		$this->db->select('count(pid) as cnt');
		$this->db->from($this->dbprefix.'predefined');
		$this->db->where('pid', $pid);
		$query = $this->db->get();
		$data = $query->row_array();
		if ($data['cnt'] > 0) return true;
		else return false;
    }

    function list_acc_type($acc_credit = 100)
    {
		$this->db->select('acc_id, acc_name, acc_credit');
		if ($acc_credit != 100) $this->db->where('acc_credit', $acc_credit);
		$query = $this->db->get('account_type');
        return $query->result_array();
	}

    function delete_tr($tr_id)
	{
		$this->db->where('tr_id', $tr_id);
		$this->db->delete($this->dbprefix.'tran_relation'); 
	}

    function delete_transaction($tid)
	{
		$this->db->where('tid', $tid);
		$this->db->delete($this->dbprefix.'transaction'); 
	}

    function delete_predefined($pid)
	{
		$this->db->where('pid', $pid);
		$this->db->delete($this->dbprefix.'predefined'); 
	}

	//function suggest_alias($search)
	//{
	//	$query = $this->db->query("SELECT distinct(alias) as suggest FROM dbmail_aliases WHERE alias like('" .$search . "%') ORDER BY alias");		
    //    return $query->result_array();
	//}

    function list_predefined()
    {
		$this->db->select('pid,ptdesc,ptfrom,ptto,ptamount,ptmemo,a.cname as ptfromname,b.cname as pttoname');
		$this->db->order_by('ptdesc');
		$this->db->from($this->dbprefix.'predefined');
		$this->db->join($this->dbprefix.'category as a', 'a.cid = ptfrom', 'left');
		$this->db->join($this->dbprefix.'category as b', 'b.cid = ptto', 'left');
		$query = $this->db->get();
        return $query->result_array();
	}

    function get_predefined($pid)
    {
		$this->db->select('pid,ptdesc,ptfrom,ptto,ptamount,ptmemo');
		$this->db->from($this->dbprefix.'predefined');
		$this->db->where('pid', $pid);
		$query = $this->db->get();
		$data = $query->row_array();
		return $data;
    }

    function update_predefined($insert)
    {
		$this->db->where("pid",$insert['pid']);
		$this->db->update($this->dbprefix.'predefined', $insert);
    }
    
    /**
     * @author Kamaro lambert
     * @name count_types
     * @method to count the current accounts types in the database
     * 
     */
    function count_types()
    {
    	$query=$this->db->get('account_type');
    	return $query->num_rows();
    }
    /**
     * @author Kamaro Lambert
     * @name get_acctypes
     * @method to get the accounts types
     */
    
    function get_acctypes($limit=10000, $offset=0,$id=null)
    {
    	$this->db->from('account_type');
    	
    	$this->db->order_by("acc_name", "asc");
    	$this->db->limit($limit);
    	$this->db->offset($offset);
    	
    	//Check if we need only one account
    	if($id==null)
    	{
    	 $query=$this->db->get();
    	 return $query->result();
    	}
    	else
    	 {
    	  $this->db->where('acc_id',$id);
    	  $query=$this->db->get();
    	  return $query->row();
    	}
    }
    /**
     * @author kamaro Lambert
     * @method to add or edit account type
     * @params array  $insert
     * @params int    $id 
     */
    function addtype($insert,$id=null)
    {
    	if (count($insert)>0 and $id==null) //if it's new account type
    	{
    	  return	$this->db->insert('account_type',$insert);
    	}
    	elseif(count($insert)>0 and $id!=null) //type update
    	{
    	  $this->db->where('acc_id',$id);
		 return $this->db->update('account_type', $insert); 
    	}
    	
    }
    
    

}

?>