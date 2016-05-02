<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package    Kinvoice
 * @subpackage Invoices
 * @name       Invoices
 * @category   Controller
 * @author     Derrek 
 * @author     Kamaro Lambert
 * @copyright  Kamaro Lambert
 */

include(APPPATH.'controllers/secure_area.php');
class Invoices extends Secure_area {
	
	//defining the global data to be used
	public $recur_frequencies = array(
			'7D' => 'calendar_week',
			'1M' => 'calendar_month',
			'1Y' => 'year',
			'3M' => 'quarter',
			'6M' => 'six_months'
	);
	public $data=array();
	
	function __construct()
	{
		parent::__construct();
		
		/**Loads of the languages we will need**/
		$this->lang->load('calendar');
		/**Loading all helper  we will need**/
		$this->load->helper(array('date', 'text', 'typography'));
		/**Loading all library   we will need**/
		$this->load->library('pagination');
		/**Loading our models   we will need**/
		$this->load->model('invoice/invoices_model');
		
		/**Load languages**/
		$this->load->language('invoices');
		
		
	
		
	}
	
	// --------------------------------------------------------------------

	function index($recurring=false)
	{
		
		//Let's check if we need recurring invoices 
		//-----------------------------------------
		if($recurring=='recurring')
		{   
			//we need recurring invoices to display them
			//-----------------------------------------
			$recurring=true;
			$this->data['recurring']=true;
		}
		else
		{
			$this->data['recurring']=false;
		}
		
		
		$config['base_url'] = site_url('/customers/index');
		$config['total_rows'] = $this->Customer->count_all();
		$config['per_page'] = '20';
		$config['uri_segment'] = 3;
		
		$this->pagination->initialize($config);
		
		$offset = (int) $this->uri->segment(3, 0);

		$this->data['clientList'] = $this->Customer->get_all( $config['per_page'], $this->uri->segment( $config['uri_segment'] )) ; // activate the option
		
		
		$this->data['query'] = $this->invoices_model->getInvoices(false,false,'open', 30, $offset, 5000);
		
	    $this->data['short_description'] = $this->invoices_model->build_short_descriptions();
	  
		$this->data['total_rows'] = ($this->data['query']) ? $this->data['query']->num_rows() : 0;

		$overdue_count = $this->invoices_model->getInvoices('overdue',$days_overdue=30, 0, 5000);

		$this->data['overdue_count'] = ($overdue_count) ? $overdue_count->num_rows() : 0;

		$this->data['message'] = ($this->session->flashdata('message') != '') ? $this->session->flashdata('message') : '';
        
		$this->data['status_menu'] = TRUE; // pass status_menu
		$this->data['page_title'] = $this->lang->line('menu_invoices');
		// Get any status message that may have been set.
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['kinvoice_title'] ='Kamaro Invoice'; //I am setting the title of the page
		$this->data['kinvoice_heading'] = 'Dashboard'; //I am setting the head of the page
		$this->data['page_title'] =$this->data['page_title'];
		$this->data['base_url'] = base_url(); //defining base url
		
		$this->data['body']=$this->load->view('invoices/invoices/index', $this->data,true); //Define the main content
		
		$this->load->view('invoices/page', $this->data);
		//Building our templates with the data
		
		

	}

	// --------------------------------------------------------------------

	function recalculate_items()
	{
		$amount = 0;
		$tax1_amount = 0;
		$tax2_amount = 0;

		$items = $this->input->post('items');
		$tax1_rate = $this->input->post('tax1_rate');
		$tax2_rate = $this->input->post('tax2_rate');

		foreach ($items as $item)
		{
			$taxable = (isset($item['taxable']) && $item['taxable'] == 1) ? 1 : 0;
			$sub_amount = $item['quantity'] * $item['amount'];
			$amount += $sub_amount;
			$tax1_amount += $sub_amount * (($tax1_rate)/100) * $taxable;
			$tax2_amount += $sub_amount * (($tax2_rate)/100) * $taxable;
		}

		echo '{"amount" : "'.number_format($amount, 2, $this->config->item('currency_decimal'), '').'", "tax1_amount" : "'.number_format($tax1_amount, 2, $this->config->item('currency_decimal'), '').'", "tax2_amount" : "'.number_format($tax2_amount, 2, $this->config->item('currency_decimal'), '').'", "total_amount" : "'.number_format($amount + $tax1_amount+$tax2_amount, 2, $this->config->item('currency_decimal'), '').'"}';
	}

	// --------------------------------------------------------------------

