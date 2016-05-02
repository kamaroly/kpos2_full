<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package    Kinvoice
 * @subpackage reports
 * @name       reports
 * @category   Controller
 * @author     Derrek 
 * @author     Kamaro Lambert
 * @copyright  Kamaro Lambert
 */

include(dirname(__FILE__) .'/Auth_public.php');
class Reports extends Auth_public {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('date', 'text'));
		$this->load->library('pagination');
		$this->load->library('table');
		$this->load->model('invoice/invoices_model');
		$this->load->model('invoice/reports_model');
		//if user didn't configure his company information then
		if($this->company_id<1 or $this->company_id==null)
		{
			redirect('settings');
		}
	}

	// --------------------------------------------------------------------

	function index()
	{
		$this->lang->load('calendar');

		
		 $this->data['current_year'] = $this->uri->segment(3, date('Y'));

		//earliest year
		$earliest_year = substr($this->db->select('MIN(`dateIssued`) AS dateIssued', FALSE)->get('invoices')->row()->dateIssued, 0, 4);

		 $this->data['years'] = array();

		while($earliest_year <= date('Y'))
		{
			 $this->data['years'][] = $earliest_year++;
		}

		for ($i=1; $i<13; $i++)
		{
			// invoices totals without taxes
			($i < 10) ? $monthnum = "0$i" : $monthnum=$i;
			 $this->data['month_invoices'][$i] = round($this->reports_model->getSummaryData( $this->data['current_year']. "-$monthnum-01",  $this->data['current_year']."-$monthnum-31")->amount);

			// tax1
			($i < 10) ? $monthnum = "0$i" : $monthnum=$i;
			 $this->data['month_tax1'][$i] = round($this->reports_model->getSummaryData( $this->data['current_year']. "-$monthnum-01",  $this->data['current_year']."-$monthnum-31")->tax1_collected);

			// tax2
			($i < 10) ? $monthnum = "0$i" : $monthnum=$i;
			 $this->data['month_tax2'][$i] = round($this->reports_model->getSummaryData( $this->data['current_year']. "-$monthnum-01",  $this->data['current_year']."-$monthnum-31")->tax2_collected);
		}

		$days_overdue = $this->settings_model->get_setting('days_payment_due');

		 $this->data['openInvoices'] = $this->invoices_model->getInvoices('open', $days_overdue, '', '');

		 $this->data['openInvoicesAmount'] = 0;
		$openInvoices = $this->invoices_model->getInvoices('open', $days_overdue, '', '');

		 $this->data['overdueInvoicesAmount'] = 0;
		$overdueInvoices = $this->invoices_model->getInvoices('overdue', $days_overdue, '', '');

		if ($openInvoices == NULL)
		{
			 $this->data['openInvoicesCount'] = 0;
		}
		else
		{
			 $this->data['openInvoicesCount'] = $overdueInvoices->num_rows();

			 $this->data['openInvoicesCount'] = $openInvoices->num_rows();
			if ( $this->data['openInvoicesCount'] != 0)
			{
				foreach ($openInvoices->result() as $invoice)
				{
					 $this->data['openInvoicesAmount'] += $invoice->subtotal;
				}
			}

			// account for non-period decimal
			 $this->data['openInvoicesAmount'] = str_replace('.', $this->config->item('currency_decimal'),  $this->data['openInvoicesAmount']);
		}

		if ($overdueInvoices == NULL)
		{
			 $this->data['overdueInvoicesCount'] = 0;
		}
		else
		{
			 $this->data['overdueInvoicesCount'] = $overdueInvoices->num_rows();

			if ( $this->data['overdueInvoicesCount'] != 0)
			{
				foreach ($overdueInvoices->result() as $invoice)
				{
					 $this->data['overdueInvoicesAmount'] += $invoice->subtotal;
				}
			}

			// account for non-period decimal
			 $this->data['overdueInvoicesAmount'] = str_replace('.', $this->config->item('currency_decimal'),  $this->data['overdueInvoicesAmount']);
		}

		 $this->data['yearToDateCount'] = $this->reports_model->getInvoiceDateRange( $this->data['current_year'].'-01-01',  $this->data['current_year'].'-12-31')->num_rows() . ' ' . $this->lang->line('reports_invoices_issued_year');

		$current_year_data = $this->reports_model->getSummaryData( $this->data['current_year'].'-01-01',  $this->data['current_year'].'-12-31');

		 $this->data['yearToDateAmount'] = $this->settings_model->get_setting('currency_symbol') . number_format($current_year_data->amount, 2, $this->config->item('currency_decimal'), '');
		if ($current_year_data->tax1_collected > 0)
		{
			 $this->data['yearToDateTax1'] = $this->settings_model->get_setting('currency_symbol') . number_format($current_year_data->tax1_collected, 2, $this->config->item('currency_decimal'), '');
		}
		else
		{
			 $this->data['yearToDateTax1'] = '';
		}

		if ($current_year_data->tax2_collected > 0)
		{
			 $this->data['yearToDateTax2'] = $this->settings_model->get_setting('currency_symbol') . number_format($current_year_data->tax2_collected, 2, $this->config->item('currency_decimal'), '');
		}
		else
		{
			 $this->data['yearToDateTax2'] = '';
		}

		 $this->data['page_title'] = $this->lang->line('menu_reports');
		
		// Get any status message that may have been set.
		$this->data['message'] = $this->session->flashdata('message');
		 $data = array(
				'kinvoice_title' => 'Kamaro Invoice', //I am setting the title of the page
				'kinvoice_heading' => 'Dashboard', //I am setting the head of the page
				'page_title'=>$this->data['page_title'],
				'base_url' => base_url(), //defining base url
				'navigation_top'=>$this->load->view( 'theme/partials/notification_area',$this->data,true), //Define the top navigation
				'main_content'=>$this->load->view('reports/index', $this->data,TRUE) //Define the main content
		);
		//Building our templates with the data
		$this->parser->parse('theme/layout/default', $data);
			
		
	}

	// --------------------------------------------------------------------

	function dates()
	{
		$tax1_desc = $this->settings_model->get_setting('tax1_desc');
		$tax2_desc = $this->settings_model->get_setting('tax2_desc');
		$tax1_rate = $this->settings_model->get_setting('tax1_rate');
		$tax2_rate = $this->settings_model->get_setting('tax2_rate');

		$start_date = $this->uri->segment(3, $this->input->post('startDate')); //ie: '2007-04-01';
		$end_date = $this->uri->segment(4, $this->input->post('endDate')); //ie: '2007-04-01';

		$start_date_timestamp = mysqldatetime_to_timestamp($start_date);
		$end_date_timestamp = mysqldatetime_to_timestamp($end_date);

		$date_error = (date("Y", $start_date_timestamp) == '1969' OR date("Y", $end_date_timestamp) == '1969') ? TRUE : FALSE;

		// sanity checks
		 $this->data['report_dates'] = 'Report for ' . date("Y-m-d", $start_date_timestamp) . ' to ' . date("Y-m-d", $end_date_timestamp);

		$detailed_data = $this->reports_model->getDetailedData($start_date, $end_date);
		$detailed_data_summary = $this->reports_model->getSummaryData($start_date, $end_date);

		if ($end_date_timestamp < $start_date_timestamp)
		{
			 $this->data['data_table'] = '<p class="error">You\'ll need to pick a start date before that end date.</p>';
		}
		elseif ($date_error)
		{
			 $this->data['data_table'] = '<p class="error">Looks like the dates somehow got messed up.  Probably easiest to go ' . anchor ('reports', 'back to reports') . ' and try again.</p>';
		}
		elseif ($detailed_data->num_rows() > 0)
		{

			$tmpl = array ( 'table_open' => '<table style="width: auto; margin: 0;" class="stripe  table table-bordered table-striped invoice_items stripe">' );
			$this->table->set_template($tmpl);

			$this->table->clear();
			if ($tax2_desc == '')
			{
				$this->table->set_heading($this->lang->line('invoice_client'), $this->lang->line('invoice_amount'), "$tax1_desc ($tax1_rate%)");

				foreach ($detailed_data->result() as $details)
				{
					$this->table->add_row($details->name, $this->settings_model->get_setting('currency_symbol') . number_format($details->amount, 2, $this->config->item('currency_decimal'), ''), $this->settings_model->get_setting('currency_symbol') . number_format($details->tax1_collected, 2, $this->config->item('currency_decimal'), ''));
				}

				$this->table->add_row('<strong>Total</strong>', '<strong>' . $this->settings_model->get_setting('currency_symbol') . number_format($detailed_data_summary->amount, 2, $this->config->item('currency_decimal'), ''), '<strong>' . $this->settings_model->get_setting('currency_symbol') . number_format($detailed_data_summary->tax1_collected, 2, $this->config->item('currency_decimal'), '') . '</strong>');
			}
			else
			{
				$this->table->set_heading('Client', 'Total Billed', "$tax1_desc ($tax1_rate%)", "$tax2_desc ($tax2_rate%)");

				foreach ($detailed_data->result() as $details)
				{
					$this->table->add_row($details->name, $this->settings_model->get_setting('currency_symbol') . number_format($details->amount, 2, $this->config->item('currency_decimal'), ''), $this->settings_model->get_setting('currency_symbol') . number_format($details->tax1_collected, 2, $this->config->item('currency_decimal'), ''), $this->settings_model->get_setting('currency_symbol') . number_format($details->tax2_collected, 2, $this->config->item('currency_decimal'), ''));
				}

				$this->table->add_row('<strong>'.$this->lang->line('invoice_total').'</strong>', '<strong>' . $this->settings_model->get_setting('currency_symbol') . number_format($detailed_data_summary->amount, 2, $this->config->item('currency_decimal'), ''), '<strong>' . $this->settings_model->get_setting('currency_symbol') . number_format($detailed_data_summary->tax1_collected, 2, $this->config->item('currency_decimal'), '') . '</strong>', '<strong>' . $this->settings_model->get_setting('currency_symbol') . number_format($detailed_data_summary->tax2_collected, 2, $this->config->item('currency_decimal'), '') . '</strong>');
			}

			 $this->data['data_table'] = $this->table->generate();
		}
		else
		{
			 $this->data['data_table'] = '<p class="error">'.$this->lang->line('reports_no_data').'</p>';
		}

		 $this->data['page_title'] = $this->lang->line('menu_reports');

		 // Get any status message that may have been set.
		 $this->data['message'] = $this->session->flashdata('message');
		 $data = array(
		 		'kinvoice_title' => 'Kamaro Invoice', //I am setting the title of the page
		 		'kinvoice_heading' => 'Dashboard', //I am setting the head of the page
		 		'page_title'=>$this->data['page_title'],
		 		'base_url' => base_url(), //defining base url
		 		'navigation_top'=>$this->load->view( 'theme/partials/notification_area',$this->data,true), //Define the top navigation
		 		'main_content'=>$this->load->view('reports/dates', $this->data,TRUE) //Define the main content
		 );
		 //Building our templates with the data
		 $this->parser->parse('theme/layout/default', $data);
		
	}

}
?>