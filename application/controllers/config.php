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
	function index($config_type=null,$checkstatus=null)
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
       
        if(strtolower(substr($config_type, 0,3))=='sdc')
		{
		if($checkstatus==='checkstatus')
		   {
		    $port=$this->config->item('sdc_port');
           if(is_null($this->_get_sdc_status( $port)) || $this->_get_sdc_status( $port)===FALSE)
           {
            
           	$this->session->set_flashdata('type','danger');
	 		$this->session->set_flashdata('message',str_replace('_SDC_PORT_',  $port, $this->lang->line('sales_sdc_not_connected_to_the_port')));
	 		
           }
           else
           {
           	$data['sdc_status']=$this->_get_sdc_status( $port);
           }
         }
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
		'tin'      =>$this->input->post('tin'),
		'company'  =>$this->input->post('company'),
		'address'  =>$this->input->post('address'),
		'phone'    =>$this->input->post('phone'),
		'phone_sms'=>$this->input->post('phone_sms'),
		'email'    =>$this->input->post('email'),
		'fax'      =>$this->input->post('fax'),
		'website'  =>$this->input->post('website'),
        'language' =>$this->input->post('language'),
		'timezone' =>$this->input->post('timezone'),);
		}
		//SDC CONFIGURATIONS
		if($config_type=='sdc')
		{
		 $port=$this->input->post('sdc_port');
        if(strtolower(substr($config_type, 0,3))=='sdc')
		{
           if(is_null($this->_get_sdc_status( $port)) || $this->_get_sdc_status( $port)===FALSE)
           {
            
           	$this->session->set_flashdata('type','danger');
	 		$this->session->set_flashdata('message',str_replace('_SDC_PORT_',  $port, $this->lang->line('sales_sdc_not_connected_to_the_port')));
	 		
           }
		}
		$batch_save_data=array(
		'sdc_port'                             =>$this->input->post('sdc_port'),
		'software_developer_id'                =>$this->input->post('software_developer_id')?$this->input->post('software_developer_id'):"KPO",		
		'software_certificate_number'          =>$this->input->post('software_certificate_number')?$this->input->post('software_certificate_number'):"01",		
		'serial_number'        		           =>"000001",	
		);
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
		'return_policy'                  =>$this->input->post('return_policy'),
		'show_logo_on_receipt'           =>$this->input->post('show_logo_on_receipt'),       
		'print_receipt_size'             =>$this->input->post('print_receipt_size'),
		'print_after_sale'               =>$this->input->post('print_after_sale')	,
		'finish_sale_confirm'            =>$this->input->post('finish_sale_confirm'),);
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
			$message=str_replace("<br />","",$this->session->flashdata('message'));

			if(strlen($message)>50):
			echo json_encode(array('error'=>true,'message'=>$message.' '.$this->lang->line('config_saved_successfully')));
		    else:
		    echo json_encode(array('success'=>true,'message'=>$this->lang->line('config_saved_successfully')));
		    endif;
		}
	}
	 /**
   * @method to get the get_sdc_status
   * @return [type] [description]
   */
  function _get_sdc_status($port)
  {
       
    if(substr($port, 3)>9)
    {
      $port="\\\\.\\$port";
    }

    ser_register("KAMARO LAMBERT #1","-1054384780");

     //Opening Port...
    ser_open($port, 9600, 8, "None", 1, "None");

    // Clears input and/or output buffers.
    ser_flush(true,true);
 
    //Check if the port is open 
    if (ser_isopen() == true )
     {

     $string_dig= '01 24 20 E7 05 30 31 33 30 03';
   
      $string_array_dig=explode(' ',$string_dig);
       $bytes_dig=" ";        
       foreach ($string_array_dig as $string_hex_dig=>$value_dig)
       {
         $bytes_dig=" ".(('0x'.$value_dig+128) % 256) - 128;
         if($bytes_dig!=00)
         {
         ser_writebyte("$bytes_dig\r\n");
         }
       }
       sleep(1);
     //Send request to the SDC asking the response 
    $str = ser_read();
     
    if($this->_error_handling($str))
    {

      $returned_data=implode(" ", $this->strToHex($str));
      $returned_data=$this->_get_string_between($returned_data,"E7","04");
      
      $hex_string=str_replace(" ", "", $returned_data);

      $string=explode(",", $this->hexToStr($hex_string));
      $data['SDC serial number']                     =$string[0];
      $data['Firmware version']                      =$string[1];
      $data['Hardware revision']                     =$string[2];
      $data['The number of current SDC daily report']=$string[3];   
      $data['Last remote audit date and time']       =$string[4];
      $data['Last local audit date and time']        =$string[5];                 
      $this->session->set_userdata('sdc_status', $hex_string);
      return $data;
    }
    else
    {
      return FALSE;
    }
   }
   else
   {
   	 return FALSE;
   }
   
   ser_close();
    
  }

/**
 * @author Kamaro Lambert
 * @method to convert strto hex
 */