	function newinvoice($id=null)
	{
		$this->load->library('validation');
		
		$this->data['clientList'] = $this->Customer->get_all( $config['per_page'], $this->uri->segment( $config['uri_segment'] )) ; // activate the option // used to extract name, id and tax info
		
		$this->data['tax1_desc'] = $this->settings_model->get_setting('tax1_desc',$this->user_id);
		$this->data['tax1_rate'] = $this->settings_model->get_setting('tax1_rate',$this->user_id);
		$this->data['tax2_desc'] = $this->settings_model->get_setting('tax2_desc',$this->user_id);
		$this->data['tax2_rate'] = $this->settings_model->get_setting('tax2_rate',$this->user_id);
		$this->data['invoice_note_default'] = $this->settings_model->get_setting('invoice_note_default',$this->user_id);

		$last_invoice_number = $this->invoices_model->lastInvoiceNumber($id);
		($last_invoice_number != '') ? $this->data['lastInvoiceNumber'] = $last_invoice_number : $this->data['lastInvoiceNumber'] = '';
		$this->data['suggested_invoice_number'] = (is_numeric($last_invoice_number)) ? $last_invoice_number+1 : '';

		
		$this->_validation(); // Load the validation rules and fields

		$this->data['invoiceDate'] = date("Y-m-d");

		if ($this->validation->run() == FALSE)
		{
			$this->session->keep_flashdata('clientId');
			$this->data['invoiceDate'] = $this->validation->dateIssued;
			$this->data['page_title'] = $this->lang->line('invoice_new_invoice');
			// Get any status message that may have been set.
			$this->data['message'] = $this->session->flashdata('message');
			$data = array(
					'kinvoice_title' => 'Kamaro Invoice', //I am setting the title of the page
					'kinvoice_heading' => 'Dashboard', //I am setting the head of the page
					'page_title'=>$this->data['page_title'],
					'base_url' => base_url(), //defining base url
					'navigation_top'=>$this->load->view( 'theme/partials/notification_area',$this->data,true), //Define the top navigation
					'main_content'=>$this->load->view('invoices/newinvoice', $this->data,TRUE) //Define the main content
			);
			//Building our templates with the data
			$this->parser->parse('theme/layout/default', $data);
			
		}
		else
		{
			
			
			$this->data['clientList'] =$this->input->post('client_id')?$this->input->post('client_id'):$id;
			
			$invoice_data = array(
									'client_id' => $this->input->post('client_id'),
									'invoice_number' => $this->input->post('invoice_number'),
									'dateIssued' => $this->input->post('dateIssued'),
									'tax1_desc' => $this->input->post('tax1_description'),
									'tax1_rate' => $this->input->post('tax1_rate'),
									'tax2_desc' => $this->input->post('tax2_description'),
									'tax2_rate' => $this->input->post('tax2_rate'),
									'invoice_note' => $this->input->post('invoice_note'),
					                'user_id'      =>$this->user_id,
					                'company_id'   =>$this->company_id
								);

			$invoice_id = $this->invoices_model->addInvoice($invoice_data);

			if ($invoice_id > 0)
			{
				$items = $this->input->post('items');

				$amount = 0;
				foreach ($items as $item)
				{
					$taxable = (isset($item['taxable']) && $item['taxable'] == 1) ? 1 : 0;

					$invoice_items = array(
											'invoice_id' 		=> $invoice_id,
											'quantity' 			=> $item['quantity'],
											'amount' 			=> $item['amount'],
											'work_description' 	=> $item['work_description'],
											'taxable' 			=> $taxable
										);

					$this->invoices_model->addInvoiceItem($invoice_items);
				}

				redirect('invoices/view/'.$invoice_id);
			}
			else
			{
				// clear clientId session
				$this->data['page_title'] = $this->lang->line('invoice_new_error');
				$this->load->view('invoices/create_fail', $this->data);
			}
		}
	}

	// --------------------------------------------------------------------

	function newinvoice_first()
	{
		// page for users without javascript enabled
		$this->data['page_title'] = $this->lang->line('menu_new_invoice');
		$this->data['clientList'] = $this->clients_model->getAllClients(); // activate the option
		$this->load->view('invoices/newinvoice_first', $this->data);
	}

	/**
	 * @name view
	 * @author Derrek , Stongly modified by Kamaro Lambert
	 * @param integer $id
	 * @method to view one selected invoice
	 */

	function view($sale_id)
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
		$data['sale_info']=$sale_info = $this->Sale->get_info($sale_id)->row_array();
		
