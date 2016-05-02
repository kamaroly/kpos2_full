<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class invoices_model extends CI_Model 
{

	function __construct()
	{
		parent::__construct();
		//$this->obj =& get_instance();
		//Create our temp tables to work with the data in our report
		//check if we have some params in URL
		if (strtotime($this->uri->segment(3)) AND strtotime($this->uri->segment(4)))
		{
			$this->Sale->create_sales_items_temp_table($this->uri->segment(3),$this->uri->segment(4));
			$this->Sale_quotations->create_quotations_items_temp_table($this->uri->segment(3),$this->uri->segment(4));
			$this->Receiving->create_receivings_items_temp_table($this->uri->segment(3),$this->uri->segment(4));
		}
		else
		{
			$this->Sale->create_sales_items_temp_table();
			$this->Sale_quotations->create_quotations_items_temp_table();
			$this->Receiving->create_receivings_items_temp_table();
		
		}
	}

	
	/**
	 * @Author Kamaro Lambert
	 * @method to add payments on the given invoice
	 * ----------------------------------------------
	 */
	
	function payment($invoice_data)
	{
	
		
	//Payment method already exist?
	//------------------------------
	if (!$this->payment_type_exists($invoice_data['sale_id'],$invoice_data['payment_type']))
		{ //Payment type doesn't exist
		if ($this->db->insert('sales_payments', $invoice_data))
		{
			
			return $invoice_data['sale_id'];
		}
		else
		 {
			return FALSE;
		 }
		}
	else 
	 {  //Payment type for this sale alread exist let's update it
	 	//------------------------------------------------------
	 	
	 	//Let's first find out what exist in the database
	 	$existing_data=$this->payment_type_exists($invoice_data['sale_id'],$invoice_data['payment_type']);
	 	
	 	//Now we have what is in the database, We are going to add the new submitted value
	 	$invoice_data['payment_amount']+=$existing_data['payment_amount'];
	 	
	 	//We have everything for now let's update the sales payment
	 	//---------------------------------------------------------
	 	
	 	$this->db->where($existing_data);
	 	if($this->db->update('sales_payments',$invoice_data))
	 	{//Payment was successfull updated
	 		return true;	 		
	 	}
	 	else 
	 	{//Something went wrong while updating...
	 		return false;
	 	}
	 	
	 	
	  }
	}

	
	/**
	 * @Author Kamaro Lambert
	 * @name   payment_type_exists()
	 * @params string  $payment_type
	 * @params numeric $sale_id
	 */
	function payment_type_exists($sale_id,$payment_type)
	{
	    $this->db->where(array('sale_id'=>$sale_id,
	    		               'payment_type'=>$payment_type))	;
	    $this->db->select('sale_id,payment_type,payment_amount');
	    
	    $query=$this->db->get('sales_payments');
	    //Let's check if the payment exists
	    //----------------------------------
	    if($query->num_rows()>0)
	    {
	    	return $query->row_array();
	    }
	    else
	    {
	    	return false;
	    }
	}
	// --------------------------------------------------------------------

	function delete_invoice($invoice_id)
	{
		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('invoice_payments'); // remove invoice payments

		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('invoice_histories'); // remove invoice_histories info

		$this->db->where('id', $invoice_id);
		$this->db->delete('invoices'); // remove invoice info

		$this->delete_invoice_items($invoice_id); // remove invoice items
	}

	// --------------------------------------------------------------------

	function delete_invoice_items($invoice_id)
	{
		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('invoice_items');
	}

	// --------------------------------------------------------------------

	/**
	 * @author Kamaro Lambert
	 * @namer get_total_paid
	 * @param numeric $sale_id ID of the invoice
	 * @return boolean
	 */
	function get_total_paid($sale_id=-1)
	{
		if($sale_id==-1)
		{
			return false;
		}
		else
		{
	     $this->db->select_sum('payment_amount');
	     $this->db->where(array('sale_id'=>$sale_id,
	     		                'credit' =>0));
	     $query = $this->db->get('sales_payments');
	     return $query->row();
		}
	}

	// --------------------------------------------------------------------

	function build_short_descriptions()
	{
		$limit = ($this->config->item('short_description_characters') != '') ? $this->config->item('short_description_characters') : 50;

		$short_descriptions = array();

		$this->db->select('sale_id, description AS work_description', FALSE);
		$this->db->group_by('sale_id');
		
		foreach($this->db->get('sales_items')->result() as $short_desc)
		{
			$short_descriptions[$short_desc->sale_id] = ($limit == 0) ? '' : '['.character_limiter($short_desc->work_description, $limit).']';
		}

		return $short_descriptions;
	}

	

	// --------------------------------------------------------------------

	function getInvoiceHistory($invoice_id)
	{
		$this->db->where('invoice_histories.sale_id', $invoice_id);
		$this->db->order_by('date_sent');

		return $this->db->get('invoice_histories');
	}

	// --------------------------------------------------------------------

	function getInvoicePaymentHistory($invoice_id)
	{
		$this->db->where('invoice_id', $invoice_id);
		$this->db->order_by('date_paid');
        
		return $this->db->get('invoice_payments');
	}

	// --------------------------------------------------------------------

	function getInvoices($invoice_id, $client_id,$status, $days_payment_due = 30, $offset=0, $limit=20,$recurring=false)
    {
    	
		return $this->_getInvoices($invoice_id, $client_id,$status, $days_payment_due, $offset, $limit,$recurring);
	}

	
	
	// --------------------------------------------------------------------
	/**
	 * @author Kamaro Lambert
	 * @param numeric $invoice_id the unique id of the invoice
	 * @param numeric $client_id the unique client id 
	 * @param string  $status the status of the invoice, whether it's open /closed or...
	 * @param numeric $days_payment_due number of days a invoice should be overdue
	 * @param numeric $offset the record index to return
	 * @param numeric $limit  number of records to return
	 * @param booleen $recurring is the invoice recurring or not?
	 * @return boolean
	 */

	function _getInvoices($invoice_id=null, $client_id=null, $status, $days_payment_due = 30, $offset=0, $limit=20,$recurring=FALSE)
    {
    	
		// check for any invoices first
		if ($this->db->count_all_results('sales') < 1)
		{
			return FALSE;
		}
         //mention that only invoices that belongs to the client can be retrieved
		
		if (is_numeric($invoice_id)!=null)
		{
					
			$this->db->where('sales_items_temp.sale_id', $invoice_id);
		}
        
		//Retrieving invoice for a specific clients
        
		if (is_numeric($client_id)!=null)
		{
			$this->db->where(array('customer_id '=> $client_id));
		}
		else
		{
			$this->db->where('sales_items_temp.sale_id IS NOT NULL');
		}

		if ($status == 'overdue')
		{
			$this->db->having("daysOverdue <= -$days_payment_due AND (ROUND(amount_paid, 2) < ROUND(subtotal, 2) OR amount_paid is null)", '', FALSE);
		}
		elseif ($status == 'open')
		{
			$this->db->having("(ROUND(amount_paid, 2) < ROUND(subtotal, 2) or amount_paid is null)", '', FALSE);
		}
		elseif ($status == 'closed')
		{
			
			$this->db->having('ROUND(amount_paid, 2) >= ROUND(subtotal, 2)', '', FALSE);
		}
         //Check if we are looking for the recurring invoices.
         //---------------------------------------------------
         if($recurring)
         {
         	//Yes we are looking for the recurring invoices 
         	//----------------------------------------------
         	$this->db->select('invoices_recurring.invoice_recurring_id,invoices_recurring.recur_start_date,invoices_recurring.recur_end_date,invoices_recurring.recur_frequency');
         	$this->db->join('invoices_recurring','invoices_recurring.sale_id=sales_items_temp.sale_id','INNER');
         	
         }
         $this->db->select('SALE_date dateIssued, TO_DAYS(SALE_date)- TO_DAYS(curdate()) AS daysOverdue');
         $this->db->select('sales_items_temp.sale_id, customer_id, customer_names,  SUM( quantity_purchased ) AS number_of_items');
         $this->db->select('(SELECT sum(payment_amount) FROM '.$this->db->dbprefix('sales_payments').' WHERE '.$this->db->dbprefix('sales_payments').'.sale_id='.$this->db->dbprefix('sales_items_temp').'.sale_id and credit=0)
         as amount_paid');
         $this->db->select('SUM( subtotal ) AS subtotal, SUM( total ) total, SUM( tax ) tax');
         $this->db->where("quantity_purchased >",0);
         $this->db->where("invoice_url_key IS NOT NULL ");
         $this->db->group_by('SALE_date, sale_id, customer_id, customer_names'); 
         $this->db->order_by('dateIssued desc');
 		 $this->db->offset($offset);
		 $this->db->limit($limit);

		return    $this->db->get('sales_items_temp');
	}

/**
 * @author Kamaro Lambert
 * @name   get_quotations()
 * @param numeric $quotation_id
 * @param numeric $client_id
 * @param string $status
 * @param numeric $offset
 * @param numeric $limit
 * @return boolean
 */
	function get_quotations($quotation_id=null, $client_id=null, $status=null,$offset=0, $limit=20)
    {
    	
		// check for any invoices first
		if ($this->db->count_all_results('sales_quotations') < 1)
		{
			return FALSE;
		}
         //mention that only invoices that belongs to the client can be retrieved
		
		if (is_numeric($quotation_id)!=null)
		{
					
			$this->db->where('quotations_items_temp.sale_id', $quotation_id);
		}
        
		//Retrieving invoice for a specific clients
        
		if (is_numeric($client_id)!=null)
		{
			$this->db->where(array('customer_id '=> $client_id));
		}
		else
		{
			$this->db->where('quotations_items_temp.sale_id IS NOT NULL');
		}

         $this->db->select('quotations_items_temp.sale_id,SALE_date as dateIssued, customer_id, customer_names,  SUM( quantity_purchased ) AS number_of_items');
         $this->db->select('SUM( subtotal ) AS subtotal, SUM( total ) total, SUM( tax ) tax');
         $this->db->where("quantity_purchased >",0);
         $this->db->where("invoice_url_key IS NOT NULL ");
         $this->db->group_by('SALE_date, sale_id, customer_id, customer_names'); 
         $this->db->order_by('SALE_date desc');
 		 $this->db->offset($offset);
		 $this->db->limit($limit);

		return    $this->db->get('quotations_items_temp');
	}
	// --------------------------------------------------------------------

	function lastInvoiceNumber($client_id)
	{
		if ($this->config->item('unique_invoice_per_client') === TRUE)
		{
			$this->db->where('client_id', $client_id);
		}

		$this->db->where('invoice_number != ""');
		$this->db->order_by("id", "desc"); 
		$this->db->limit(1);

		$query = $this->db->get('invoices');

		if ($query->num_rows() > 0)
		{
			return $query->row()->invoice_number;
		}
		else
		{
			return '0';
		}
	}

	// --------------------------------------------------------------------

	function uniqueInvoiceNumber($invoice_number)
	{
		$this->db->where('invoice_number', $invoice_number);

		$query = $this->db->get('invoices');

		$num_rows = $query->num_rows();

		if ($num_rows == 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// --------------------------------------------------------------------

	function uniqueInvoiceNumberEdit($invoice_number, $invoice_id)
	{
		$this->db->where('invoice_number', $invoice_number);
		$this->db->where('id != ', $invoice_id);
		$query = $this->db->get('invoices');

		$num_rows = $query->num_rows();

		if ($num_rows == 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	/**
	 * @author Kamaro Lambert
	 * @method to get the unique url key of the invoice
	 */
	public function get_url_key()
	{
		$this->load->helper('string');
		return random_string('unique');
	}
	
	/**
	 * @Author Kamaro Lambert
	 * @param numeric $invoice_id
	 * Function to mark the invoice as viewed by the client
	 */
	public function mark_viewed($invoice_id)
	{
		$this->db->select('invoice_status_id');
		$this->db->where('sale_id', $invoice_id);
	
		$invoice = $this->db->get('sales');
	    
		if ($invoice->num_rows())
		{
			
				$this->db->where('sale_id', $invoice_id);
				$this->db->update('sales',array('invoice_status_id'=>3));
			
		}
	}
	
	/**
	 * @Author Kamaro Lambert
	 * @param numeric $invoice_id
	 * Function to mark the invoice as sent by the client
	 */
	public function mark_sent($invoice_id)
	{
		$this->db->select('invoice_status_id');
		$this->db->where('sale_id', $invoice_id);
	
		$invoice = $this->db->get('sales');
	
		if ($invoice->num_rows())
		{
			if ($invoice->row()->invoice_status_id == 1)
			{
				$this->db->where('sale_id', $invoice_id);
				$this->db->set('invoice_status_id', 2);
				$this->db->update('sales');
			}
		}
	}
	
}
	
?>
