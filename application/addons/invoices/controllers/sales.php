<?php if (!defined('BASEPATH'))    exit('No direct script access allowed');

/*
 * Kamaro Point of sale
 * 
 * A Small and Medium enterprise web based Retail and invoicing system
 *
 * @package		KPos
 * @subpackage  Invoicing
 * @author		Kamaro Lambert
 * @copyright	Copyright (c) 2012 - 2013, Kamaro Lambert
 * @license		http://www.kamaroly.com/support/page/license-agreement
 * @link		http://www.kamaroly.com
 * 
 */
require_once (APPPATH."Controllers/secure_area.php");

class Sales extends Secure_area
{
	/**
	 * @author Kamaro Lambert
	 * @method to instastiate the need resources
	 */
	function __construct()
	{
		parent::__construct();
		//Load library to be used
		//-----------------------
		$this->load->library('sale_lib');
		
		//Load languages
		//---------------
		$this->load->language('invoices');
		
		
		
	}

	/**
	 * @Author Kamaro Lambert
	 * @name index()
	 * @method to enter our Controller
	 */
	function index()
	{
		
	    $this->_reload();
	}

	/**
	 * @Author Kamaro Lambert
	 * @name  item_search() 
	 * @method to search in the database the items based on the keywords
	 * @param string $keyword
	 * @param numeric $limit
	 */
	function item_search($keyword=null,$limit=20)
	{
		//Receiving the submitted data
		//----------------------------
		$key=$this->input->post('q')?$this->input->post('q'):$keyword;
		$limit=$this->input->post('limit')?$this->input->post('limit'):$limit;
		
		//Getting database item based on the provided keyword
		//---------------------------------------------------
		$suggestions = $this->Item->get_item_search_suggestions($key,$limit);
		
		//If we have item kits created , let's try to merge them with items found
		//------------------------------------------------------------------------
		$suggestions = array_merge($suggestions, $this->Item_kit->get_item_kit_search_suggestions($key,$limit));
		
		//Display the result on the page so that Jquery can read it
		//---------------------------------------------------------
		echo implode("\n",$suggestions);
	}

	/**
	 * @Author Kamaro Lambert
	 * @name customer_search 
	 * @method to search client based on the submitted keyword
	 */
	function customer_search()
	{
		//Receive the posted keywords and limits then search database for suggestions
		//--------------------------------------------------------------------------
		$suggestions = $this->Customer->get_customer_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		//Display the result on the page so that Jquery can read it
		//---------------------------------------------------------
		echo implode("\n",$suggestions);
	}

	/**
	 * @uathor Kamaro Lambert
	 * @name Select_customer()
	 * @method to set the customer based on the submited customer id
	 */
	function select_customer()
	{
		//Receiving the submitted customer id and assign it to the value
		//-------------------------------------------------------------
		if ($this->input->post("customer")!='')
		{
		$customer_id = $this->input->post("customer");
		
		//Let's set the customer id in the session with the help of the sale library
		//--------------------------------------------------------------------------
		$this->sale_lib->set_customer($customer_id);
		
		//Let's reload the cart with new data
		//------------------------------------
		$this->_reload();
		}
		else
		{
			$this->_reload();
		}
	}