		$this->sale_lib->copy_entire_sale($sale_id);
		$data['cart']=$this->sale_lib->get_cart();
		
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

		if($customer_id!=-1)
		{
			$data['client']=$this->Customer->get_info($customer_id);
			
		}
		
		 $data['sale_id']='POS '.$sale_id;
		 //Get Status information
		 //-------------------------
		 if ($data['total_paid'] >= $data['total'])
		 {
		 	// paid invoices
		 	$data['status'] = '<span>'.$this->lang->line('invoice_closed').'</span>';
		 	$data['class']='success';
		 }
		 elseif (mysql_to_unix($sale_info['sale_time'] ) >= time()-(30 * 60*60*24))
		 {
		 	// owing less then 30 days
		 	$data['status'] = '<span>'.$this->lang->line('invoice_open').'</span>';
		 	$data['class']='warning';
		 }
		 else
		 {
		 	// owing more then 30 days
		 	$due_date = $sale_info['invoice_date_due'] ;
		 	$data['class']='danger';
		 	$data['status'] = '<span class="error">'.timespan(mysql_to_unix($sale_info['invoice_date_due'] ), now()). ' '.$this->lang->line('invoice_overdue').'</span>';
		 }
		 $this->data['right_header']=$this->load->view('invoices/invoices/invoice_header_menu',$data,true);
		 
