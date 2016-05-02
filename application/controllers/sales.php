<?php
require_once ("secure_area.php");
class Sales extends Secure_area
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('sale_lib');
		$this->load->model('Myfinamodel');
		
		
	}
	
	/**
	 * @author Kamaro Lambert
	 * this function should return a file that looks like
	 * ================================
	 * R_NAM "Test Bill Date:07/05/2012"
	 * R_VRN "1001 Bill No : 15"
	 * R_TIN "30303030303"
	 * R_ADR "232"
	 * R_TXT " -------------------------------------------------------------------------------- "
	 * R_TXT " Item Name Qty Amount VAT "
	 * R_TXT " -------------------------------------------------------------------------------- "
	 * R_TRP " ITEM 1 " 1 * 65.00 V3
	 * R_TRP " ITEM 2 " 2 * 300.00 V2
	 * R_TRP " ITEM 5 " 2 * 1100.00 V1
	 * R_TXT " -------------------------------------------------------------------------------- "
	 * R_PM1 1465.00
	 * R_STT 1465.00
	 * @param array $data
	 */
	function sdc_receipt($sales_data=array())
	{
		
		//Loading the file helper
	    //-----------------------
		$this->load->helper('file');
		
		//Preparing the data
		//-------------------
		
		//Company Name and the Bill date
		$data['R_NAM']='" Bill Date:'.date('d/m/Y').'"';
		
		//Customer VRN number
		$data['R_VRN']='"1001 Bill No : '.$sales_data['sale_number'].'"';
		
		//Tax Identification Number
		$data['R_TIN']='"'.$sales_data['customer_tin'].'"';
		
		//Tax Identification Number
		$data['R_ADR']='"'.$sales_data['customer_address'].'"';
		
		//Tax Identification Number
		$data['R_TXT']='" Item Name Qty Amount VAT "';
		//Receipt Transaction
		
		$data['transactions']=$sales_data['cart'];
		//Payment Type
		$data['R_PM1']=$sales_data['payments'];
		
		//Subtotal Subtotal
		$data['R_STT']=$sales_data['subtotal'];
		
		
		//Loading the view with the data
		 $receipt_fiscal=$this->load->view('sdc/sdc_receipt',$data,true);
	     //Removing any space in the filename
		 $receipt_file_name=str_replace(' ', '_','Bill_'.$sales_data['sale_id'] );
		 
		 //Now let try to get the drive letter
		 $syste_driver_latter=$this->_driveLetter(__FILE__);
		 
		 $mypath="$syste_driver_latter:/dpool/in/";
   
		
		 if (!is_dir($mypath))
		  {
		 	mkdir($mypath, 0777, TRUE);
		 
		 }
	
	//Attempt file write
	//-------------------
     if ( ! write_file("$mypath$receipt_file_name.txt", $receipt_fiscal,"wd"))
       {
       	//Something went wrong while writting the file
       	echo 'byanze :(';
       	exit;
       	return false;
       }
     else
      { 
      	//All Goes well
        return true;
      }
		
	}

	function index()
	{
	    $this->_reload();
	}
   
	function item_search($keyword=null,$limit=20)
	{
		$key=$this->input->post('q')?$this->input->post('q'):$keyword;
		$limit=$this->input->post('limit')?$this->input->post('limit'):$limit;
		
		$suggestions = $this->Item->get_item_search_suggestions($key,$limit);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_item_kit_search_suggestions($key,$limit));
		
		echo implode("\n",$suggestions);
	}

	function customer_search()
	{
		$suggestions = $this->Customer->get_customer_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}

	function select_customer()
	{
		$customer_id = $this->input->post("customer");
		$this->sale_lib->set_customer($customer_id);
		$this->_reload();
	}

	function change_mode()
	{
	
	    $mode = $this->input->post("mode");
		$type = $this->input->post("type");
		$this->sale_lib->set_mode($mode);
		$this->sale_lib->set_type($type);
		$this->_reload();
	}
	/**
	 * @Author Kamaro Lambert
	 * @name chance_currency
	 * 
	 */
	function change_currency()
	{
		//First clear all payments 
	    $this->sale_lib->empty_payments();
	    
	    //Change payment currency
		$currency = $this->input->post("payment_currency");
		$this->sale_lib->set_currency($currency);
		
		$this->_reload();
	}
	
	function set_comment() 
	{
 	  $this->sale_lib->set_comment($this->input->post('comment'));
	}
	
	
   
	function set_email_receipt()
	{
 	  $this->sale_lib->set_email_receipt($this->input->post('email_receipt'));
	}

	//Alain Multiple Payments
	function add_payment()
	{		
		$dafault_currencies= Model\Currency_model::limit(1,0)->find_by_Default(1);
		foreach ($dafault_currencies as $def_currency )
		{
			$default_currency= $def_currency;
			break;
		}
		$data=array();
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'numeric');
		
		if ($this->form_validation->run() == FALSE)
		{
			if ( $this->input->post('payment_type') == $this->lang->line('sales_gift_card') )
				$data['error']=$this->lang->line('sales_must_enter_numeric_giftcard');
			else
				$data['error']=$this->lang->line('sales_must_enter_numeric');
				
 			$this->_reload($data);
 			return;
		}
		
		
		//check if there entered amount
		if($this->input->post('amount_tendered')==0)
		{
			$data['error']=$this->lang->line('sales_no_amount_entered');
			
			$this->_reload($data);
			return;
		}
		
		$payment_type=$this->input->post('payment_type');
	
		if ( $payment_type == $this->lang->line('sales_giftcard') )
		{
			$payments = $this->sale_lib->get_payments();
			$payment_type=$this->input->post('payment_type').':'.$payment_amount=$this->input->post('amount_tendered');
			$current_payments_with_giftcard = isset($payments[$payment_type]) ? $payments[$payment_type]['payment_amount'] : 0;
			$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $current_payments_with_giftcard;
			if ( $cur_giftcard_value <= 0 )
			{
				$data['error']='Giftcard balance is '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) ).' !';
				$this->_reload($data);
				return;
			}
			elseif ( ( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ) > 0 )
			{
				$data['warning']='Giftcard balance is '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ).' !';
			}
			$payment_amount=min( $this->sale_lib->get_total(), $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) );
		}
		else
		{
			$payment_amount=$this->input->post('amount_tendered');
		}
		
		//Get account ID for the payment method used
		if ( $payment_type == $this->lang->line('sales_giftcard') )
		{
			$accid=39; //Account ID for Gift
		}
		elseif ( $this->input->post('payment_type') == $this->lang->line('sales_check') )
		{
				if($this->input->post('bank_name')=='' OR ($this->input->post('check_number'))=='')
				{
					$data['error']=$this->lang->line('sales_must_enter_bank_name_and_check_number');
			
					$this->_reload($data);
					return;
				}
				else
				{
					$accid=40; //Account ID for Stock
					$payment_type=$this->input->post('payment_type').'_'.$this->input->post('check_number').'_'.$this->input->post('bank_name');
				}
			
			
		}
		elseif ($payment_type == $this->lang->line('sales_cash'))
		{
			$accid=37; // Account ID FOR bacnk
		}
		elseif ($payment_type == $this->lang->line('sales_debit'))
		{
			$accid=37; // Account ID FOR bacnk
		}
		elseif ( $payment_type == $this->lang->line('sales_insurance') )
		{
			$accid=$this->input->post('insurance_type');
		}
		
		
		if ( $payment_type == $this->lang->line('sales_insurance') )
		{
			$payment_type.=' '.$this->Myfinamodel->list_accountsby_id($accid)->cname;
		}
		
		
		
		
		if( !$this->sale_lib->add_payment( $payment_type, $payment_amount,$accid,$default_currency ) )
		{
			$data['error']='Unable to Add Payment! Please try again!';
		}
		
		$this->_reload($data);
	}

	//Alain Multiple Payments
	function delete_payment( $payment_id )
	{
		
		$this->sale_lib->delete_payment( $payment_id );
		$this->_reload();
	}
  /**
   * @author Kamaro Lambert
   * @method to add an item to the cart
   * @name add()
   * @params int $item_id
   */
  function add($item_id=-1)
	{
		$data=array();
		$mode = $this->sale_lib->get_mode();
		$type = $this->sale_lib->get_type();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post("item")?$this->input->post("item"):$item_id;
		$quantity = $mode=="sale" ? 1:-1;

		if($this->sale_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode=='return')
		{
			$this->sale_lib->return_entire_sale($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->sale_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->sale_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif(!$this->sale_lib->add_item($item_id_or_number_or_item_kit_or_receipt,$quantity))
		{
			$data['error']=$this->lang->line('sales_unable_to_add_item');
		}
		
		if($this->sale_lib->out_of_stock($item_id_or_number_or_item_kit_or_receipt))
		{
			$data['warning'] = $this->lang->line('sales_quantity_less_than_zero');
		}
		$this->_reload($data);
	}

	function edit_item($line)
	{
		$data= array();

		$this->form_validation->set_rules('price', 'lang:items_price', 'required|numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required|numeric');

        $description = $this->input->post("description");
        $serialnumber = $this->input->post("serialnumber");
		$price = $this->input->post("price");
		$quantity = $this->input->post("quantity");
		$discount = $this->input->post("discount");


		if ($this->form_validation->run() != FALSE)
		{
			$this->sale_lib->edit_item($line,$description,$serialnumber,$quantity,$discount,$price);
		}
		else
		{
			$data['error']=$this->lang->line('sales_error_editing_item');
		}
		
		if($this->sale_lib->out_of_stock($this->sale_lib->get_item_id($line)))
		{
			$data['warning'] = $this->lang->line('sales_quantity_less_than_zero');
		}


		$this->_reload($data);
	}

	function delete_item($item_number)
	{
		$this->sale_lib->delete_item($item_number);
		$this->_reload();
	}

	function remove_customer()
	{
		$this->sale_lib->remove_customer();
		$this->_reload();
	}

	/**
	 * @author Kamaro Lambert
	 * @method to complete sale
	 */
 function complete()
	
   {
	//Get Customer if he exists if he doesn't -1 will be returned
	$customer_id=$this->sale_lib->get_customer();
	//Collecting the payments methods
	$data['payments']=$this->sale_lib->get_payments();
	
	//Get mode
	$mode=$this->sale_lib->get_mode();
	
	//Check if in the payment methods there is credit and that the customer were selected
	
	if (array_key_exists( $this->lang->line('sales_credit'), $data['payments']) AND $customer_id==-1)
	 { //No Customer Selected, let's return to the sales page and display error
	 	$data['error'] = $this->lang->line('sales_you_are_selling_on_credit_have_to_choose_customer');
	 	$this->_reload($data);
     	
	 }
	 else
	 {//Looks normal so far...
	 	
	   $cust_info=$this->Customer->get_info($customer_id);
	   
	  
	   
	   $data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
	   $data['customer_tin']=$cust_info->tin;
	   $data['customer_address']=$cust_info->address_1;
		
	   $data['cart']=$this->sale_lib->get_cart();
	   
   	   $data['subtotal']=round($this->sale_lib->get_subtotal());
	
	   $data['taxes']=$this->sale_lib->get_taxes();
		
       $data['total']=round($this->sale_lib->get_total());
	
	   $data['receipt_title']=$this->lang->line('sales_receipt');

	   $data['transaction_time']= date('m/d/Y h:i:s a');

	   
	   
	   
	
	   $employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
	
	   $comment = $this->sale_lib->get_comment();
		
       $emp_info=$this->Employee->get_info($employee_id);
	   
              
       $data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);

	   $data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
	
	    $total=$data['total'];
		
	    //Clear any Sale id in the cookies
	    $this->sale_lib->clear_sale_id();

		//SAVE sale to database
		$data['sale_number']=$this->Sale->save($data['cart'], $customer_id,$employee_id,$comment,$data['payments'],$sale_id=false,$total);
        
		$data['sale_id']='NS '.$data['sale_number'];
		
		if(strtolower($mode)==strtolower($this->lang->line('sales_return')))
		{
			$data['sale_id']='NR '.$data['sale_number'];
		}
		
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = $this->lang->line('sales_transaction_failed');
		}
		else
		{
			//save the sale per currency
			$this->Sale->save_currency_sales($data['sale_number'],$data['subtotal'],$total);
			
			/*
		   //Let's save the transaction to accounting 
		  foreach ($data['cart'] as $item) //let's go through all the sold items
		   		 {
		   		

		   	    $transaction_data=Array (
		       			'tamount' =>$item['price']*$item['quantity'],
		       			'tmemo' =>$this->input->post('comment')?$this->input->post('comment'):'' );
		       	
		   	
		   	
		   	if(strtolower($mode)==strtolower($this->lang->line('sales_sale')))
		   	{ 
		   		//We are selling
		   	
		   		  $transaction_data['tdesc']=$item['quantity'].' '. $item['name']. ' sold with receipt number '.$data['sale_id'];
		   		  $transaction_data['tfrom']=$item['acc_id'];//item accound ID

		   		 $transaction_data['tto']  =$this->config->item('local_cash_account');//Local currency accound ID
		   		
		   		   
		   		
		   	}
		   	elseif(strtolower($mode)==strtolower($this->lang->line('sales_return')))
		   	{ 
		   		//We are returning
		   		$transaction_data['tdesc']= 'Items returned with receipt number '.$data['sale_id'];
		   		$transaction_data['tto']  =$item['acc_id'];//item accound ID
		   				  
		   		
		   		//check if client is paying in foreign or local currency
		   		
		   		$transaction_data['tfrom']=$this->config->item('local_cash_account');;//Local currency accound ID
		   		
		   		
		   		
		   	}
		  
		   	
		   	//Save transaction to the database
		   	$this->_insert_transaction($transaction_data);
		   	
		   }
			*/
			if ($this->sale_lib->get_email_receipt() && !empty($cust_info->email))
			{
				$this->load->library('email');
				$config['mailtype'] = 'html';				
				$this->email->initialize($config);
				$this->email->from($this->config->item('email'), $this->config->item('company'));
				$this->email->to($cust_info->email); 

				$this->email->subject($this->lang->line('sales_receipt'));
				$this->email->message($this->load->view("sales/receipt_email",$data, true));	
				$this->email->send();
			}
		}
		
		//Write to a file locally
		$this->sdc_receipt($data);
		
		//Show the receipt
		$this->load->view("sales/receipt_non_printing",$data);
		
		$this->sale_lib->clear_all();
	 }
	
	}
