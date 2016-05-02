<?php
require_once ("secure_area.php");
class Config extends Secure_area 
{
	function __construct()
	{
		parent::__construct('config');
		//Loading Libraries
		$this->load->library('gas');
		//Loading Models
		$this->load->model('Myfinamodel', '', TRUE);
	}
	/**
	 * @Author Kamaro Lambert
	 * @name index 
	 * Entrance method for the configuration module
	 */
	function index($config_type=null)
	{
		//Getting account types
		$data['accounts']  = $this->Myfinamodel->list_accounts();
		//Getting the list of all account types
		$data['acc_type'] = $this->Myfinamodel->list_acc_type();
		$data['page_title']=$this->lang->line("config_info");
		if ($config_type==null)
		{
			$config_type='config';
		}
		else
		{
			$config_type=$config_type."_config";
		}
		$data['body']=$this->load->view("config/global_config/".$config_type,$data,true);
		
		$this->load->view('config/page',$data);
	}
		/**
		 * @Author Kamaro Lambert
		 * @name   save
		 * @method to save global configuration settins
		 */
	function save($config_type=null)
	{
		if($config_type==null)
		{
		$batch_save_data=array(
		//Global configuration
		'company'=>$this->input->post('company'),
		'address'=>$this->input->post('address'),
		'phone'=>$this->input->post('phone'),
		'email'=>$this->input->post('email'),
		'fax'=>$this->input->post('fax'),
		'website'=>$this->input->post('website'),
        'language'=>$this->input->post('language'),
		'timezone'=>$this->input->post('timezone'),);
		}
		
		//ITEMS CONFIGURATIONS
		if($config_type=='items')
		{
		$batch_save_data=array(
		'default_tax_1_rate'=>$this->input->post('default_tax_1_rate'),		
		'default_tax_1_name'=>$this->input->post('default_tax_1_name'),		
		'default_tax_2_rate'=>$this->input->post('default_tax_2_rate'),	
		'default_tax_2_name'=>$this->input->post('default_tax_2_name'),	
		'expiration_days'=>$this->input->post('expiration_days'),);
		}		
		//CURRENCY CONFIGURATIONS
		if($config_type=='currency')
		{
		$batch_save_data=array(
		'currency_symbol'=>$this->input->post('currency_symbol'),
		'decimal_number'=>$this->input->post('decimal_number'),
		'exchange_rate'=>$this->input->post('exchange_rate')	,
		'exchange_rate_name'=>$this->input->post('exchange_rate_name'),
		'decimal_symbol'=>$this->input->post('decimal_symbol'),
		'thousands_separator'=>$this->input->post('thousands_separator'),);
		}
				
		//RECEIPT CONFIGURATIONS
		if($config_type=='receipt')
		{
		$batch_save_data=array(
		'return_policy'=>$this->input->post('return_policy'),
		'print_receipt_size'=>$this->input->post('print_receipt_size'),
		'print_after_sale'=>$this->input->post('print_after_sale')	,
		'finish_sale_confirm'=>$this->input->post('finish_sale_confirm'),);
		}

		//FINANCE CONFIGURATIONS
		if($config_type=='finance')
		{
		$batch_save_data=array(
	    'local_cash_account'=>$this->input->post('local_cash_account'),
		'foreign_cash_account'=>$this->input->post('foreign_cash_account'),
		'insurance_parent_account'=>$this->input->post('insurance_parent_account')
		);
		}
		if( $this->Appconfig->batch_save( $batch_save_data ) )
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('config_saved_successfully')));
		}
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name do_upload()
	 * @method to upload image
	 */
	function do_upload()
	{
		$config['upload_path'] = './images/company_logo/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$config['file_name']='logo';
		$config['overwrite']=TRUE;
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{
			$data = array('error' => $this->upload->display_errors());
	        
			
		}
		else
		{
			$data_uploaded = array('upload_data' => $this->upload->data());
			$config_data=array(
					'key'=>'company_logo',
					'value'=>$data_uploaded['upload_data']['file_name']
			);
			$data=array('success' => 'New log set successfully');
			$this->db->where('key', 'company_logo');
			$this->db->update('app_config',$config_data);
			
		}
		 $this->images($data);
		
	}
	/**
	 * @author Kamaro Lambert
	 * @name  image
	 * $method to load the image view
	 */
	function images($message=null)
	{
		
		$data['message']=$message;
		$data['page_title']='Image settings';
		$data['body']=$this->load->view('config/global_config/images_settings',$data,true);
		$this->load->view('config/page',$data);
	}
	/**
	 * @author Kamaro Lambert
	 * @name backup()
	 * @method to upload image
	 */
	function backup($confirm=null)
	{
		if ($confirm=='yes')
		{
		// Load the DB utility class
		$this->load->dbutil();
		
		// Backup your entire database and assign it to a variable
		if($backup =& $this->dbutil->backup())
		{
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		// naming our backup datbase
	    $mybackup='KPOS2Backup'.DATE('D.d.M.Y').'at'.date("G:i:s").'.gz';
	   	write_file("c:\wamp\www\kpos\$mybackup", $backup);
		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		force_download($mybackup, $backup);
		}
		else 
		{
			$data['message']= array('error' => 'Oops! We couldn\'t back your store up');
			$data['page_title']='Back up your Store';
			$data['body']=$this->load->view('config/maintenance/backup_settings',$data,true);
			$this->load->view('config/page',$data);
		}
		}
		else {
			
		$data['page_title']='Back up your Store';
		$data['body']=$this->load->view('config/maintenance/backup_settings',$data,true);
		$this->load->view('config/page',$data);
		}
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
	// CURRENCY
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
	
	/**
	 * @author Kamaro Lambert
	 * @name   currency()
	 * Displays a manageable list of all currencies. Each row can be updated or deleted.
	 */
	function currency($offset=0)
	{
		$currencies= Model\Currency_model::all();
		
		$config['base_url'] = site_url('/config/currency');
		$config['total_rows'] =  count($currencies);
		$config['per_page'] = '10';
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		
		$data['currencies']= Model\Currency_model::limit($config['per_page'],$offset)->all();
		
		
		//Setting the tamplate for the table
		$template = array( 'table_open' => '<table border="1" class="table table-bordered table-striped">');
		
		$data['page_title']='Currencies';
		
		
		
		$data['body']=$this->load->view('config/global_config/currency_table',$data,true);
	
		$this->load->view('config/page',$data);
		
		
	}
	
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###
	
	/**
	* Currency EDIT
	* Inserts new currencies to the database.
	 * This page is accessed via the 'Currency' page via a link titled 'Insert New Currency'.
	 */
	 function currency_edit($id=0)
	 {
     //check if the id were provided
	 		if($id==0)
	 		{
	 			show_404();
	 			return;
	 		}
	 	
	 		if($_POST)
	 		{
	 			if($this->input->post('cancel'))
	 			{
	 				$this->currency_delete($id);
	 			}
	 			$data['currency']= Model\Currency_model::find($id);
	 			
	 			$data['currency']->Name=$this->input->post('Name');
	 			$data['currency']->Exchange_Rate=$this->input->post('Exchange_Rate');
	 			$data['currency']->Symbol=$this->input->post('Symbol');
	 			$data['currency']->Symbol_Suffix=$this->input->post('Symbol_Suffix');
	 			$data['currency']->Thousand_Separator=$this->input->post('Thousand_Separator');
	 			$data['currency']->Decimal_Separator=$this->input->post('Decimal_Separator');
	 			$data['currency']->Status=$this->input->post('Status');
	 		
	 			if(!$data['currency']->save(TRUE))
	 			{
	 				$this->session->set_flashdata('type','danger');
	 				$this->session->set_flashdata('message','Error occured during operation');
	 				$this->currency();
	 				
	 			}
	 			else
	 			{
	 				$this->session->set_flashdata('type','success');
	 				$this->session->set_flashdata('message','Successfully Saved in the database');
	 			    $this->currency();
	 			}
	 		}
	 		else 
	 		{
	 			
	 			$data['currency']= Model\Currency_model::find($id);
	 			
	 			$this->load->view('config/global_config/currency_edit',$data);
	 		}
	 		
	 		
	 	}
	 /**
      * @author Kamaro Lambert
      * @name   optimize
      * Function to optimize kpos database
      */
		function optimize()
		{
		   $this->load->dbutil();
		   
		   $result = $this->dbutil->optimize_database();
		  
            if ($result)
            {
              $this->session->set_flashdata('type','success');
	 		  
			  $this->session->set_flashdata('message','Database optimized successfully!');
	 			    
     		}
			else
	 		{
	 				$this->session->set_flashdata('type','danger');
	 				$this->session->set_flashdata('message','Unable to optimize  database');
	 			    $this->currency();
	 		}
		  redirect('config',refresh);
		}	
     /**
      * @author Kamaro Lambert
      * @name   currency_new
      * Function create new Currency
      */
	 function currency_new()
	 {
	 	$currency= new Model\Currency_model();
	 	$currency->Name='New Currency';
	 	$currency->Exchange_Rate='600';
	 	
	 	//Save the new records
	 	$currency->save();
	 	
        $curr_id= Model\Currency_model::last_created()->curr_id;
       $this->currency_edit($curr_id);
      	 	
	 }
	 /**
	  * @author Kamaro Lambert
	  * @name   Currency_delete
	  * @param  numeric $id
	  * Method to delete the currency
	  */
	 function currency_delete($id=0)
	 {
	 
	 	Model\Currency_model::delete($id);
	 
	 	$this->session->set_flashdata('type','success');
	 	$this->session->set_flashdata('message','Operation done successfully');
	  	redirect('config/currency');
	 }

	/**
	 * @author Kamaro Lambert
	 * Method to change the default currency
	 */ 
    function currency_default()
    {
    	//First remove all default currencies
    	$currencies = Model\Currency_model::find_by_Default(1);
    	
    	//Remove all default currencies
    	foreach($currencies as $curr)
    	{
    		$curr->Default=0;
    		$curr->save(); 
    		
    	}
    	
   
    	//Set one default currencies
    	$currency = Model\Currency_model::find($_POST['Default']);
    	$currency->Default=1;
    	$currency->save();
    	 	
       	$this->session->set_flashdata('type','success');
       	$this->session->set_flashdata('message','New default Currency Set');
       	redirect('config/currency');
       	
    	
    }
}
?>