		 $this->data['page_title']=$data['status'] ;
	     $this->data['body']=$this->load->view("invoices/invoices/invoice",$data,true);
	     $this->load->view('invoices/page', $this->data);
		$this->sale_lib->clear_all();
	
	
	}


	// --------------------------------------------------------------------

	function notes($id)
	{
		$this->load->model('invoice/invoice_histories_model');

		$this->invoice_histories_model->insert_note($id, $this->input->post('private_note'));
		$this->flexi_auth_model->set_status_message(FALSE,$this->lang->line('invoice_comment_success'), 'config');
		$this->lang->line('invoice_comment_success');
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
		
		redirect('invoices/view/'.$id);
	}

	function choose_clientcontacts($customer_id,$sale_number)
	{
        //Load models..
        //------------
        $this->load->model('invoices/clients/clients_model');
        
		$data['clientContacts'] = $this->clients_model->getClientContacts($customer_id);
		
		$data['sale_number']=(int) $sale_number;
		$data['customer_id']=(int) $customer_id;
		$this->load->view('invoices/invoices/email',$data);
		
	}
	// --------------------------------------------------------------------

	public function mail($sale_id)
	{
				
		ini_set('set_memory_limit',-1);
		//Loading library
		//-----------------
		$config = Array(
		
				'protocol' => 'smtp',
		
				'smtp_host' => 'ssl://smtp.googlemail.com',
		
				'smtp_port' => 465,
		
				'smtp_user' => 'lambert@huguka.com',
		
				'smtp_pass' => 'kamalo2013',
		
				'mailtype' => 'html',
		
				'charset' => 'iso-8859-1',
		
				'wordwrap' => TRUE
		
		);
		$this->load->library('email',$config);
		
		//Loading models..
		//---------------
		$this->load->model('invoices/clients/clientcontacts_model');
		$this->load->model('invoices/invoice/invoice_histories_model');
		
	
		$recipients = $this->input->post('recipients');

		if ($recipients == '') {show_error($this->lang->line('invoice_email_no_recipient'));} // a rather rude reminder to include a recipient in case js is disabled

		$recipient_emails = '';

		foreach($recipients as $recipient)
		{
			($recipient == 1) ? $recipient_emails[] .= $from_email : $recipient_emails[] .= $this->clientcontacts_model->getContactInfo($recipient)->email;
		}

		$recipient_names = '';

		foreach($recipients as $recipient)
		{
			if ($recipient == 1)
			{
				$recipient_names[] .= $this->lang->line('invoice_you');
			}
			else
			{
				$recipient_names[] .= $this->clientcontacts_model->getContactInfo($recipient)->first_name.' '.$this->clientcontacts_model->getContactInfo($recipient)->last_name;
			}
		}

		// send emails

		if (count($recipient_emails) === 1)
		{
			$this->email->to($recipient_emails[0]);
		}
		else
		{
			
			$this->email->to($recipient_emails[0]);
			$this->email->cc(array_slice($recipient_emails, 1));
		}

		// should we blind copy the primary contact? 
		if ($this->input->post('primary_contact') == 'y')
		{
			$this->email->bcc($this->config->item('email'));
		}

		$email_body = $this->input->post('email_body');

		$this->email->from($this->config->item('email'), $this->data['user_info']->email);
		$this->email->subject($this->lang->line('invoice_invoice')." $invoice_number : ".$this->data['companyInfo']->company_name);
		$this->email->message(stripslashes($email_body));
		$this->email->attach($this->pdf($sale_id, FALSE));
		
		// for the demo, I don't want actual emails sent out, so this provides an easy
		// override. 
		
		$this->email->send();
		

		$this->_delete_stored_files(); // remove saved invoice(s)

		// save this in the invoice_history
		$this->invoice_histories_model->insert_history_note($sale_id, $email_body, $recipient_names);

		// next line for debugging
		show_error($this->email->print_debugger());

		if ($this->input->post('isAjax') == 'true')
		{
			// for future ajax functionality, right now this is permanently set to false
		}
		else
		{
			if ($this->settings_model->get_setting('demo_flag') == 'y')
			{
				$this->session->set_flashdata('message', $this->lang->line('invoice_email_demo'));
			}
			else
			{
				$this->session->set_flashdata('message', $this->lang->line('invoice_email_success'));
			}

			redirect('invoices/view/'.$id); // send them back to the invoice view
		}
	}

	
	// --------------------------------------------------------------------

	/**
	  * Batch PDF
	  *
	  * This function is in here for the convenience of people who need it, but is not accessible currently
	  * via the "front end".  It is very memory intensive, and unlikely that most servers could handle it
	  * even with resetting memory and timeout options... thus, its in here for people who need it, and for
	  * me, but not currently publicly accessible.
	  */
	function batch_pdf()
	{
		$start_id = $this->uri->segment(3);
		$end_id = $this->uri->segment(4);

		if ($start_id == '' || $end_id == '')
		{
			show_error('Start and end id\'s must be passed');
		}

		$this->db->select('id');
		$this->db->where('id >= '.$start_id);
		$this->db->where('id <= '.$end_id);
		$invoice_set = $this->db->get('invoices');

		foreach($invoice_set->result() as $invoice)
		{
			echo "$invoice->id<br />";
			$this->pdf($invoice->id, FALSE);
		}
	}

	// --------------------------------------------------------------------

	function payment($sale_id=null)
	{
		if(!$this->input->post())
		{
			//prepare the variables
			//---------------------
			$data['payment_options']=array(
					$this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
					$this->lang->line('sales_insurance') => $this->lang->line('sales_insurance'),
					$this->lang->line('sales_check') => $this->lang->line('sales_check'),
					$this->lang->line('sales_giftcard') => $this->lang->line('sales_giftcard'),
					$this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
					);
			$data['sale_id']=$sale_id;
			$this->load->view('invoices/invoices/payment_form',$data);
			return false;
		}
		//prepare inputs
		
		$id = (int) $this->input->post('id');
		$date_paid = date('Y-m-d',strtotime($this->input->post('date_paid')));
		$amount = $this->input->post('amount');
		$payment_type=$this->input->post('payment_type');
		$payment_note = substr($this->input->post('payment_note'), 0, 255);

		if (!preg_match("/(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])/", $date_paid) || !is_numeric($amount))
		{
			$this->session->set_flashdata('error', $this->lang->line('error_date_fill'));
			redirect('invoices/view/'.$id);
		}
		else
		{
			$this->data = array(
							'sale_id' => $id,
							'payment_amount' => $amount,
							'date_paid' => $date_paid,
					        'payment_type'=>$payment_type,
							'payment_note' => $payment_note
						);

			if($this->invoices_model->payment($this->data))
			{//all goes well...
					
				//Let's insert in history
				$this->load->model('invoices/invoice/invoice_histories_model');
				//Preparing data
				$Invoice_note='Paid '.$this->data['payment_amount'].' using '.$this->data['payment_type'];

				//let's insert
				$this->invoice_histories_model->insert_note($this->data['sale_id'],$Invoice_note,$this->data['date_paid']);
			}

			$this->session->set_flashdata('success', $this->lang->line('invoice_payment_success'));
            redirect('invoices/view/'.$id);
		}
	}

	// --------------------------------------------------------------------

	function delete($id)
	{
		$this->session->set_flashdata('deleteInvoice', $id);
		$this->data['deleteInvoice'] = $this->invoices_model->getSingleInvoice($id,$this->user_id,$this->company_id)->row()->invoice_number;
		$this->data['page_title'] = $this->lang->line('menu_delete_invoice');
		$this->load->view('invoices/delete', $this->data);
	}

	// --------------------------------------------------------------------

	function delete_confirmed()
	{
		$invoice_id = $this->session->flashdata('deleteInvoice');
		$this->invoices_model->delete_invoice($invoice_id);
		$this->session->set_flashdata('message', $this->lang->line('invoice_invoice_delete_success'));
		redirect('invoices/');
	}