/**
 * @Author Kamaro Lambert
 * @name   Receipt
 * @method to show the receipt of a done transaction
 * @param  numeric $sale_id
 */
function receipt($sale_id)
	
{
	

        $sale_info = $this->Sale->get_info($sale_id)->row_array();
       
        $this->sale_lib->copy_entire_sale($sale_id);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['subtotal']=round($this->sale_lib->get_subtotal());
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=round($this->sale_lib->get_total());
		$data['receipt_title']=$this->lang->line('sales_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a', strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();
		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$data['payment_type']=$sale_info['payment_type'];
		
		$data['sale_number']=$sale_id;
		
		//Get mode
		$mode=$this->sale_lib->get_mode();
		
		
		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
		}
		
		$data['sale_id']='NS '.$sale_id;
		
		//if it's return then change the Receipt title
		if(strtolower($mode)==strtolower($this->lang->line('sales_return')))
		{
			$data['sale_id']='NR '.$sale_id;
		}
		
		$this->load->view("sales/receipt_non_printing",$data);
		
		$this->sale_lib->clear_all();
		

	}
	
/**
 * @Author Kamaro Lambert
 * @name  printreceipt()
 * @method to print the receipt of a done transaction
 * @param  numeric $sale_id
 */
function printreceipt($sale_id)
	