	/**
	 * @author Kamaro Lambert
	 * @name Change_mode()
	 * @method to change mode and type of salling
	 * 
	 */
	function change_mode()
	{
	     //Receiving new submitted data 
	     //---------------------------
	    $mode = $this->input->post("mode");
		$type = $this->input->post("type");
		
		//Set new submitted data
		//----------------------
		$this->sale_lib->set_mode($mode);
		$this->sale_lib->set_type($type);
		
		//Let's reload the cart with new data
		//------------------------------------
		$this->_reload();
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name   change_currency()
	 * @method to change salling Currency
	 * 
	 */
	function change_currency()
	{
	
		//Receiving new submitted data
		//---------------------------
		$currency = $this->input->post("payment_currency");
		//Set new submitted data
		//----------------------
		$this->sale_lib->set_currency($currency);
		//Let's reload the cart with new data
		//------------------------------------
		$this->_reload();
	}
	/**
	 * @author Kamaro Lambert
	 * @name   set_comment
	 * @method to set comment
	 */
	function set_comment() 
	{
 	  $this->sale_lib->set_comment($this->input->post('comment'));
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name   set_po_number
	 * @method to set Purchase order number
	 */
	function set_po_number()
	{
		if($this->input->post('po_number')!='')
		{
		$this->sale_lib->set_po_number($this->input->post('po_number'));
		}
		//Load the cart to the view
		$this->_reload();
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name   set_date_issued()
	 * @method to set Purchase order number
	 */
	function set_date_issued()
	{
		
		if($this->input->post('date_issued')!='')
		{
		  //Convert the date then save it in the sessions
		 $this->sale_lib->set_date_issued(DATE('Y-m-d',strtotime($this->input->post('date_issued'))));
		}
		//Load the cart to the view
		$this->_reload();
	}
	
	function set_invoice_number()
	{
		//Setting the new invoice number
		if($this->input->post('invoice_number')!='')
			{ //Setting the new invoice number
		     $this->sale_lib->set_invoice_number($this->input->post('invoice_number'));
		    }
		//Load the cart to the view
		$this->_reload();
		
	}
	
	function  editsale($sale_id=null)
	{
		if($sale_id==null)
		{
			show_404();
		}
		
			$this->sale_lib->copy_entire_sale($sale_id);
			$this->_reload();
	}
	/**
	 * @author Kamaro Lambert
	 * @name   Set_email_receipt()
	 */
	function set_email_receipt()
	{
 	  $this->sale_lib->set_email_receipt($this->input->post('email_receipt'));
	}

	/**
	 * @author Kamaro Lambert
	 * @name  add_payment()
	 * @method to add Payments of a sale
	 */
	
	function add_payment()
	{		
		//Initialise the data array
		//-------------------------
		$data=array();
		
		//Run form rules for validation
		//-----------------------------
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'numeric');
		
		//Check if the submited data are valid
		//------------------------------------
		if ($this->form_validation->run() == FALSE)
		{
			//Something went wrong check if it's a gift card then return Giftcard related error
			//--------------------------------------------------------------------------------
			if ( $this->input->post('payment_type') == $this->lang->line('sales_gift_card') )
			{
				$data['error']=$this->lang->line('sales_must_enter_numeric_giftcard');
			}
			else
			{
				$data['error']=$this->lang->line('sales_must_enter_numeric');
			}
			
			//Reload the cart with errors
			//---------------------------
 			$this->_reload($data);
 			return;
		}
		
		//So far everything looks fine let's collect submitted payment
		//-----------------------------------------------------------
		$payment_type=$this->input->post('payment_type');
		
		//Is it a gift card?
		//-------------------
		if ( $payment_type == $this->lang->line('sales_giftcard') )
		{  
			//it's a gift card let's then do some operations with it 
			//-------------------------------------------------------
			
			$payments = $this->sale_lib->get_payments();
			$payment_type=$this->input->post('payment_type').':'.$payment_amount=$this->input->post('amount_tendered');
			$current_payments_with_giftcard = isset($payments[$payment_type]) ? $payments[$payment_type]['payment_amount'] : 0;
			$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $current_payments_with_giftcard;
			
			//Does gift card still have values?
			//---------------------------------
			if ( $cur_giftcard_value <= 0 )
			{
				//Gift card doesn't have enough value to purchase
				//----------------------------------------------
				$data['error']='Giftcard balance is '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) ).' !';
				
				//Reload the cart with worning
				//---------------------------
				$this->_reload($data);
				return;
			}
			elseif ( ( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ) > 0 )
			{
				//Gift card has some money let's use it.
				//-------------------------------------
				$data['warning']='Giftcard balance is '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ).' !';
			}
			//Let's get payment amount
			$payment_amount=min( $this->sale_lib->get_total(), $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) );
		}
		else
		{//it's not a gift card
			$payment_amount=$this->input->post('amount_tendered');
		}
		
		//Get account ID for the payment method used
		if ( $payment_type == $this->lang->line('sales_giftcard') )
		{
			$accid=39; //Account ID for Gift
		}
		elseif ($payment_type == $this->lang->line('sales_cash'))
		{
			$accid=37; //Account ID for Stock
		}
		elseif ($payment_type == $this->lang->line('sales_check'))
		{
			$accid=40; // Account ID FOR bacnk
		}
		elseif ( $payment_type == $this->lang->line('sales_insurance') )
		{
			$accid=$this->input->post('insurance_type');
		}
		
		
		if ( $payment_type == $this->lang->line('sales_insurance') )
		{
			$payment_type.=' '.$this->Myfinamodel->list_accountsby_id($accid)->cname;
		}
		
		if( !$this->sale_lib->add_payment( $payment_type, $payment_amount,$accid ) )
		{
			$data['error']='Unable to Add Payment! Please try again!';
		}
		//Reload the cart with current updated data
		//-----------------------------------------
		$this->_reload($data);
	}
    /**
     * @Author Kamaro Lambert
     * @name delete_payment();
     * @method to delete payments added or done
     */
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

	
	/**
	 * @author Kamaro Lambert
	 * @name   edit_item()
	 * @method to edit item which is currently in the cart
	 * @param numeric $line
	 */
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

	/**
	 * @Author Kamaro Lambert
	 * @method to delete item which is in cart
	 * @param numeric $item_number
	 */
	function delete_item($item_number)
	{
		$this->sale_lib->delete_item($item_number);
		$this->_reload();
	}
    /**
     * @author Kamaro Lambert
     * @method to remove the client which was set for the current cart
     * 
     */
	function remove_customer()
	{
		$this->sale_lib->remove_customer();
		$this->_reload();
	}

	/**
	 * @Author Kamaro Lambert
	 * @method to complete sale or complete the creating invoice
	 */
  function complete()
	
   {
   
	//Get Customer if he exists if he doesn't -1 will be returned
	$customer_id=$this->sale_lib->get_customer();
	//Collecting the payments methods
	$data['payments']=$this->sale_lib->get_payments();
	
	//Check if in the payment methods there is credit and that the customer were selected
	
	if ($customer_id==-1)
	 { //No Customer Selected, let's return to the sales page and display error
	 	$data['error'] = $this->lang->line('sales_please_select_client');
	 	$this->_reload($data);
     	
	 }
	 else
	 {
	 //Looks normal so far...
	 
	   $cust_info=$this->Customer->get_info($customer_id);
	   
	   $data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
	  
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
	
	   //Invoice information
	   //-------------------
	   $provisioned_sale_id=$this->Sale->get_last_sale_id()+1;
	   
	   $data['invoice_number']=($this->sale_lib->get_invoice_number()!='')?$this->sale_lib->get_invoice_number():'Invoice_'.$provisioned_sale_id;
	   $data['date_issued']=$this->sale_lib->get_date_issued();
	   $data['po_number']=$this->sale_lib->get_po_number();

	   $sale_id=($this->sale_lib->get_sale_id()!=null)?$this->sale_lib->get_sale_id():false;
		
        /**
         * @todo add the possibility to modify the sales
         */
		//SAVE sale to database
		//Check if it's updating existing sale
		
		$data['sale_number']=$this->Sale->save($data['cart'], $customer_id,$employee_id,$comment,$data['payments'],$sale_id,$total,$data['invoice_number'],$data['date_issued'],$data['po_number'],$sale_id);
        
		$data['sale_id']='POS '.$data['sale_number'];
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = $this->lang->line('sales_transaction_failed');
		}
		else
		{
		   //Let's save the transaction to accounting 
		  foreach ($data['cart'] as $item) //let's go through all the sold items
		   		 {
		   		//Get mode 	
		       	$mode=$this->sale_lib->get_mode();

		   	    $transaction_data=Array (
		       			'tamount' =>$item['price']*$item['quantity'],
		       			'tmemo' =>$this->input->post('comment')?$this->input->post('comment'):'' );
		       	
		   	
		   	
		   	if(strtolower($mode)==strtolower($this->lang->line('sales_sale')))
		   	{ 
		   		//We are selling
		   	
		   		  $transaction_data['tdesc']=$item['quantity'].' '. $item['name']. ' sold with receipt number '.$data['sale_id'];
		   		  $transaction_data['tfrom']=$item['acc_id'];//item accound ID

		   		  //check if client is paying in foreign or local currency
		   		  if($this->sale_lib->get_currency()==$this->config->item('currency_symbol'))
		   		  {   // Client is  paying in local money
		   		  	
		   		  	$transaction_data['tto']  =$this->config->item('local_cash_account');//Local currency accound ID
		   		  	
		   		  }
		   		  if($this->sale_lib->get_currency()==$this->config->item('exchange_rate_name'))
		   		  {  // Client is  paying in foreign money
		   		  
		   		  	$transaction_data['tto']  =$this->config->item('foreign_cash_account');//Foreign currency accound ID
		   		  	
		   		  }
		   		   
		   		
		   	}
		   	elseif(strtolower($mode)==strtolower($this->lang->line('sales_return')))
		   	{ 
		   		//We are returning
		   		$transaction_data['tdesc']= 'Items returned with receipt number '.$data['sale_id'];
		   		$transaction_data['tto']  =$item['acc_id'];//item accound ID
		   				  
		   		
		   		//check if client is paying in foreign or local currency
		   		if($this->sale_lib->get_currency()==$this->config->item('currency_symbol'))
		   		{   // Client is  paying in local money
		   		
		   		$transaction_data['tfrom']=$this->config->item('local_cash_account');;//Local currency accound ID
		   		
		   		}
		   		if($this->sale_lib->get_currency()==$this->config->item('exchange_rate_name'))
		   		{  // Client is  paying in foreign money
		   		 
		   		$transaction_data['tfrom']=$this->config->item('foreign_cash_account');;//foreign currency accound ID
		   		
		   		}
		   		
		   	}
		  
		   	
		   	//Save transaction to the database
		   	$this->_insert_transaction($transaction_data);
		   	
		   }
			
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
		
		$this->sale_lib->clear_all();
		redirect('invoices/view/'.$data['sale_number']);
		
		
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
		
	
		
		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
		}
		$data['sale_id']='POS '.$sale_id;
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
		$data['sale_id']='POS '.$sale_id;
		
		$this->load->view("sales/".$this->config->item('print_receipt_size'),$data);
		
		$this->sale_lib->clear_all();
		

	}
	
	
	function edit($sale_id)
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
			'comment' => $this->input->post('comment')
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

		//check if client is paying in foreign or local currency
		if($this->sale_lib->get_currency()==$this->config->item('currency_symbol'))
		{   // Client is  paying in local money
		  foreach($this->sale_lib->get_payments() as $payment)
		  {
			$total_payments += $payment['payment_amount'];
		  }
		}
		if($this->sale_lib->get_currency()==$this->config->item('exchange_rate_name'))
		{  // Client is  paying in local money
		
		  foreach($this->sale_lib->get_payments() as $payment)
		  {
			$total_payments += $payment['payment_amount_foreign'];
		  }
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
	public function _reload($data=array())
	{
		
		//Load models to be used
		//-----------------------
		
		$person_info = $this->Employee->get_logged_in_employee_info();
	
	    $data['cart']=$this->sale_lib->get_cart();

	    $data['modes']=array('sale'=>$this->lang->line('sales_sale'),'return'=>$this->lang->line('sales_return'));

	    $data['types']=array('normal'=>$this->lang->line('sales_normal'),
                             'grossary'=>$this->lang->line('sales_grossary'),
	    		             'interprener'=>$this->lang->line('sales_interprener'));
	    
	    $data['mode']=$this->sale_lib->get_mode();
	    $data['currency']=$this->sale_lib->get_currency();
	    $data['items']   =$this->sale_lib->get_category_items()?$this->sale_lib->get_category_items():array();
	    $data['categories']   =$this->sale_lib->get_categories()?$this->sale_lib->get_categories():array();
        $data['type']=$this->sale_lib->get_type();
        $data['subtotal']=round($this->sale_lib->get_subtotal());
		$data['taxes']=$this->sale_lib->get_taxes();
		
		$data['total']=$this->sale_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_permission('items', $person_info->person_id);
		$data['comment'] = ($this->sale_lib->get_comment()!='')?$this->sale_lib->get_comment():$this->config->Item('return_policy');
		$data['email_receipt'] = $this->sale_lib->get_email_receipt();
		$data['payments_total']=$this->sale_lib->get_payments_total();
		$data['amount_due']=$this->sale_lib->get_amount_due();
		$data['payments']=$this->sale_lib->get_payments();
		
		$data['payment_currency']=array(
			$this->config->item('currency_symbol') => $this->config->item('currency_symbol'),
			$this->config->item('exchange_rate_name') => $this->config->item('exchange_rate_name'),
		
		);
		
		//Invoice information
		//-------------------
		$provisioned_sale_id=$this->Sale->get_last_sale_id()+1;
		
		$data['invoice_number']=($this->sale_lib->get_invoice_number()!='')?$this->sale_lib->get_invoice_number():'Invoice_'.$provisioned_sale_id;
		$data['date_issued']=$this->sale_lib->get_date_issued();
		$data['po_number']=$this->sale_lib->get_po_number();
		$data['invoice_url_key']=($this->sale_lib->get_invoice_url_key()!=null)?$this->sale_lib->get_invoice_url_key():null;
		$data['sale_id']=($this->sale_lib->get_sale_id()!=null)?$this->sale_lib->get_sale_id():null;
		
		$data['payment_options']=array(
			$this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
			$this->lang->line('sales_insurance') => $this->lang->line('sales_insurance'),
			$this->lang->line('sales_check') => $this->lang->line('sales_check'),
			$this->lang->line('sales_giftcard') => $this->lang->line('sales_giftcard'),
			$this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
			$this->lang->line('sales_credit') => $this->lang->line('sales_credit')
		);
        
		
		
		$data['insurance_options']=$this->Myfinamodel->list_accountsby_parent($this->config->Item('insurance_parent_account'));
		
		$customer_id=$this->sale_lib->get_customer();
		if($customer_id!=-1)
		{
			$info=$this->Customer->get_info($customer_id);
			$data['customer']=$info->first_name.' '.$info->last_name;
			$data['client_info']=$data['client']=$info;
			$data['customer_email']=$info->email;
		}
		
		$data['payments_cover_total'] = $this->_payments_cover_total();

		$data['right_footer']=$this->load->view('invoices/invoices/invoice_header_new',$data,true);
		$data['body']=$this->load->view("invoices/invoices/register",$data,true);
		$data['right_header']=$this->load->view('invoices/invoices/invoice_header_new',$data,true);
		
		
		$this->load->view('invoices/page',$data);
	}
   
	/**
	 * @author Kamaro Lambert
	 * @method to cancel the current cart and delete all things in the cart
	 */
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
		$data['taxes']=$total=$this->sale_lib->get_taxes();
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
		//Invoice information
		//-------------------
		$provisioned_sale_id=$this->Sale->get_last_sale_id()+1;
		
		$data['invoice_number']=($this->sale_lib->get_invoice_number()!='')?$this->sale_lib->get_invoice_number():'Invoice_'.$provisioned_sale_id;
		$data['date_issued']=$this->sale_lib->get_date_issued();
		$data['po_number']=$this->sale_lib->get_po_number();
		
		//SAVE sale to database
		$data['sale_id']='POS '.$this->Sale_suspended->save($data['cart'], $customer_id,$employee_id,$comment,$data['payments'],$sale_id=false,$total,$data['invoice_number'],$data['date_issued'],$data['po_number']);
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = $this->lang->line('common_operation_failed');
		}
		$this->sale_lib->clear_all();
		$this->_reload(array('success' => $this->lang->line('sales_successfully_saved_draft')));
	}
	/**
	 * @Author Kamaro Lambert
	 * @name  save_quotation
	 * @method to save quotations
	 * 
	 */
	function save_quotation()
	{
		//Load models...
		//--------------
		$this->load->model('Sale_quotations');
		
		$customer_id=$this->sale_lib->get_customer();
		//Let's check if the user has provided the customer he's selling to .
		if ($customer_id==-1)
		{ //No Customer Selected, let's return to the sales page and display error
		$data['error'] = $this->lang->line('sales_please_select_client');
		$this->_reload($data);
		
		}
		else
		{
		$data['cart']=$this->sale_lib->get_cart();
		$data['subtotal']=round($this->sale_lib->get_subtotal());
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$total=$this->sale_lib->get_total();
		$data['receipt_title']=$this->lang->line('sales_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a');
		
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
	     
		/**
		 * @todo Add the posibility to modify the existing quotation
		 */
		//Invoice information
		//-------------------
		$provisioned_sale_id=$this->Sale->get_last_sale_id()+1;
		
		$data['invoice_number']=($this->sale_lib->get_invoice_number()!='')?$this->sale_lib->get_invoice_number():'Invoice_'.$provisioned_sale_id;
		$data['date_issued']=$this->sale_lib->get_date_issued();
		$data['po_number']=$this->sale_lib->get_po_number();
		
		//SAVE sale to database
		$data['sale_id']='POS '.$this->Sale_quotations->save($data['cart'], $customer_id,$employee_id,$comment,$data['payments'],$sale_id=false,$total,$data['invoice_number'],$data['date_issued'],$data['po_number']);
		
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = $this->lang->line('common_operation_failed');
		}
		$this->sale_lib->clear_all();
		$this->_reload(array('success' => $this->lang->line('sales_successfully_saved_quotation')));
		}
	}
	
	/**
	 * @author Kamaro Lambert 
	 * @name suspended()
	 * @method to display all suspended sales
	 * 
	 */
	function suspended()
	{
		$customer_id=$this->sale_lib->get_customer();
		if ($customer_id==-1)
		{ //No Customer Selected, let's return to the sales page and display error
		$data['error'] = $this->lang->line('sales_please_select_client');
		$this->_reload($data);
		
		}
		else
		{
		$data = array();
		$data['suspended_sales'] = $this->Sale_suspended->get_all()->result_array();
		$this->load->view('sales/suspended', $data);
		}
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name suspended()
	 * @method to display all suspended sales
	 *
	 */
	function quotations($offset=0)
	{
		//configuring pagination...
		//-------------------------
		$limit = 1; //how many records to display
	
		//do a quick db lookup to see how many records we are dealing with
		$config['total_rows'] =$this->Sale_quotations->get_all()->num_rows();
		
		//our URI segments are built from the site address, posts controller and the index method.
		$config['base_url'] = site_url('/invoices/sales/quotations/');
		//using our limit variable to set the number of records to display
		$config['per_page'] = $limit;
			
		//initializing the configuration
		$this->pagination->initialize($config);
			
		//creating pagination links based on the configuration
		echo $data["pagination_links"] = $this->pagination->create_links(5);
		
		
		$data = array();
		$data['quotation_sales'] = $this->Sale_quotations->get_all($limit,$offset)->result();
		
		
		$this->load->view('invoices/invoices/quotations_table', $data);
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
	 * 
	 * @name   editquote()
	 * @method to unsuspend a given suspended sale
	 */
	function editquote($quotation_id=null)
	{
		
		//check if the id was provided
		if($quotation_id==null or !$this->Sale_quotations->exists($quotation_id))
		{
			show_404();
		}
		
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_quotation($quotation_id);
		$this->Sale_quotations->delete($quotation_id);
		$this->_reload();
	}	
	
	/**
	 * @author Kamaro Lambert
	 * @name   unsuspend()
	 * @method to unsuspend a given suspended sale
	 */
	function quotetoinvoice($quotation_id=null)
	{
		//check if the id was provided
		if($quotation_id==null or !$this->Sale_quotations->exists($quotation_id))
		{
			show_404();
		}
	
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_quotation($quotation_id);
		$this->Sale_quotations->delete($quotation_id);
		$this->complete();
		redirect('invoices');
	}
	/**
	 * @author Kamaro Lambert
	 * @name   sales_touch()
	 * @method to display all the categories and their items
	 * @param numeric $category_id
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
	
}
?>