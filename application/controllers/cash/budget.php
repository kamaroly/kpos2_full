<?php

/**
 * Budget Controller Class
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
class Budget extends secure_area
 {

	var $datestring = "%D %d %M %Y - %h:%i %a"; 
	
   function  __construct()
    {     
        parent::__construct();
        
        
		$time = time();
		//Setting the global variables to be used
	    $this->today_date = mdate($this->datestring, $time);
	    $this->dbprefix = $this->db->dbprefix;
	    $this->userid = 1;

	    //Loading libraries
	    $this->load->library('session');
	    
	    //setting the validation rules
	    $this->budget_validation_rules = array(
	    		array(
	    				'field' => 'cname',
	    				'label' => 'Category Name',
	    				'rules' => 'required|trim'
	    		),
	    		array(
	    				'field' => 'camount',
	    				'label' => 'Amount',
	    				'rules' => 'numeric|required'
	    		)
	    );
	    
	    
	}

	/**
	 * @author Kamaro Lambert
	 * @name index
	 * @method for home of the accounts controller
	 *
	 */
	
	function index()
	{
		redirect('/cash/budget/summary', 'location');
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name additem()
	 * @method to add new budget item
	 *
	 */
	function additem()
	{
		//load libraries....
		$this->load->library('Form_validation');
		
		//Loading models...
	    $this->load->model('Myfinamodel', '', TRUE);
		
	    //Setting page titles...
	    $data['page_title'] = 'Add Budget Item';
		
	    //Setting validation rules
	    $this->form_validation->set_rules($this->budget_validation_rules);
	    
	    //Get the budgets summary
		$data['budget'] = $this->Myfinamodel->budget_summary();
		
		//Get tree of the budget
		$data['tree'] = $this->_getTree( $data['budget']['new'], 0 );
		$data['tree_table'] = $this->_arrayTree($data['tree']);
		
		//Receiving posted information if submitted
		$data['post'] = isset($_POST) ? $_POST : '';
		
		//Validating the submitted form
	    if ($this->form_validation->run() == FALSE)
	    { //Something goes wrong here.. let's go back to the form
	    	
			$data['body'] = $this->load->view('cash/budget/add_item', $data, TRUE);
		    $this->load->view('cash/page', $data);
	    }
	    else
		{  //All seems to be welll
			
			$parent_id = $_POST['cparent']; //Receiving posted information
			
			$cincome = $this->Myfinamodel->get_budget_item($parent_id); //Get category income by parent
			$cincome = $cincome['cincome'];
			$camount = isset($_POST['camount']) ? $_POST['camount'] : 0;
			$this->Myfinamodel->insert_budget_item($_POST['cname'],"b",$cincome,$camount, $parent_id);
			
			//Redirect to the budget summary
			redirect('/cash/budget/summary', 'location');
	    }
	}

	/**
	 * @author Kamaro Lambert
	 * @name edititem
	 * @method to edit budget
	 * @param int $cid
	 *
	 */
	function edititem($cid)
	{
		//load libraries....
		$this->load->library('Form_validation');
		
		//Loading models
	    $this->load->model('Myfinamodel', '', TRUE);
		
	    //Setting page title
		$data['page_title'] = 'Edit Budget Item';
		
		//Setting validation rules
	    $this->form_validation->set_rules($this->budget_validation_rules);
	    
	    //Getting budget summary
		$data['budget'] = $this->Myfinamodel->budget_summary();
		
		//Getting the table that contains budget items
		$data['table'] = $this->Myfinamodel->get_budget_item($cid);
		
		//Getting the tree of the budgets		
		$data['tree'] = $this->_getTree( $data['budget']['new'], 0 );
		
		//Getting tree array
		$data['tree_table'] = $this->_arrayTree($data['tree']);
		
		//Receiving posted information
		$data['post'] = isset($_POST) ? $_POST : '';
		//Run validation 
	    if ($this->form_validation->run() == FALSE)
	    {//Something went wrong...
	    	
			//Reload the form page again
			$data['body'] = $this->load->view('cash/budget/add_item', $data, TRUE);
		    $this->load->view('cash/page', $data);
	    }
	    else
		{   //Everything seem to be while
			
			//Let's get parent ID			
			$parent_id = isset($_POST['cparent']) ? $_POST['cparent'] : $data['table']['cparent'];
			//Get budget items
			$cincome = $this->Myfinamodel->get_budget_item($parent_id);
			//Getting income per category
			$cincome = $cincome['cincome'];
			$camount = isset($_POST['camount']) ? $_POST['camount'] : 0;
			$insert = ''; $parent = '';
			
			//Check if there is a change on current information if yes update it.
			if ($_POST['cname'] != $data['table']['cname']) $insert['cname'] = $_POST['cname'];  
			if ($cincome != $data['table']['cincome']) $insert['cincome'] = $cincome;
			if ($camount != $data['table']['camount']) $insert['camount'] = $camount;
			if ($parent_id != $data['table']['cparent']) $parent['parent_id'] = $parent_id;
			//Edit budget			
			$this->Myfinamodel->edit_budget_item($cid,$insert,$parent);
			
			//Redirect
			redirect('/cash/budget/summary', 'location');
	    }
	}

	/**
	 * @author Kamaro Lambert
	 * @name summary
	 * @method to get budgets summaries
	  */
	function summary()
	{
		//Loading models
	    $this->load->model('Myfinamodel', '', TRUE);
	    
	    //Setting the page title
		$data['page_title'] = 'My Budgets';
		//Getting budget summary
		$data['budget'] = $this->Myfinamodel->budget_summary();

		//Check if the month was submitted
		if($this->input->post('month')) 
		{
	        //Storing variables in the session
			$this->session->set_userdata('budget_sum_month', $this->input->post('month'));
			$this->session->set_userdata('budget_sum_year', $this->input->post('year'));
			redirect('/cash/budget/summary', 'location');
		}
		
         //Check if the budget_sum_month
         
		if (!$this->session->userdata('budget_sum_month'))
		 {
			$this->session->set_userdata('budget_sum_month', mdate('%m', time()));
			$this->session->set_userdata('budget_sum_year', mdate('%Y', time()));
		}
		
		
		
		$data['total'] = $this->Myfinamodel->sum_budget_amount($this->session->userdata('budget_sum_month'),$this->session->userdata('budget_sum_year'));

		$this->load->library('formdate');
		$formdate = new FormDate();
		//$formdate->setLocale('nl_BE');
		$formdate->year['start'] = 2006;
		$formdate->year['end'] = DATE('Y');
		$formdate->month['values'] = 'numbers';
		$formdate->month['selected'] = $this->session->userdata('budget_sum_month');
		$formdate->year['selected'] = $this->session->userdata('budget_sum_year');
		$data['formdate'] = $formdate;

		$data['tree'] = $this->_getTree( $data['budget']['new'], 0 );
		$data['table'] = $this->_arrayTree($data['tree']);
		$data['body'] = $this->load->view('cash/budget/summary', $data, TRUE);
		$this->load->view('cash/page', $data);
	}

	/**
	 * @author Kamaro Lambert
	 * @name _getTree()
	 * @method to get budget tree
	 * @param $relationships
	 * @param $parent_id
	 */
	function _getTree( $relationships, $parent_id ) 
	{ 
		$children = array(); 
		foreach( $relationships as $key=>$val ) 
		{ 
			if( $val['parent_id'] == $parent_id ) 
			{ 
				unset( $relationships[$key] ); 
				$child = array( 'nav_name' => $val['child_name'], 'nav_id' => $val['child_id'], 'camount' => $val['camount'], 'cincome' => $val['cincome'], 'cbalance' => $val['cbalance'],'nav_children' => array() ); 
				$child['nav_children'] = $this->_getTree( $relationships, $val['child_id'] ); 
				if( count($child['nav_children']) == 0 ) { 
					unset( $child['nav_children'] ); 
				} array_push( $children, $child ); 
			} 
		} 
		return $children; 
	}

	//Array tree
	
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
		$table[$i]['cbalance'] = $val['cbalance'];
		$table[$i]['lvl'] = $level;
		if( isset($val['nav_children']) ) 
		{
			$table[$i]['gotchild'] = 1;
			$table[$i]['btotal'] = $val['camount'];
			$data = $this->_arrayTree( $val['nav_children'], $level+1 );
			foreach ($data as $k => $v){
				$a = $v['cid'];

				$table[$a] = $v;
				$table[$a]['gotparent'] = 1;
				$table[$a]['parentid'] = $val['nav_id'];
				$table[$i]['btotal'] = $table[$i]['btotal'] + $v['camount'];
			}
		}
	}
	 return $table;
	}

    function sort_table($table,$column)
    {
		
		$this->session->set_userdata($table, $column);
		redirect($this->session->userdata('session_referer'), 'location');
    }


	

}
?>