{

        $sale_info = $this->Sale->get_info($sale_id)->row_array();

         
        $this->sale_lib->copy_entire_sale($sale_id);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['subtotal']=round($this->sale_lib->get_subtotal());
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=round($this->sale_lib->get_total());
		$data['receipt_title']=$this->lang->line('sales_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a', strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();
		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$data['payment_type']=$sale_info['payment_type'];
		
		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
		}
		
		//Get mode
		$mode=$this->sale_lib->get_mode();
		
	    $data['sale_id']='NS '.$sale_id;
		
		//if it's return then change the Receipt title
		if(strtolower($mode)==strtolower($this->lang->line('sales_return')))
		{
			$data['sale_id']='NR '.$sale_id;
		}
		
		$this->load->view("sales/".$this->config->item('print_receipt_size'),$data);
		
		$this->sale_lib->clear_all();
		

	}
	
	
	function edit($sale_id,$type='NS')
	{
		$data = array();

		$data['customers'] = array('' => 'No Customer');
		foreach ($this->Customer->get_all()->result() as $customer)
		{
			$data['customers'][$customer->person_id] = $customer->first_name . ' '. $customer->last_name;
		}

		$data['employees'] = array();
		foreach ($this->Employee->get_all()->result() as $employee)
		{
			$data['employees'][$employee->person_id] = $employee->first_name . ' '. $employee->last_name;
		}

		$data['sale_info'] = $this->Sale->get_info($sale_id)->row_array();
				// Detected payment method
		$data['payment_method'] =  substr( $data['sale_info']['payment_type'],0,strpos($data['sale_info']['payment_type'], ':'));
		$amountCurrency = substr($data['sale_info']['payment_type'],strpos($data['sale_info']['payment_type'], ':'));
		
		
		$data['payment_options']=array(
			$this->lang->line('sales_cash')  .' '.$amountCurrency => $this->lang->line('sales_cash'),
			$this->lang->line('sales_insurance') .' '.$amountCurrency => $this->lang->line('sales_insurance'),
			$this->lang->line('sales_check') .' '.$amountCurrency => $this->lang->line('sales_check'),
			$this->lang->line('sales_giftcard') .' '.$amountCurrency => $this->lang->line('sales_giftcard'),
			$this->lang->line('sales_debit') .' '.$amountCurrency => $this->lang->line('sales_debit'),
			$this->lang->line('sales_credit') .' '.$amountCurrency => $this->lang->line('sales_credit')
		);


		$data['sale_type']=$type;
		
		$data['body']=$this->load->view('sales/edit', $data,TRUE);
		$this->load->view('reports/page',$data);
	}
	
	
	function delete($sale_id)
	{
		$data = array();
		
		if ($this->Sale->delete($sale_id))
		{
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('sales/delete', $data);
		
	}
	
	function save($sale_id)
	{
		$sale_data = array(
			'sale_time' => date('Y-m-d', strtotime($this->input->post('date'))),
			'customer_id' => $this->input->post('customer_id') ? $this->input->post('customer_id') : null,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment'),
			'payment_type' => $this->input->post('payment_type')
		);
		
		if ($this->Sale->update($sale_data, $sale_id))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('sales_successfully_updated')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('sales_unsuccessfully_updated')));
		}
	}
	
	function _payments_cover_total()
	{
		$total_payments = 0;

		// Client is  paying in local money
		  foreach($this->sale_lib->get_payments() as $payment)
		  {
			$total_payments += $payment['payment_amount'];
		  }
		
		
		/* Changed the conditional to account for floating point rounding */
		if ( (strtolower($this->sale_lib->get_mode()) == strtolower($this->lang->line('sales_sale')) )
				 && ( ( to_currency_no_money( $this->sale_lib->get_total() ) - $total_payments ) > number_format(1e-6)) )
		{

			return false;
			
		}
		
		return true;
	}
	
	/**
	 * @Author kamaro Lambert
	 * @name
	 * @param unknown_type $data
	 */
	function _reload($data=array())
	{
		
		$person_info = $this->Employee->get_logged_in_employee_info();
	
	    $data['cart']=$this->sale_lib->get_cart();
	    
	   

	    $data['modes']=array('sale'=>$this->lang->line('sales_sale'),'return'=>$this->lang->line('sales_return'));

	    $data['types']=array('normal'=>$this->lang->line('sales_normal'),
                             'grossary'=>$this->lang->line('sales_grossary'),
	    		             'interprener'=>$this->lang->line('sales_interprener'));

	    $data['mode']=$this->sale_lib->get_mode();
	   
	    $data['items']   =$this->sale_lib->get_category_items()?$this->sale_lib->get_category_items():array();
	    $data['categories']   =$this->sale_lib->get_categories()?$this->sale_lib->get_categories():array();
        $data['type']=$this->sale_lib->get_type();
        $data['subtotal']=round($this->sale_lib->get_subtotal());
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_permission('items', $person_info->person_id);
		$data['comment'] = $this->sale_lib->get_comment();
		$data['email_receipt'] = $this->sale_lib->get_email_receipt();
		$data['payments_total']=$this->sale_lib->get_payments_total();
		$data['amount_due']=$this->sale_lib->get_amount_due();
		$data['payments']=$this->sale_lib->get_payments();
		
		
		//=============================================================================//
		//Let's get all currencies in the system
		$currencies= Model\Currency_model::find_by_Status(1);
		
		$currency_array=array();
		
		foreach($currencies as $currency)
		{
			$currency_array[$currency->curr_id]=$currency->Name;
		}
		
		$data['payment_currency']=$currency_array;
		
		$data['payment_options']=array(
			$this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
			$this->lang->line('sales_insurance') => $this->lang->line('sales_insurance'),
			$this->lang->line('sales_check') => $this->lang->line('sales_check'),
			$this->lang->line('sales_giftcard') => $this->lang->line('sales_giftcard'),
			$this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
			$this->lang->line('sales_credit') => $this->lang->line('sales_credit')
		);
		
		//get all Active currencies
		$currencies         = Model\Currency_model::find_by_Default(0);
		
		$data['currencies'] = $currencies;
		
		$dafault_currencies= Model\Currency_model::limit(1,0)->find_by_Default(1);
		foreach ($dafault_currencies as $def_currency )
		{
			$data['default_currency']= $def_currency;
			break;
		}
		

		//Get current payment currency
		$data['current_payment_currency']=$this->sale_lib->get_currency();
		$data['current_payment_currency_info']= Model\Currency_model::find($data['current_payment_currency']);
		//=============================================================================//
		
		$data['insurance_options']=$this->Myfinamodel->list_accountsby_parent($this->config->Item('insurance_parent_account'));
		
		
		$customer_id=$this->sale_lib->get_customer();
		
		if($customer_id!=-1)
		{
			$info=$this->Customer->get_info($customer_id);
			$data['customer']=$info->first_name.' '.$info->last_name;
			$data['customer_email']=$info->email;
		}
		
		$data['payments_cover_total'] = $this->_payments_cover_total();
		
		$this->load->view("sales/register",$data);
	}

	
    function cancel_sale()
    {
    	$this->sale_lib->clear_all();
    	$this->_reload();

    }
	/**
	 * @Author Kamaro Lambert
	 * @name suspend()
	 * @method to suspend sales so that they can be resumed later.
	 */
	function suspend()
	{
		$data['cart']=$this->sale_lib->get_cart();
		$data['subtotal']=round($this->sale_lib->get_subtotal());
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=$this->lang->line('sales_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a');
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->input->post('comment');
		$emp_info=$this->Employee->get_info($employee_id);
		$payment_type = $this->input->post('payment_type');
		$data['payment_type']=$this->input->post('payment_type');
		//Alain Multiple payments
		$data['payments']=$this->sale_lib->get_payments();
		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
		}

		$total_payments = 0;

		foreach($data['payments'] as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}

		//SAVE sale to database
		$data['sale_id']='POS '.$this->Sale_suspended->save($data['cart'], $customer_id,$employee_id,$comment,$data['payments'],$invoice_number=null,$date_issued=null,$po_number=null);
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = $this->lang->line('sales_transaction_failed');
		}
		$this->sale_lib->clear_all();
		$this->_reload(array('success' => $this->lang->line('sales_successfully_suspended_sale')));
	}
	/**
	 * @Author Kamaro Lambert
	 * @name  save_quotation
	 * @method to save quotations
	 */
	function save_quotation()
	{
		//Load models...
		//--------------
		$this->load->model('Sale_quotations');
		
		
		$data['cart']=$this->sale_lib->get_cart();
		$data['subtotal']=round($this->sale_lib->get_subtotal());
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=$this->lang->line('sales_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a');
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->input->post('comment');
		$emp_info=$this->Employee->get_info($employee_id);
		$payment_type = $this->input->post('payment_type');
		$data['payment_type']=$this->input->post('payment_type');
		
		//Alain Multiple payments
		$data['payments']=$this->sale_lib->get_payments();
		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
	
		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
		}
	
		$total_payments = 0;
	
		foreach($data['payments'] as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}
	
		//SAVE sale to database
		$data['sale_id']='POS '.$this->Sale_quotations->save($data['cart'], $customer_id,$employee_id,$comment,$data['payments']);
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = $this->lang->line('sales_transaction_failed');
		}
		$this->sale_lib->clear_all();
		$this->_reload(array('success' => $this->lang->line('sales_successfully_suspended_sale')));
	}
	
	/**
	 * @author Kamaro Lambert 
	 * @name suspended()
	 * @method to display all suspended sales
	 * 
	 */
	function suspended()
	{
		$data = array();
		$data['suspended_sales'] = $this->Sale_suspended->get_all()->result_array();
		$this->load->view('sales/suspended', $data);
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name   unsuspend()
	 * @method to unsuspend a given suspended sale
	 */
	function unsuspend()
	{
		$sale_id = $this->input->post('suspended_sale_id');
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_suspended_sale($sale_id);
		$this->Sale_suspended->delete($sale_id);
    	$this->_reload();
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name   sales_touch()
	 * @method to display all the categories and their items
	 * @param unknown_type $category_id
	 */
	function change_category($limit=20,$offset=0)
	{
		//Loading the model to be used
		$this->load->model('Mdl_items_categories','categories');
		
		//Setting the categories
		$this->sale_lib->set_categories($this->categories->get_categories(null, 20, 0));
		$this->_reload();
		
		
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name items_by_category()
	 * @method to get items by their categories
	 * @params int $category_id
	 */
	function items_by_category($category_id=-1)
	{
		//Load models....
		$this->load->model('item');
		
		//Setting the items for the selected category
		$this->sale_lib->set_category_items($this->item->items_by_category($category_id));
	   
		$this->_reload();
		
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to save perform new transaction to database
	 * @param  array $data
	 */
	function _insert_transaction($data)
	{
		
		$this->load->model('Myfinamodel');
		$insert['tdesc']   = $data['tdesc'];
		$insert['tamount'] = $data['tamount'];
		$insert['tmemo']   = $data['tmemo'];
		$insert['tdate']   = DATE('Y')."-".DATE('m')."-".DATE('d');
	
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
	
	/**
	 * @author Kamaro Lambert
	 *  Returns null if unable to determine drive letter (such as on a *nix box)
	 */
	
	function _driveLetter($path)
	{
		return (preg_match('/^[A-Z]:/i', $path = realpath($path))) ? $path[0] : null;
	}
	
	
	
	
}
?>