function strToHex($string)
{

 //Force to Convert to string  
 $string=strval($string);

$hex='';
for ($i=0; $i < strlen($string); $i++)
{
$hex .= sprintf("%02x",ord($string[$i]));
}
return str_split(strtoupper($hex),2);
}

  /**
   * @Author Kamaro Lambert
   * @name hexToStr()
   * @example Base_convert_lib->hexToStr()
   * @param  string $hex
   * Method to convert  Hexadecimal to string
   */
  public function hexToStr($hex)
  {
    //Initializing the variable
    $string='';
    //Convert each HEX to string
    for ($i=0; $i < strlen($hex)-1; $i+=2)
    {
      $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
  }
/**
 * [get_string_between description]
 * @param  [type] $string [description]
 * @param  [type] $start  [description]
 * @param  [type] $end    [description]
 * @return [type]         [description]
 */
function _get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}
  /*
ERROR CODES
=====================================
i.       00 – no error;
ii.      11 – internal memory full;
iii.     12 – internal data corrupted;
iv.      13 – internal memory error;
v.       20 – Real Time Clock error;
vi.      30 – wrong command code;
vii.     31 – wrong data format in the CIS request data;
viii.    32 – wrong TIN in the CIS request data;
ix.      33 – wrong tax rate in the CIS request data;
x.       34 – invalid receipt number int the CIS request data;
xi.      40 – SDC not activated;
xii.     41 – SDC already activated;
xiii.    90 – SIM card error;
xiv.     91 – GPRS modem error;
xv.      99 – hardware intervention is necessary.

------------------------------------------
WORNING CODES
======================================
i. 0 – no warning;
ii. 1 – SDC internal memory is near to full (it is at more than 90% of capacity);
iii. 2 – SDC internal memory is near to full (it is at more than 95% of capacity).
-------------------------------------------

 * @param  string missage_string    [description]
 * @return [type]         [description]
*/

  function _error_handling($missage_string)
  {

    if(strpos($missage_string, "P0"))
    {
      return true;
    }
    elseif(strpos($missage_string, "E11"))
    {
      return $this->lang->line('sdc_error_internal_memory_full');
    }
    elseif(strpos($missage_string, "E12"))
    {
      return $this->lang->line('sdc_error_internal_data_corrupted');
    }
    elseif(strpos($missage_string, "E13"))
    {
      return $this->lang->line('sdc_error_internal_memory_error');
    }
    elseif(strpos($missage_string, "E20"))
    {
      return $this->lang->line('sdc_error_real_Time_Clock_error');
    }
    elseif(strpos($missage_string, "E30"))
    {
      return $this->lang->line('sdc_error_wrong_command_code');
    }
    elseif(strpos($missage_string, "E31"))
    {
      return $this->lang->line('sdc_error_wrong_data_format_in_the_CIS_request_data');
    }
    elseif(strpos($missage_string, "E32"))
    {
      return $this->lang->line('sdc_error_wrong_TIN_in_the_CIS_request_data');
    }
    elseif(strpos($missage_string, "E33"))
    {
      return $this->lang->line('sdc_error_wrong_tax_rate_in_the_CIS_request_data');
    }
    elseif(strpos($missage_string, "E34"))
    {
      return $this->lang->line('sdc_error_invalid_receipt_number_int_the_CIS_request_data');
    }
    elseif(strpos($missage_string, "E40"))
    {
      return $this->lang->line('sdc_error_sdc_not_activated');
    }
    elseif(strpos($missage_string, "E41"))
    {
      return $this->lang->line('sdc_error_sdc_already_activated');
    }
    elseif(strpos($missage_string, "E90"))
    {
      return $this->lang->line('sdc_error_sim_card_error');
    }
    elseif(strpos($missage_string, "E91"))
    {
      return $this->lang->line('sdc_error_gprs_modem_error');
    }
     elseif(strpos($missage_string, "E92"))
    {
      return $this->lang->line('sdc_error_hardware_intervention_is_necessary');
    }
     elseif(strlen($missage_string)>110)
    {
      return str_replace('_SDC_PORT_', $this->config->item('sdc_port'), $this->lang->line('sales_sdc_not_connected_to_the_port'));
    }
    elseif(empty($missage_string) or strlen($missage_string)==0 )
    {
      return str_replace('_SDC_PORT_', $this->config->item('sdc_port'), $this->lang->line('sales_sdc_not_connected_to_the_port'));
    }
    elseif(strlen($missage_string)==1 )
    {
         return $this->lang->line('sdc_is_busy');
       }
    else
    {
      return "Unknow error :".$missage_string;
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
		$config['file_name']='sme_logo';
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
	 				$this->session->set_flashdata('message',$this->lang->line('config_saved_successfully'));
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
	 	$this->session->set_flashdata('message',$this->lang->line('config_saved_successfully'));
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
    	 	
    	//FINANCE CONFIGURATIONS
    	$batch_save_data=array(
    				'enable_multi_currency'=>$this->input->post('enable_multi_currency'),
    	            );
    	if( $this->Appconfig->batch_save( $batch_save_data ) )
    	{
    		
       	$this->session->set_flashdata('type','success');
       	$this->session->set_flashdata('message',$this->lang->line('config_saved_successfully'));
    	}
    	
       	redirect('config/currency');
       	
    	
    }
    /**
     * @author Kamaro Lambert
     * @name   optimize
     * Function to optimize kpos database
     */
    function optimize()
    {
    	  //diplaying waiting message.
            echo img('images/loading_animation.gif');

            echo $this->lang->line('common_wait_transaction');
            
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
    	redirect('config','refresh');
    } 
}
?>