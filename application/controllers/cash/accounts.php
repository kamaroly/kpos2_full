<?php

/**
 * Account Controller Class
 *
 * @package     Cash model for kpos
 * @subpackage  Controllers
 * @author      Jamalulkhair Khairedin (jalte)
 * @upgradedby     Kamaro Lambert
 * @copyright   Copyright (c) 2013, www.huguka.com
 * @license		http://huguka.com/kpos_license.txt
 * @link 		http://kpos.huguka.com
 * @version 	1.5
 *
 */

require_once (APPPATH."controllers/secure_area.php");
class Accounts extends secure_area 
{
var $datestring = "%D %d %M %Y - %h:%i %a"; 

 function  __construct()
    {     
        parent::__construct();
        
       
        //Get time values
        $time = time();
        $this->today_date = mdate($this->datestring, $time);
        
        
        
        //setting the validation rules
        $this->accounts_validation_rules = array(
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
        
        //validation rules for the account type
        $this->accounts_types_validation=array(
        		array(
        				'field' => 'acc_name',
        				'label' => 'Type name',
        				'rules' => 'required|trim'
        		),
        		array(
        				'field' => 'acc_description',
        				'label' => 'Description',
        				'rules' => 'required|trim'
        		),
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
		print_r($this->data['allowed_modules']);
		exit;
		redirect('cash/accounts/summary', 'location');
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name additem()
	 * @method to add new account 
	 *
	 */
	function additem()
	{
		//load libraries
		$this->load->library('Form_validation');
		
		//Loading Models
	    $this->load->model('Myfinamodel', '', TRUE);
	    
		//Setting the title of the page
		$data['page_title'] = 'Add Account';
		
		//Setting validation
	    $this->form_validation->set_rules($this->accounts_validation_rules);
		
	    //Getting the list of all account types
	    $data['acc_type'] = $this->Myfinamodel->list_acc_type();
         
	    //Receiving posted data
		$data['post'] = isset($_POST) ? $_POST : '';
		
		//Check if submitted data are valid
	    if ($this->form_validation->run() == FALSE)
	    {
	    	//Something went wrong let's go back to the form 
			$data['body'] = $this->load->view('cash/account/add_item', $data, TRUE);
			
		    $this->load->view('cash/page', $data);
	    }
	    else
		{  //All seem to be fine...
			$insert = $_POST; //Getting the posted data
			
			$this->Myfinamodel->add_account($insert); //Adding new account to the database
			
			//Redirect to the accounts summary page
			redirect('cash/accounts/summary', 'location');
	    }
	}

	/**
	 * @author Kamaro Lambert
	 * @name edititem
	 * @method to edit account
	 * @param int $cid
	 *
	 */
	function edititem($cid)
	{
		//load libraries
		$this->load->library('Form_validation');
		
		//Loading models
	    $this->load->model('Myfinamodel', '', TRUE);
		
	    //Setting the title of the page
		$data['page_title'] = 'Edit Account';
		
		//Setting validation rules
	
	    $this->form_validation->set_rules($this->accounts_validation_rules);
		 
	    //Getting items related to the account
		$data['table'] = $this->Myfinamodel->get_account_item($cid);
		
		//Getting account types
		$data['acc_type'] = $this->Myfinamodel->list_acc_type($data['table']['acc_credit']);
		
		//Getting posted data
		$data['post'] = isset($_POST) ? $_POST : '';
		
		//Validating the submited form
	    if ($this->form_validation->run() == FALSE)
	    {
	    	//Something goes wrong..
			$data['body'] = $this->load->view('cash/account/add_item', $data, TRUE);
			//let's return to the form
		    $this->load->view('cash/page', $data);
	    }
	    else
		{
			//Check if there is a change on the existed information
			if ($_POST['cname'] != $data['table']['cname']) $insert['cname'] = $_POST['cname'];  
			if ($_POST['camount'] != $data['table']['camount']) $insert['camount'] = $_POST['camount'];
			if ($_POST['cparent'] != $data['table']['cparent']) $insert['cparent'] = $_POST['cparent'];
			
			//Check if the data to be inserted are in array()
			if (is_array($insert))
			{
			 //Get Category ID to edit	
			 $insert['cid'] = $data['table']['cid'];
			  //Edit the inserted data
			 $this->Myfinamodel->edit_account($insert);
			 
			}
			//Redirect to the account summary
			redirect('cash/accounts/summary', 'location');
	    }
	}

	/**
	 * @author Kamaro Lambert
	 * @name summary
	 * @method to get accounts summaries
	 * 
	 *
	 */
	function summary()
	{
	    $this->load->model('Myfinamodel', '', TRUE);
	    
	    
		$data['page_title'] = 'My Accounts';
		$data['table'] = $this->Myfinamodel->list_accounts();
		$data['sumtype'] = $this->Myfinamodel->list_accounts_sum();
		$data['sum'] = $this->Myfinamodel->sum_acc_amount();
		$data['body'] = $this->load->view('cash/account/summary', $data, TRUE);
		$this->load->view('cash/page', $data);
	}

	/**
	 * @author Kamaro Lambert
	 * @name _getTree()
	 * @method to get accounts tries
	 * @param $relationships
	 * @param $parent_id
	 *
	 */
	function _getTree( $relationships, $parent_id ) 
	{ 
		$children = array(); 
		foreach( $relationships as $key=>$val ) 
		{ 
			if( $val['parent_id'] == $parent_id ) 
			{ 
				unset( $relationships[$key] ); 
				$child = array( 'nav_name' => $val['child_name'], 'nav_id' => $val['child_id'], 'bamount' => $val['bamount'], 'bincome' => $val['bincome'], 'nav_children' => array() ); 
				$child['nav_children'] = $this->_getTree( $relationships, $val['child_id'] ); 
				if( count($child['nav_children']) == 0 ) { 
					unset( $child['nav_children'] ); 
				} array_push( $children, $child ); 
			} 
		} 
		return $children; 
	}

	/**
	 * @author Kamaro Lambert
	 * @name _arrayTree()
	 * @method to get _arrayTree 
	 * @param array $tree
	 * @param int $level
	 *
	 */
	function _arrayTree($tree=array(),$level=0) {
	  if( !is_int( $level ) ) {
		$level = 0;
	  }
	  $level++;
	  foreach( $tree as $key=>$val ) {
		$i = $val['nav_id'];
		$table[$i]['bid'] = $val['nav_id'];
		$table[$i]['bname'] = $val['nav_name'];
		$table[$i]['bamount'] = $val['bamount'];
		$table[$i]['bincome'] = $val['bincome'];
		$table[$i]['lvl'] = $level;
		if( isset($val['nav_children']) ) {
			$table[$i]['gotchild'] = 1;
			$table[$i]['btotal'] = $val['bamount'];
			$data = $this->_arrayTree( $val['nav_children'], $level+1 );
			foreach ($data as $k => $v){
				$a = $v['bid'];
				$table[$a]['bid'] = $v['bid'];
				$table[$a]['bname'] = $v['bname'];
				$table[$a]['bamount'] = $v['bamount'];
				$table[$a]['lvl'] = $v['lvl'];
				$table[$a]['bincome'] = $v['bincome'];
				$table[$a]['gotparent'] = 1;
				$table[$i]['btotal'] = $table[$i]['btotal'] + $v['bamount'];
			}
		}
	  }
	  return $table;
	}

	/**
	 * @author Kamaro Lambert
	 * @name sort_table()
	 * @method to sort the table 
	 * @param array $table
	 * @param int $column
	 *
	 */
    function sort_table($table,$column)
    {
		$this->load->helper('url');
		$this->db_session->set_userdata($table, $column);
		redirect($this->db_session->userdata('session_referer'), 'location');
    }

    /**
     * @wuthor Kamaro Lambert
     * @name   acctypes()
     * @method to manage accounts
     * @params int $limit
     * @params int $offset 
     */
	function acctypes($offset=0)
	{
		//Loading the models to be used
		//Loading Models
		$this->load->model('Myfinamodel', '', TRUE);
		 
		
		//Setting the title of the page
		$data['page_title'] = 'Accounts types';
		$config['base_url'] = site_url('/cash/accounts/acctypes');
		$config['total_rows'] = $this->Myfinamodel->count_types();
		$config['per_page'] = '20';
	
		$this->pagination->initialize($config);
		
		$data['controller_name']=strtolower(get_class());
		
		$data['table']=$this->Myfinamodel->get_acctypes( $config['per_page'] ,$offset);
			
		$data['body'] = $this->load->view('cash/account/accountstypes', $data, TRUE);
		
		$this->load->view('cash/page', $data);
		
	}
   
	/**
	 * @author Kamaro Lambert
	 * @name additem()
	 * @method to add new account
	 *
	 */
	function addtype($id=null)
	{
		
		//Loading Models
		$this->load->model('Myfinamodel', '', TRUE);
         
	    //check if the the user wants to edit the account type
	    if(count($_POST)>0) //check if the use wants to create new account
	    {
	    //load libraries
		$this->load->library('Form_validation');
		
	
				
		//Setting the title of the page
		$data['page_title'] = 'Add Account';
	  
		//Setting validation
		$this->form_validation->set_rules($this->accounts_types_validation);
			 
		//Receiving posted data
		$data['post'] = isset($_POST) ? $_POST : '';
	
		//Check if submitted data are valid
		if ($this->form_validation->run() == FALSE)
		{
			//Something went wrong let's go back to the form
			$data['body'] = $this->load->view('cash/account/addtype', $data, TRUE);
				
			$this->load->view('cash/page', $data);
		}
		 else
		  {  //All seem to be fine...
			$insert = $_POST; //Getting the posted data
			if($id==null)	 //if it a new parent?
			{
			   if($this->Myfinamodel->addtype($insert)) //Adding new account to the database
			    {
			      //Set the delete status message
			   	$this->session->set_flashdata('message','Account type added successfull');
			   	}
			   	Else
			   	{   //Set the delete status message in the cookies
			   	$this->session->set_flashdata('message','Failed to add account type');
			   	}
			   	
			}
			
			else 
			{ //it's just editing the existing account
				if($this->Myfinamodel->addtype($insert,$id))
					{
						//Set the delete status message
						$this->session->set_flashdata('message','Account type edited successfull');
					}
			    else
					{   //Set the delete status message in the cookies
				    	$this->session->set_flashdata('message','Failed to edit account type');
					}
					 
			}	
			//Redirect to the accounts summary page
			redirect('cash/accounts/acctypes', 'location');
		 }
	   }
	   else
	   {
	   	
	   	//Setting the title of the page
	   	$data['page_title'] = 'Edit types';
	   
	   	$data['table']= $this->Myfinamodel->get_acctypes(1,0,$id);
	   		
	   	$data['body'] = $this->load->view('cash/account/addtype', $data, TRUE);

	   	$this->load->view('cash/page', $data);
	
	   	
	   }
	   
	}
	/**
	 * @author Kamaro Lambert
	 * @name   deletetype()
	 * @method to delete the type of the account
	 * @params int $id
	 */
	function deletetype($id)
	{
		//First delete all children accounts
		if($this->db->delete('category', array('cparent' => $id)))
		{
			//If children accounts are deleted then delete the parent
			$this->db->delete('account_type', array('acc_id' => $id));
			//Set the delete status message
			$this->session->set_flashdata('message','Account Deleted successfull');
		}
		Else
		{   //Set the delete status message in the cookies
			$this->session->set_flashdata('message','Failed to delete the account');
		}
		
		$this->acctypes();
		
		
	}
	
}
?>