/**
 * @name REtrieveInvoice
 * @author Kamaro Lambert
 * @Method to retried invoices based on their categories
 * -----------------------------------------------------
 */
	function retrieveInvoices($recurring=false)
	{
		//Let's check if we need recurring invoices
		//-----------------------------------------
		if($recurring='recurring')
		{
			//we need recurring invoices to display them
			//-----------------------------------------
		   $recurring=true;
		   $this->data['recurring']=true;
		}
		else 
		{  //We need all invoices
		   $this->data['recurring']=false;
		}
		
		$this->data['query'] = $this->invoices_model->getInvoices (null, $this->input->post('client_id'),$this->input->post('status'),$days_payment_due=30,0,20,$recurring);
		
		
		$this->data['clientList'] = $this->Customer->get_all() ; // activate the option // activate the option
		$this->data['short_description'] = $this->invoices_model->build_short_descriptions();
		
		// Get any status message that may have been set.
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['page_title']=$this->input->post('status');
		
		//Building our templates with the data
		$this->data['body']=$this->load->view('invoices/invoices/index', $this->data,true);
		 //Define the main content
		$this->load->view('invoices/page', $this->data);
	}

	function quotations()
	{
		
		$this->data['query'] = $this->invoices_model->get_quotations ();
		
		$this->data['clientList'] = $this->Customer->get_all() ; // activate the option // activate the option
		$this->data['short_description'] = $this->invoices_model->build_short_descriptions();
		
		// Get any status message that may have been set.
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['page_title']=$this->input->post('status');
		
		//Building our templates with the data
		$this->data['body']=$this->load->view('invoices/invoices/index', $this->data,true);
		 //Define the main content
		$this->load->view('invoices/page', $this->data);
	}
	/**
	 * @author Kamaro Lambert
	 * @name suspended()
	 * @method to display all suspended sales
	 *
	 */
	function drafts()
	{
		$data = array();
		$data['suspended_sales'] = $this->Sale_suspended->get_all()->result_array();
		$data['page_title']='';
		//Building our templates with the data
		$this->data['body']=$this->load->view('invoices/invoices/invoices_drafts', $data,true);
		//Define the main content
		$this->load->view('invoices/page', $this->data);
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name   unsuspend()
	 * @method to unsuspend a given suspended sale
	 */
	function opendrafts($sale_id=null)
	{
		//Loading library
		//--------------
        $this->load->library('Sale_lib');
        
        //Load Controller
        //---------------
       
		if($sale_id==null)
		{
			show_404();
			exit;
		}
		 
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_suspended_sale($sale_id);
		$this->Sale_suspended->delete($sale_id);
		redirect('invoices/sales');
	}
	// --------------------------------------------------------------------

	function dateIssued($str)
	{
		if (preg_match("/(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])/", $str))
		{
			return TRUE;
		}
		else
		{
			$this->validation->set_message('dateIssued', $this->lang->line('error_date_format'));
			return FALSE;
		}
	}

	// --------------------------------------------------------------------

	function _delete_stored_files($file=null)
	{
		if ($file!=null)
		{
			
			delete_files($file);
			
		}
		else
		{
			delete_files('./uploads/temp/');
		}
	}

	// --------------------------------------------------------------------
 /**
  * @name logo
  * @param unknown_type $target
  * @param unknown_type $context
  * @return string
  */
	function _get_logo($target='', $context='web')
	{
		$this->load->helper('logo');
		$this->load->helper('path');
        //Get the logo of the company for the current logged on user
		return get_logo($this->settings_model->get_setting('logo'.$target,$this->user_id), $context);
	}

	// --------------------------------------------------------------------

	function _tax_info($data)
	{
		$tax_info = '';

	if ($data->total_tax1 != 0)
		{
			$tax_info .= $data->tax1_desc." (".$data->tax1_rate."%): ".$this->settings_model->get_setting('currency_symbol',$this->user_id).number_format($data->total_tax1, 2, $this->config->item('currency_decimal'), '')."<br />\n";
		}

		if ($data->total_tax2 != 0)
		{
			$tax_info .= $data->tax2_desc." (".$data->tax2_rate."%): ".$this->settings_model->get_setting('currency_symbol',$this->user_id).number_format($data->total_tax2, 2, $this->config->item('currency_decimal'), '')."<br />\n";
		}

		return $tax_info;
	}

	// --------------------------------------------------------------------

	function _validation()
	{
		$rules['client_id'] 		= 'required|numeric';
		$rules['invoice_number'] 	= 'trim|required|htmlspecialchars|max_length[12]|alpha_dash|callback_uniqueInvoice';
		$rules['dateIssued'] 		= 'trim|htmlspecialchars|callback_dateIssued';
		$rules['invoice_note'] 		= 'trim|htmlspecialchars|max_length[2000]';
		$rules['tax1_description'] 	= 'trim|htmlspecialchars|max_length[50]';
		$rules['tax1_rate'] 		= 'trim|htmlspecialchars';
		$rules['tax2_description'] 	= 'trim|htmlspecialchars|max_length[50]';
		$rules['tax2_rate'] 		= 'trim|htmlspecialchars';
		$this->validation->set_rules($rules);

		$fields['client_id'] 		= $this->lang->line('invoice_client_id');
		$fields['invoice_number'] 	= $this->lang->line('invoice_number');
		$fields['dateIssued'] 		= $this->lang->line('invoice_date_issued');
		$fields['invoice_note'] 	= $this->lang->line('invoice_note');
		$fields['tax1_description']	= $this->settings_model->get_setting('tax1_desc');
		$fields['tax1_rate'] 		= $this->settings_model->get_setting('tax1_rate');
		$fields['tax2_description']	= $this->settings_model->get_setting('tax1_desc');
		$fields['tax2_rate'] 		= $this->settings_model->get_setting('tax2_rate');
		$this->validation->set_fields($fields);

		$this->validation->set_error_delimiters('<span class="error">', '</span>');
	}

	// --------------------------------------------------------------------

	function _validation_edit()
	{
		$rules['client_id'] 		= 'required|numeric';
		$rules['invoice_number'] 	= 'trim|required|htmlspecialchars|max_length[50]|alpha_dash';
		$rules['dateIssued'] 		= 'trim|htmlspecialchars|callback_dateIssued';
		$rules['invoice_note'] 		= 'trim|htmlspecialchars|max_length[2000]';
		$rules['tax1_description'] 	= 'trim|htmlspecialchars|max_length[50]';
		$rules['tax1_rate'] 		= 'trim|htmlspecialchars';
		$rules['tax2_description'] 	= 'trim|htmlspecialchars|max_length[50]';
		$rules['tax2_rate'] 		= 'trim|htmlspecialchars';
		$this->validation->set_rules($rules);

		$fields['client_id'] 		= $this->lang->line('invoice_client_id');
		$fields['invoice_number'] 	= $this->lang->line('invoice_number');
		$fields['dateIssued'] 		= $this->lang->line('invoice_date_issued');
		$fields['invoice_note'] 	= $this->lang->line('invoice_note');
		$fields['tax1_description']	= $this->settings_model->get_setting('tax1_desc');
		$fields['tax1_rate'] 		= $this->settings_model->get_setting('tax1_rate');
		$fields['tax2_description']	= $this->settings_model->get_setting('tax1_desc');
		$fields['tax2_rate'] 		= $this->settings_model->get_setting('tax2_rate');
		$this->validation->set_fields($fields);

		$this->validation->set_error_delimiters('<span class="error">', '</span>');
	}

	function uniqueInvoice()
	{
		$this->validation->set_message('uniqueInvoice', $this->lang->line('invoice_not_unique'));

		return $this->invoices_model->uniqueInvoiceNumber($this->input->post('invoice_number'));
	}
	/**
	 * @author Kamaro Lambert
	 * @param  numeric sale_id
	 * @param  $stream
	 * @param  $invoice_template
	 */
	public function pdf($sale_id)
	{
		
       	if (!$sale_id)
    	{
    		show_404();
    	}

    	$this->load->helper('pdf');
    	
    	generate_invoice_pdf($sale_id);
	}
	
	function create_recurring($sale_id=false)
	{
		//Load models..
		//------------
		$this->load->model('invoices/invoice/mdl_invoices_recurring');
		$data['sale_id']=$sale_id;
		$data['recur_frequencies']= $this->mdl_invoices_recurring->recur_frequencies;
		$this->load->view('invoices/invoices/modal_create_recurring',$data);
	}
	
} 
?>
