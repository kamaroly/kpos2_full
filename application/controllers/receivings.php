<?php
require_once ("secure_area.php");
class Receivings extends Secure_area
{
	function __construct()
	{
		parent::__construct('receivings');
		$this->load->library('receiving_lib');
	}

	/**
	 * @author Kamaro Lambert
	 * @param array $sales_data
	 * @return boolean
	 */
	
	function sdc_receipt($receiving_data=array())
	{
	
		
		//Loading the file helper
		//-----------------------
		$this->load->helper('file');
	
		//Preparing the data
		//-------------------
	
		$data['r_tin']=$receiving_data['supplier_tin'];
		
		
		$data['total']=$receiving_data['total'];
		$data['taxe']=($receiving_data['receipt_tax']!=NULL)? $receiving_data['receipt_tax']:'';
			   
		//Loading the view with the data
		$receipt_fiscal=$this->load->view('sdc/sdc_receiving_receipt',$data,true);
		//Removing any space in the filename
		$receipt_file_name=str_replace(' ', '_','Bill_'.$receiving_data['receiving_id']);
			
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

	function item_search()
	{
		$suggestions = $this->Item->get_item_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		$suggestions = array_merge($suggestions, $this->Item_kit->get_item_kit_search_suggestions($this->input->post('q'),$this->input->post('limit')));
		echo implode("\n",$suggestions);
	}

	function supplier_search()
	{
		$suggestions = $this->Supplier->get_suppliers_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}

	function select_supplier()
	{
		 $supplier_id = $this->input->post("supplier");
		
		$this->receiving_lib->set_supplier($supplier_id);
		$this->_reload();
	}

	function change_mode()
	{
		$mode = $this->input->post("mode");
		$this->receiving_lib->set_mode($mode);
		$this->_reload();
	}
	
	function add_tax()
	{
		echo $tax_amount = $this->input->post("receipt_tax");
		
		$this->receiving_lib->set_receipt_tax($tax_amount);
		
		redirect('receivings');
	}

	
	function delete_tax()
	{
		$this->receiving_lib->clear_receipt_tax();
		$this->_reload();
	}
	
	function add()
	{
		$data=array();
		$mode = $this->receiving_lib->get_mode();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");
		$quantity = $mode=="receive" ? 1:-1;

		if($this->receiving_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode=='return')
		{
			$this->receiving_lib->return_entire_receiving($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->receiving_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->receiving_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif(!$this->receiving_lib->add_item($item_id_or_number_or_item_kit_or_receipt,$quantity))
		{
			$data['error']=$this->lang->line('recvs_unable_to_add_item');
		}
		$this->_reload($data);
	}

	function edit_item($item_id)
	{
		$data= array();

		$this->form_validation->set_rules('price', 'lang:items_price', 'required|numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required|integer');
		$this->form_validation->set_rules('discount', 'lang:items_discount', 'required|integer');

    	$description = $this->input->post("description");
    	$serialnumber = $this->input->post("serialnumber");
		$price = $this->input->post("price");
		$quantity = $this->input->post("quantity");
		$discount = $this->input->post("discount");

		if ($this->form_validation->run() != FALSE)
		{
			$this->receiving_lib->edit_item($item_id,$description,$serialnumber,$quantity,$discount,$price);
		}
		else
		{
			$data['error']=$this->lang->line('recvs_error_editing_item');
		}

		$this->_reload($data);
	}

	function delete_item($item_number)
	{
		$this->receiving_lib->delete_item($item_number);
		$this->_reload();
	}

	function delete_supplier()
	{
		$this->receiving_lib->delete_supplier();
		$this->_reload();
	}
  /**
   * @author Kamaro Lambert
   * @name   complete()
   * @todo   Adding option for credit to that will create account for the supplier and debit it
   */
	function complete()
	{
		//First let's check if the supplier was chosen
		$supplier_id=$this->receiving_lib->get_supplier();
		
		$data['cart']=$this->receiving_lib->get_cart();
		
		$data['total']=$this->receiving_lib->get_total();
		
		$data['receipt_title']=$this->lang->line('recvs_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a');
		
		
		
		
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->input->post('comment');
		$emp_info=$this->Employee->get_info($employee_id);
		$payment_type = $this->input->post('payment_type');
		$data['payment_type']=$this->input->post('payment_type');
        
		if ($this->input->post('amount_tendered'))
		{
			$data['amount_tendered'] = $this->input->post('amount_tendered');
			$data['amount_change'] = to_currency($data['amount_tendered'] - round($data['total'], 2));
		}
		
		//Payments
		$data['payments_total']=$this->receiving_lib->get_payments_total();
		$data['payments']=$this->receiving_lib->get_payments();
		$data['amount_change']=to_currency($this->receiving_lib->get_amount_due() * -1);
		$data['amount_due']=$this->receiving_lib->get_amount_due();
		$data['payments_cover_total'] = $this->_payments_cover_total();
		
		//Taxes
		$receipt_tax=$this->receiving_lib->get_receipt_tax();
		
		
		if($receipt_tax!=-1 AND !empty($receipt_tax) AND is_numeric($receipt_tax))
		{
			$data['receipt_tax']=$receipt_tax;		
		}
		else
		{
			$data['receipt_tax']=null;
		}
		
		
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($supplier_id!=-1)
		{
			
			$suppl_info=$this->Supplier->get_info($supplier_id);
			$data['supplier']=$suppl_info->first_name.' '.$suppl_info->last_name;
			$data['supplier_tin']=$suppl_info->tin;
			
			//SAVE receiving to database
			$data['receiving_id']='RECV '.$this->Receiving->save($data['cart'], $supplier_id,$employee_id,$comment,$payment_type,false,$data['payments'],$data['total'],$receipt_tax);
			if ($data['receiving_id'] == 'RECV -1')
			{
				$data['error_message'] = $this->lang->line('receivings_transaction_failed');
			}
			else
			{
				//Let's save the transaction to accounting
				foreach ($data['cart'] as $item) //let's go through all the sold items
				{
					//Get mode
					$mode=$this->receiving_lib->get_mode();
						
					$transaction_data=Array (
							'tamount' =>$item['price']*$item['quantity'],
							'tmemo' =>$this->input->post('comment')?$this->input->post('comment'):'' );
						
						
						
					if(strtolower($mode)==strtolower($this->lang->line('recvs_receiving')))
					{
						//We are selling
							
						$transaction_data['tdesc']=$item['quantity'].' '. $item['name']. ' received with receipt note '.$data['receiving_id'] ;
						$transaction_data['tfrom']=$this->config->item('local_cash_account');//Local currency accound ID
						$transaction_data['tto']  =	$item['acc_id'];//item accound ID
							
					}
					elseif(strtolower($mode)==strtolower($this->lang->line('recvs_return')))
					{
						//We are returning
						$transaction_data['tdesc']= 'Items returned with return note '.$data['receiving_id'] ;
						$transaction_data['tto']  =$this->config->item('local_cash_account');//Local currency accound ID
						$transaction_data['tfrom']=$item['acc_id'];//item accound ID
			
					}
						//Save transaction to the database
					$this->_insert_transaction($transaction_data);
						
				}
			}
			
			$this->sdc_receipt($data);
			
			$this->load->view("receivings/receipt",$data);
		
			$this->receiving_lib->clear_all();
		}
		else 
		{   //No Supplier Selected
			$data['error'] = $this->lang->line('receivings_you_have_to_choose_supplier');
			$this->_reload($data);
			
		
		}

		
	}

	function receipt($receiving_id)
	{
		
		$receiving_info = $this->Receiving->get_info($receiving_id)->row_array();
		$this->receiving_lib->copy_entire_receiving($receiving_id);
		$data['cart']=$this->receiving_lib->get_cart();
		$data['total']=$this->receiving_lib->get_total();
		$data['total']=$this->receiving_lib->get_total();
		$data['receipt_title']=$this->lang->line('recvs_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a', strtotime($receiving_info['receiving_time']));
		
		
		
		$supplier_id=$this->receiving_lib->get_supplier();
		$emp_info=$this->Employee->get_info($receiving_info['employee_id']);
		$data['payment_type']=$receiving_info['payment_type'];
        
		
		
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($supplier_id!=-1)
		{
			$supplier_info=$this->Supplier->get_info($supplier_id);
			$data['supplier']=$supplier_info->first_name.' '.$supplier_info->last_name;
			$data['supplier_tin']=$supplier_info->tin;
		}
		$data['receiving_id']='RECV '.$receiving_id;
		$this->load->view("receivings/receipt",$data);
		$this->receiving_lib->clear_all();

	}

	function _reload($data=array())
	{
		$person_info = $this->Employee->get_logged_in_employee_info();
		$data['cart']=$this->receiving_lib->get_cart();
		$data['modes']=array('receive'=>$this->lang->line('recvs_receiving'),'return'=>$this->lang->line('recvs_return'));
		$data['mode']=$this->receiving_lib->get_mode();
		$data['total']=$this->receiving_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_permission('items', $person_info->person_id);
		
		//Payments
		$data['payments_total']=$this->receiving_lib->get_payments_total();
		$data['payments']=$this->receiving_lib->get_payments();
		$data['amount_change']=to_currency($this->receiving_lib->get_amount_due() * -1);
		$data['amount_due']=$this->receiving_lib->get_amount_due();
		$data['payments_cover_total'] = $this->_payments_cover_total();
		
		
		//Taxes
		$receipt_tax=$this->receiving_lib->get_receipt_tax();
		
		if($receipt_tax!=-1 AND !empty($receipt_tax) AND is_numeric($receipt_tax))
		{
			$data['receipt_tax']=$receipt_tax;
			
		}
		
		$data['payment_options']=array(
			$this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
			$this->lang->line('sales_check') => $this->lang->line('sales_check'),
			$this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
			$this->lang->line('sales_credit') => $this->lang->line('sales_credit')
		);

		 $supplier_id=$this->receiving_lib->get_supplier();
		
		if($supplier_id!=-1)
		{
			$info=$this->Supplier->get_info($supplier_id);
			 $data['supplier']=$info->first_name.' '.$info->last_name;
			
		}
		$this->load->view("receivings/receiving",$data);
	}

    function cancel_receiving()
    {
    	$this->receiving_lib->clear_all();
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
     * @name  _payments_cover_total()
     * @method to check if the payment were full covered
     */
    function _payments_cover_total()
    {
    	$total_payments = 0;
    
    	foreach($this->receiving_lib->get_payments() as $payment)
    	{
    		$total_payments += $payment['payment_amount'];
    	}
    
    	/* Changed the conditional to account for floating point rounding */
    	if ( ( $this->receiving_lib->get_mode() == 'sale' ) && ( ( to_currency_no_money( $this->receiving_lib->get_total() ) - $total_payments ) > 1e-6 ) )
    	{
    		return false;
    	}
    
    	return true;
    }
    /**
     * @Author Kamaro Lambert
     * @Method to add payment
     */
    //Alain Multiple Payments
	function add_payment()
	{		
		$data=array();
		//Setting validation rules
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'numeric');
		
		//Let's validate
		if ($this->form_validation->run() == FALSE)
		{ 
			//Set error message to be displayed
			$data['error']=$this->lang->line('sales_must_enter_numeric');
			//Reload the data	
 			$this->_reload($data);
 			return;
		}
		//get the payment type
		$payment_type=$this->input->post('payment_type');
	
		$payment_amount=$this->input->post('amount_tendered');
		
		
		if( !$this->receiving_lib->add_payment( $payment_type, $payment_amount ) )
		{
			$data['error']='Unable to Add Payment! Please try again!';
		}
		
		$this->_reload($data);
	}
    /**
     * @Author Kamaro Lambert
     * @name   delete_payments
     */
	function delete_payment( $payment_id )
	{
	
		$this->receiving_lib->delete_payment( $payment_id );
		$this->_reload();
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