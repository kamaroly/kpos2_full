<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package    Kinvoice
 * @subpackage Invoices
 * @name       Invoices
 * @category   Controller
 * @author     Kamaro Lambert
 * @copyright  Kamaro Lambert
*/

include(APPPATH.'controllers/secure_area.php');

class View extends Secure_area
 {

 	function __construct()
 	{
 		parent::__construct();
 		/**Loading our models   we will need**/
 		
		$this->load->model('invoices/invoice/invoices_model');
		
		
 	}
 	/**
 	 * @name view
 	 * @author Derrek , Stongly modified by Kamaro Lambert
 	 * @param integer $id
 	 * @method to view one selected invoice
 	 */
 	
 	public function invoice($invoice_url_key)
 	{
 		
 		//Loading language
 		//-----------------
 		$this->lang->load('date');
 	
 		//Loading helpers
 		//------------------
 		$this->load->helper('file','js_calendar');
 	
 		//Load sales library
 		$this->load->library('Sale_lib');
 	
 		//Get Date information
 		//--------------------
 		$this->data['invoiceDate'] = date("Y-m-d");
 	
 	
 	
 		//Get sales information
 		//---------------------
 		$data['sale_info']=$sale_info = $this->Sale->get_info_for_guest($invoice_url_key)->row_array();
 		
 		//Getting the sale_id of the sale
 		//------------------------------
 		$sale_id=$sale_info['sale_id'];
 		
 	  
 		if (!$sale_id)
 		{
 			show_404();
 		}
 		$this->sale_lib->copy_entire_sale($sale_id);
 		$data['cart']=$this->sale_lib->get_cart();
 	
 		 $this->invoices_model->mark_viewed($sale_id);
 		 
 		//Payments
 		//--------------
 		$data['payments']=$this->sale_lib->get_payments();
 		$data['total_paid']=$this->invoices_model->get_total_paid($sale_id)->payment_amount;
 		$data['subtotal']=round($this->sale_lib->get_subtotal());
 		$data['taxes']=$this->sale_lib->get_taxes();
 		$data['total']=round($this->sale_lib->get_total());
 		$data['total_outstanding']=$data['total']-$data['total_paid'];
 		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
 	
 	
 		//Receipt information
 		//-------------------
 		$data['receipt_title']=$this->lang->line('sales_receipt');
 		$data['date_invoice_issued']= date('m/d/Y h:i:s a', strtotime($sale_info['sale_time']));
 		$customer_id=$this->sale_lib->get_customer();
 		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
 		$data['payment_type']=$sale_info['payment_type'];
 		$data['invoiceHistory'] = $this->invoices_model->getInvoiceHistory($sale_id);
 		$date_invoiced_time=strtotime($data['date_invoice_issued']);
 	
 		$data['sale_number']=$sale_id;
 		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
 		
 		//Get Status information
 		//-------------------------
 		if ($data['total_paid'] >= $data['total'])
 		{
 			// paid invoices
 			$data['status'] = '<span>'.$this->lang->line('invoice_closed').'</span>';
 			$data['class']='paid';
 		}
 		elseif (mysql_to_unix($date_invoiced_time) >= time()-(30 * 60*60*24))
 		{
 			// owing less then 30 days
 			$data['status'] = '<span>'.$this->lang->line('invoice_open').'</span>';
 			$data['class']='unpaid';
 		}
 		else
 		{
 			// owing more then 30 days
 			$due_date = $date_invoiced_time + (30 * 60*60*24);
 			$data['class']='unpaid';
 			$data['status'] = '<span class="error">'.timespan(mysql_to_unix($date_invoiced_time) + (30 * 60*60*24), now()). ' '.$this->lang->line('invoice_overdue').'</span>';
 		}
 	
 		if($customer_id!=-1)
 		{
 			$data['client']=$this->Customer->get_info($customer_id);
 			 
 		}
 	
 		
 	    $this->load->view("guest/invoices_view",$data);
 		 
 		$this->sale_lib->clear_all();
 	
 	
 	}
 	
    public function generate_invoice_pdf($invoice_url_key, $stream = TRUE, $invoice_template = NULL)
    {
    
    	//Get sales information
    	//---------------------
    	$data['sale_info']=$sale_info = $this->Sale->get_info_for_guest($invoice_url_key)->row_array();
    		
    	//Getting the sale_id of the sale
    	//------------------------------
    	$sale_id=$sale_info['sale_id'];
    	

    	if (!$sale_id)
    	{
    		show_404();
    	}

    	$this->load->helper('pdf');
    	
    	generate_invoice_pdf($sale_info['sale_id']);
        
    }

    public function quote($quote_url_key)
    {
       
 		//Loading language
 		//-----------------
 		$this->lang->load('date');
 	
 		//Loading helpers
 		//------------------
 		$this->load->helper('file','js_calendar');
 	
 		//Load sales library
 		$this->load->library('Sale_lib');
 	
 		//Get Date information
 		//--------------------
 		$this->data['invoiceDate'] = date("Y-m-d");
 	
 	
 	
 		//Get sales information
 		//---------------------
 		$data['sale_info']=$sale_info = $this->Sale_quotations->get_info_for_guest($quote_url_key)->row_array();
 		
 		print_r($data['sale_info']);
 		exit;
 		//Getting the sale_id of the sale
 		//------------------------------
 		$sale_id=$sale_info['sale_id'];
 		
 	  
 		if (!$sale_id)
 		{
 			show_404();
 		}
 		$this->sale_lib->copy_entire_quotation($sale_id);
 		$data['cart']=$this->sale_lib->get_cart();
 	
 		//Payments
 		//--------------
 		$data['payments']=$this->sale_lib->get_payments();
 		$data['subtotal']=round($this->sale_lib->get_subtotal());
 		$data['taxes']=$this->sale_lib->get_taxes();
 		$data['total']=round($this->sale_lib->get_total());
 		$data['total_outstanding']=$data['total']-$data['total_paid'];
 		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
 	
 	
 		//Receipt information
 		//-------------------
 		$data['receipt_title']=$this->lang->line('sales_receipt');
 		$data['date_invoice_issued']= date('m/d/Y h:i:s a', strtotime($sale_info['sale_time']));
 		$customer_id=$this->sale_lib->get_customer();
 		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
 		$data['payment_type']=$sale_info['payment_type'];
 		
 		$date_invoiced_time=strtotime($data['date_invoice_issued']);
 	
 		$data['sale_number']=$sale_id;
 		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
 	
 	
 		
 	    $this->load->view("guest/quotes_view",$data);
 		 
 		$this->sale_lib->clear_all();
 	
    }

    public function generate_quote_pdf($quote_url_key, $stream = TRUE, $quote_template = NULL)
    {
        $this->load->model('quotes/mdl_quotes');

        $quote = $this->mdl_quotes->guest_visible()->where('quote_url_key', $quote_url_key)->get();
        
        if ($quote->num_rows() == 1)
        {
            $quote = $quote->row();

            if (!$quote_template)
            {
                $quote_template = $this->mdl_settings->setting('default_pdf_quote_template');
            }
            
            $this->load->helper('pdf');
            
            generate_quote_pdf($quote->quote_id, $stream, $quote_template);            
        }
    }
    
    public function approve_quote($quote_url_key)
    {
        $this->load->model('quotes/mdl_quotes');
        $this->mdl_quotes->approve_quote_by_key($quote_url_key);
        redirect('guest/view/quote/' . $quote_url_key);
    }
    
    public function reject_quote($quote_url_key)
    {
        $this->load->model('quotes/mdl_quotes');
        $this->mdl_quotes->reject_quote_by_key($quote_url_key);
        redirect('guest/view/quote/' . $quote_url_key);
    }

}

?>