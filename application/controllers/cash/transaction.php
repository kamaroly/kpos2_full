<?php

/**
 * Transaction Controller Class
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
class Transaction extends secure_area {

	var $datestring = "%D %d %M %Y - %h:%i %a"; 

function  __construct()
    {     
        parent::__construct();
        //Setting the global variables to be used
		$time = time();
	    $this->today_date = mdate($this->datestring, $time);
	    $this->dbprefix = $this->db->dbprefix;
	    $this->userid = 1;

	    //Loading libraries
	    $this->load->library('session');
	    
	    //Setting the validation rules for Transaction
	    //============================================
	    $this->transaction_validation_rules = array(
	    		array(
	    				'field' => 'tdesc',
	    				'label' => 'Description',
	    				'rules' => 'required|trim'
	    		),
	    		array(
	    				'field' => 'tamount',
	    				'label' => 'Amount',
	    				'rules' => 'required|numeric'
	    		),
	    		array(
	    				'field' => 'year',
	    				'label' => 'Year',
	    				'rules' => 'callback_valid_date'
	    		),
	    		array(
	    				'field' => 'tfrom',
	    				'label' => 'Transaction from',
	    				'rules' => 'callback_cannot_same'
	    		),
	    );
	    
	    $rules['ptdesc'] = "required|trim";
	    $rules['ptamount'] = "required|numeric";
	    $rules['tfrom'] = "callback_cannot_same";
	    
	    
	    //Setting predefined validation rules
	    $this->predefined_validation_rules = array(
	    		array(
	    				'field' => 'ptdesc',
	    				'label' => 'Description',
	    				'rules' => 'required|trim'
	    		),
	    		array(
	    				'field' => 'ptamount',
	    				'label' => 'Amount',
	    				'rules' => 'required|numeric'
	    		),
	    		array(
	    				'field' => 'tfrom',
	    				'label' => 'Transaction from',
	    				'rules' => 'callback_p_cannot_same'
	    		),
	    );
	    
	}

	
	function index()
	{
		redirect('/cash/accountssummary', 'location');
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name additem()
	 * @method to add new transaction item
	 *
	 */
	function additem($cid="all")
	{
		//load libraries...
		$this->load->library('Form_validation');
		$this->load->library('formdate');
		
		//Loading Models...
	    $this->load->model('Myfinamodel', '', TRUE);
		
		//Assign to variable
		$formdate = new FormDate();
		$formdate->setLocale('nl_BE');
		$formdate->year['start'] = 2006;
		$formdate->year['end'] = DATE('Y');
		$formdate->month['values'] = 'numbers';
		$formdate->day['selected'] = $this->input->post('day');
		$formdate->month['selected'] = $this->input->post('month');
		$formdate->year['selected'] = $this->input->post('year');
		$data['cid'] = $cid;
		$data['formdate'] = $formdate; 
		$data['page_title'] = 'Add Transaction';
		
		$data['predefined']       = $this->Myfinamodel->list_predefined();
		$data['budget']           = $this->Myfinamodel->budget_summary();
		$data['tree']             = $this->_getTree( $data['budget']['new'], 0 );
		$data['tree_table']       = $this->_arrayTree($data['tree']);
		$data['accounts']         = $this->Myfinamodel->list_accounts();
		$data['post'] = isset($_POST) ? $_POST : '';
		$data['table']['tr_cid1'] = $cid;
		$data['table']['tr_cid2'] = $cid;
		
		//Setting form validation rules
		$this->form_validation->set_rules($this->transaction_validation_rules);
		 
		//Run the validation
	    if ($this->form_validation->run() == FALSE)
	    { 
	    	//Something goes wrong			
			$data['body'] = $this->load->view('cash/transaction/add_item', $data, TRUE);
			
		    $this->load->view('cash/page', $data);
	    }
	    else
		{  
			
			//All seems to be well...
			$this->_insert_transaction($_POST); //Saving to the database
			//Redirecting to the page
			redirect('/cash/transaction/summary/'.$cid, 'location');
	    }
	}

	/**
	 * @author Kamaro Lambert
	 * @name edititem
	 * @method to edit budget
	 * @param int $tid
	 * @param int $cid
	 *
	 */
	function edititem($tid,$cid)
	{
		//load libraries...
		$this->load->library('Form_validation');
		$this->load->library('formdate');
		
		//Loading Models...
	    $this->load->model('Myfinamodel', '', TRUE);
		
	    //Assign value  to the variables
    	$data['tid'] = $tid;
		$data['cid'] = $cid;
		
		$data['table'] = $this->_get_tran_info($tid); 
		$data['predefined'] = $this->Myfinamodel->list_predefined();
		$formdate = new FormDate();
		$formdate->setLocale('nl_BE');
		$formdate->year['start'] = 2006;
		$formdate->year['end'] = DATE('Y');
		$formdate->month['values'] = 'numbers';
		$formdate->day['selected'] = $this->input->post('day') ? $this->input->post('day') : $data['table']['day'];
		$formdate->month['selected'] = $this->input->post('month') ? $this->input->post('month') : $data['table']['month'];
		$formdate->year['selected'] = $this->input->post('year') ? $this->input->post('year') : $data['table']['year'];
		$data['formdate'] = $formdate; 
		$data['page_title'] = 'Add Transaction';
		
		$data['budget'] = $this->Myfinamodel->budget_summary();
		$data['tree'] = $this->_getTree( $data['budget']['new'], 0 );
		$data['tree_table'] = $this->_arrayTree($data['tree']);
		$data['accounts'] = $this->Myfinamodel->list_accounts();
		
		//Setting form validation rules
		$this->form_validation->set_rules($this->transaction_validation_rules);
		 
		//Run the validation 
	    if ($this->form_validation->run() == FALSE)
	    {   //Something went wrong
		    
	    	//Go back to the form
			$data['body'] = $this->load->view('cash/transaction/add_item', $data, TRUE);
		    $this->load->view('cash/page', $data);
	    }
	    else
		{
			$table = $data['table'];
			$newdate = $this->input->post('year')."-".$this->input->post('month')."-".$this->input->post('day');
			
			//if it involve chages of date & accounts, do delete and new insertion
			if ($newdate != $table['tdate'] || $this->input->post('tfrom') != $table['tr_cid1'] || $this->input->post('tto') != $table['tr_cid2'] || $this->input->post('tamount') != $table['tamount']){
				$this->deleteitem($tid,0);
				$this->_insert_transaction($_POST);
			}
			
			else {
				$insert['tid'] = $tid;
				$insert['tdesc'] = $this->input->post('tdesc');
				$insert['tmemo'] = $this->input->post('tmemo');
				$this->Myfinamodel->update_transaction($insert);
				
			}
			redirect('/cash/transaction/summary/'.$table['tr_cid1'], 'location');
			
	    }
	}

	/**
	 * @author Kamaro Lambert
	 * @name   addpredefined
	 * @method to edit budget
	 * @param  int $tid
	 * @param  int $cid
	 *
	 */
	function addpredefined()
	{
		//load libraries...
		$this->load->library('Form_validation');
			
		//Loading Models...
	    $this->load->model('Myfinamodel', '', TRUE);
		
	    //Assign value to their variables
		$data['page_title'] = 'Add Predefined Transaction';		
	   	$data['budget'] = $this->Myfinamodel->budget_summary();
		$data['tree'] = $this->_getTree( $data['budget']['new'], 0 );
		$data['tree_table'] = $this->_arrayTree($data['tree']);
		$data['accounts'] = $this->Myfinamodel->list_accounts();
		$data['post'] = isset($_POST) ? $_POST : '';
		
		//Set validation rules
		$this->form_validation->set_rules($this->predefined_validation_rules);
		//Run validation
		if ($this->form_validation->run() == FALSE)
	    {//Something goes wrong..
	    	//Go back to the form
			$data['body'] = $this->load->view('cash/transaction/add_predefined', $data, TRUE);
		    $this->load->view('cash/page', $data);
	    }
	    else
		{//All seem to go  well
			
			$this->Myfinamodel->insert_predefined($_POST);//Save to the database
			
			redirect('/cash/transaction/listpredefined/', 'location');
	    }
	}

	/**
	 * @author Kamaro Lambert
	 * @name   addpredefined
	 * @method to edit budget
	 * @param  int $pid
	 *
	 */
	function editpredefined($pid)
	{

		//load libraries...
		$this->load->library('Form_validation');
			
		//Loading Models...
		$this->load->model('Myfinamodel', '', TRUE);
	    if (!$this->Myfinamodel->is_exist_predefined($pid)) { redirect('/cash/transaction/listpredefined/', 'location');}
		
	    //Assign value to their variables
		$data['page_title'] = 'Edit Predefined Transaction';
		$data['table'] = $this->Myfinamodel->get_predefined($pid);
		$data['budget'] = $this->Myfinamodel->budget_summary();
		$data['tree'] = $this->_getTree( $data['budget']['new'], 0 );
		$data['tree_table'] = $this->_arrayTree($data['tree']);
		$data['accounts'] = $this->Myfinamodel->list_accounts();
		
		$data['post'] = isset($_POST) ? $_POST : '';
		
	    //Set validation rules
		$this->form_validation->set_rules($this->predefined_validation_rules);
		//Run validation
		if ($this->form_validation->run() == FALSE)
	    {//Something goes wrong..
	    	//Go back to the form
			
			$data['body'] = $this->load->view('cash/transaction/add_predefined', $data, TRUE);
		    $this->load->view('cash/page', $data);
	    }
	    else
		{//All goes well....
			$insert = $_POST;
			$insert['pid'] = $pid;
			$this->Myfinamodel->update_predefined($insert);
			redirect('/cash/transaction/listpredefined/', 'location');
	    }
	}

	function _get_tran_info($tid)
	{
	    $this->load->model('Myfinamodel', '', TRUE);
		$data['ori'] = $this->Myfinamodel->get_transaction_info($tid);
		$data['related'] = $this->Myfinamodel->get_tr_list($tid);
		$data['result']['tamount'] = $data['ori']['tamount'];
		$data['result']['tdate'] = $data['ori']['tdate'];
		$data['result']['tdesc'] = $data['ori']['tdesc'];
		$data['result']['tmemo'] = $data['ori']['tmemo'];
		$i = explode('-',$data['result']['tdate']);
		$data['result']['year'] = $i[0];
		$data['result']['month'] = $i[1];
		$data['result']['day'] = $i[2];
		$data['result']['tr_id1'] = $data['related'][0]['tr_id'];
		$data['result']['tr_id2'] = $data['related'][1]['tr_id'];
		$data['result']['tr_cid1'] = $data['related'][0]['tr_cid'];
		$data['result']['tr_cid2'] = $data['related'][1]['tr_cid'];
		return $data['result'];
	}
	
	function summary($offset=0,$cid='all')
	{
		$this->load->model('Myfinamodel');
		$this->load->library('formdate');
		
		
		
		$data['page_title'] = 'My Transactions';
		$config['base_url'] = site_url("transaction/summary/".$cid."/");
		$data['minmax_year'] =  $this->Myfinamodel->minmax_year();
		
		$info = $this->Myfinamodel->get_category_info($cid);
		if (is_array($info)) foreach ($info as $k => $v){ if ($k != 'cid') $data[$k] = $v; }

		$formdate = new FormDate();
		//$formdate->setLocale('_BE');
		$formdate->year['start'] = $data['minmax_year']['minyear'];
		$formdate->year['end'] = $data['minmax_year']['maxyear'];
		$formdate->month['values'] = 'string';
		$formdate->month['selected'] = $data['msel'] = $this->input->post('month') != null ? $this->input->post('month') : date("n");
		$formdate->year['selected'] = $data['ysel'] = $this->input->post('year') != null ? $this->input->post('year') : date("Y");
		$formdate->config['blank'] = 1;
		$data['formdate'] = $formdate; 

	
		if (isset($data['ctype']) && $data['ctype'] == "b"){
			$data['tmonth'] = isset($_POST['month']) ? $_POST['month'] : date("n");
			$data['tyear'] = isset($_POST['year']) ? $_POST['year'] : date("Y");
		}
		else {
			$formdate->month['selected'] = $data['msel'] = $data['tmonth'] = isset($_POST['month']) ? $_POST['month'] : 0;
			$formdate->year['selected'] = $data['ysel'] = $data['tyear'] = isset($_POST['year']) ? $_POST['year'] : 0;
		}

		//Pagination configurations
		$config['base_url'] = site_url('/cash/transaction/summary');
		$config['total_rows'] =$this->Myfinamodel->count_transactions($cid,$data['tyear'],$data['tmonth']);
		
		$config['per_page'] = '20';
		$config['uri_segment'] = $offset;
		$this->pagination->initialize($config);
		
		$data['table'] = $this->Myfinamodel->list_transactions($cid,$data['tyear'],$data['tmonth'],$config['per_page'],$config['uri_segment']);
		
		$data['cid'] = $cid;
		//$data['sum_amount'] = $this->Myfinamodel->sum_amount($cid,$data['tmonth'],$data['tyear']);
		if ($cid == 'all') $data['sum_amount'] = 0;
		elseif ($data['ctype'] == 'a') $data['sum_amount'] = $this->Myfinamodel->latest_balance($cid);
		elseif ($data['ctype'] == 'b') $data['sum_amount'] = $this->Myfinamodel->sum_amount($cid,$data['tmonth'],$data['tyear']);

		//$data['paginating'] = $this->pagination->create_links();
		if ($cid == 'all') $data['body'] = $this->load->view('cash/transaction/summary_all', $data, TRUE);
		//elseif ($data['ctype'] == 'b' && $data['cincome'] == 1) $data['body'] = $this->load->view('cash/transaction/summary_budget_income', $data, TRUE);
		//elseif ($data['ctype'] == 'b' && $data['cincome'] == 0) $data['body'] = $this->load->view('cash/transaction/summary_budget_spend', $data, TRUE);
		elseif ($data['ctype'] == 'b') $data['body'] = $this->load->view('cash/transaction/summary_budget', $data, TRUE);
		else $data['body'] = $this->load->view('cash/transaction/summary', $data, TRUE);
		$this->load->view('cash/page', $data);
	}

	function summary_with_pagination($cid='all')
	{
		$this->load->model('Myfinamodel', '', TRUE);
		$this->load->library('pagination');
		$data['page_title'] = 'My Transactions';
		$config['base_url'] = $this->basepath."/transaction/summary/".$cid."/";
		//$config['total_rows'] = $this->Myfinamodel->count_transaction_row($cid);
		//$config['per_page'] = '5'; //todo: make it stored in user profile table
		//$config['uri_segment'] = 4;
		//$this->pagination->initialize($config);
		//$data['pag_config'] = $config; 
		$data['table'] = $this->Myfinamodel->list_transactions($cid,$config['per_page'],$this->uri->segment(4));
		$data['cid'] = $cid;
		$info = $this->Myfinamodel->get_category_info($cid);
		if (is_array($info)) foreach ($info as $k => $v){ if ($k != 'cid') $data[$k] = $v; }
		$data['paginating'] = $this->pagination->create_links();
		if ($cid == 'all') $data['body'] = $this->load->view('cash/transaction/summary_all', $data, TRUE);
		elseif ($data['ctype'] == 'b' && $data['cincome'] == 1) $data['body'] = $this->load->view('cash/transaction/summary_budget_income', $data, TRUE);
		elseif ($data['ctype'] == 'b' && $data['cincome'] == 0) $data['body'] = $this->load->view('cash/transaction/summary_budget_spend', $data, TRUE);
		else $data['body'] = $this->load->view('cash/transaction/summary', $data, TRUE);
		$this->load->view('cash/page', $data);
	}
	
	function listpredefined()
	{
		$this->load->model('Myfinamodel', '', TRUE);
		$data['predefined'] = $this->Myfinamodel->list_predefined();
		$data['page_title'] = 'Predefined Transactions';
		$data['body'] = $this->load->view('cash/transaction/listpredefined', $data, TRUE);
		$this->load->view('cash/page', $data);
	}

	function deletepredefined($pid)
	{
	    $this->load->model('Myfinamodel', '', TRUE);
	    if (!$this->Myfinamodel->is_exist_predefined($pid)) 
	    { 
	    	redirect('/cash/transaction/listpredefined/', 'location');
	    }
		$this->Myfinamodel->delete_predefined($pid);
		redirect('/cash/transaction/listpredefined/', 'location');
	}

	function _getTree( $relationships, $parent_id ) 
	{ 
		$children = array(); 
		foreach( $relationships as $key=>$val ) 
		{ 
			if( $val['parent_id'] == $parent_id ) 
			{ 
				unset( $relationships[$key] ); 
				$child = array( 'nav_name' => $val['child_name'], 'nav_id' => $val['child_id'], 'camount' => $val['camount'], 'cincome' => $val['cincome'], 'nav_children' => array() ); 
				$child['nav_children'] = $this->_getTree( $relationships, $val['child_id'] ); 
				if( count($child['nav_children']) == 0 ) { 
					unset( $child['nav_children'] ); 
				} array_push( $children, $child ); 
			} 
		} 
		return $children; 
	}

	function _arrayTree($tree=array(),$level=0) {
	  if( !is_int( $level ) ) {
		$level = 0;
	  }
	  $level++;
	  foreach( $tree as $key=>$val ) {
		$i = $val['nav_id'];
		$table[$i]['cid'] = $val['nav_id'];
		$table[$i]['cname'] = $val['nav_name'];
		$table[$i]['camount'] = $val['camount'];
		$table[$i]['cincome'] = $val['cincome'];
		$table[$i]['lvl'] = $level;
		if( isset($val['nav_children']) ) {
			$table[$i]['gotchild'] = 1;
			$table[$i]['btotal'] = $val['camount'];
			$data = $this->_arrayTree( $val['nav_children'], $level+1 );
			foreach ($data as $k => $v){
				$a = $v['cid'];
				$table[$a]['cid'] = $v['cid'];
				$table[$a]['cname'] = $v['cname'];
				$table[$a]['camount'] = $v['camount'];
				$table[$a]['lvl'] = $v['lvl'];
				$table[$a]['cincome'] = $v['cincome'];
				$table[$a]['gotparent'] = 1;
				$table[$i]['btotal'] = $table[$i]['btotal'] + $v['camount'];
			}
		}
	  }
	  return $table;
	}

    function sort_table($table,$column)
    {
		$this->load->helper('url');
		$this->session->set_userdata($table, $column);
		redirect($this->session->userdata('session_referer'), 'location');
    }

	function deleteitem($tid, $redirect = 1)
	{
	    $this->load->model('Myfinamodel', '', TRUE);
		//todo, the making sure process 
		$related = $this->Myfinamodel->get_tr_list($tid);
		foreach ($related as $v){
			$this->Myfinamodel->shift_next_data_balance($v['tr_index'], $v['tr_cid'], 0 - $v['tr_amount']);	
			$this->Myfinamodel->shift_index_reduce($v['tr_index'], $v['tr_cid']);
			$this->Myfinamodel->delete_tr($v['tr_id']);
			$this->Myfinamodel->update_acc_balance($v['tr_cid']);
		} 
		$this->Myfinamodel->delete_transaction($tid);
		if($redirect == 1) {redirect('cash/transaction/summary', 'location');}
	}


    function _insert_transaction($data)
    {
		$insert['tdesc']   = $data['tdesc'];
		$insert['tamount'] = $data['tamount'];
		$insert['tmemo']   = $data['tmemo'];
		$insert['tdate']   = $data['year']."-".$data['month']."-".$data['day'];
		
		$tid = $this->Myfinamodel->insert_transaction($insert);
		
		$prev_data1 = $this->Myfinamodel->prev_data($data['tfrom'],$insert['tdate']);
		
		if(!isset($prev_data1['tr_index'])) $prev_data1['tr_index'] = 0;
		$tr_index = $prev_data1['tr_index'] + 1; 
		$insert2['tr_cid'] = $data['tfrom'];
		$insert2['tr_tranid'] = $tid;
		$insert2['tr_amount'] = 0 - $data['tamount'];
		$insert2['tr_index'] = $tr_index;
		$insert2['tr_couple'] = $data['tto'];
		
		if (!isset($prev_data1['tr_acbalance'])) $prev_data1['tr_acbalance'] = 0;
		
		$insert2['tr_acbalance'] = $prev_data1['tr_acbalance'] + $insert2['tr_amount'];
		$this->Myfinamodel->shift_index($tr_index, $data['tfrom']);
		$this->Myfinamodel->insert_tran_relation($insert2);
		$this->Myfinamodel->shift_next_data_balance($tr_index, $data['tfrom'], $insert2['tr_amount']);
		$this->Myfinamodel->update_acc_balance($data['tfrom']);
		
		
		$prev_data2 = $this->Myfinamodel->prev_data($data['tto'],$insert['tdate']);
		if (!isset($prev_data2['tr_index'])) $prev_data2['tr_index'] = 0;
		$tr_index = $prev_data2['tr_index'] + 1;
		$insert3['tr_cid'] = $data['tto'];
		$insert3['tr_tranid'] = $tid;
		$insert3['tr_amount'] = $data['tamount'];
		$insert3['tr_index'] = $tr_index;
		$insert3['tr_couple'] = $data['tfrom'];
		if (!isset($prev_data2['tr_acbalance'])) $prev_data2['tr_acbalance'] = 0;
		$insert3['tr_acbalance'] = $prev_data2['tr_acbalance'] + $insert3['tr_amount'];
		$this->Myfinamodel->shift_index($tr_index, $data['tto']);
		$this->Myfinamodel->insert_tran_relation($insert3);
		$this->Myfinamodel->shift_next_data_balance($tr_index, $data['tto'], $insert3['tr_amount']);
		$this->Myfinamodel->update_acc_balance($data['tto']);
    }

	function valid_date()
	{
		if (!checkdate($this->input->post('month'), $this->input->post('day'), $this->input->post('year')))
		{
			$this->form_validation->set_message('valid_date', 'The date ['.$this->input->post('day').'-'. $this->input->post('month').'-'.$this->input->post('year').'] is invalid.');
			return FALSE;
		}
	} 

	function cannot_same()
	{
		if ($this->input->post('tfrom') == $this->input->post('tto'))
		{
			$this->form_validation->set_message('cannot_same', 'Debit and Credit account cannot be the same');
			return FALSE;
		}
	}
	
	function p_cannot_same()
	{
		if ($this->input->post('ptfrom') == $this->input->post('ptto'))
		{
			$this->form_validation->set_message('cannot_same', 'Debit and Credit account cannot be the same');
			return FALSE;
		}
	}
 
}
?>
