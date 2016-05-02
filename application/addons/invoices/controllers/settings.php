<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package    Kinvoice
 * @subpackage settings
 * @name       settings
 * @category   Controller
 * @author     Derrek
 * @author     Kamaro Lambert
 * @copyright  Kamaro Lambert
*/

include(dirname(__FILE__) .'/Auth_public.php');
class Settings extends Auth_public {

	
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('validation');
		$this->load->helper(array('logo', 'file', 'form', 'path'));
			
	}

	// --------------------------------------------------------------------

	function index()
	{
		
		$this->_validation(); // Load the validation rules and fields

		$this->data['company_logo'] = get_logo($this->settings_model->get_setting('logo',$this->user_id));
 
		if ( ! $this->validation->run())
		{
			// if company_name was submitted, but we're here in the failed validation statement, then it means there were errors
			if ($this->input->post('company_name'))
			{
				$this->data['message'] = $this->lang->line('settings_modify_fail');
			}
			else
			{
				$this->data['message'] = $this->session->flashdata('status');
			}
			
          
			// grab existing prefs
			                     $this->db->where('user_id',$this->user_id);
			$this->data['row'] = $this->db->get('settings')->row();
			$this->data['page_title'] = $this->lang->line('menu_settings');
			//prepare the view
			$data = array(
					'kinvoice_title' => 'Kamaro Invoice', //I am setting the title of the page
					'kinvoice_heading' => $this->data['page_title'], //I am setting the head of the page
					'page_title'=>$this->data['page_title'],
					'base_url' => base_url(), //defining base url
					'navigation_top'=>$this->load->view( 'theme/partials/notification_area',$this->data,true), //Define the top navigation
					'main_content'=>$this->load->view('settings/index', $this->data,TRUE) //Define the main content
			);
			//Building our templates with the data
			$this->parser->parse('theme/layout/default', $data);
		}
		else
		{
			
			$save_invoices = ($this->input->post('save_invoices') == 'y') ? 'y' : 'n';
          
			
			$data = array(
							'company_name' => $this->input->post('company_name'),
							'address1' => $this->input->post('address1'),
							'address2' => $this->input->post('address2'),
							'city' => $this->input->post('city'),
							'province' => $this->input->post('province'),
							'country' => $this->input->post('country'),
							'postal_code' => $this->input->post('postal_code'),
							'website' => $this->input->post('website'),
							'primary_contact' => $this->input->post('primary_contact'),
							'primary_contact_email' => $this->input->post('primary_contact_email'),
							'invoice_note_default' => $this->input->post('invoice_note_default'),
							'currency_type' => $this->input->post('currency_type'),
							'currency_symbol' => $this->input->post('currency_symbol'),
							'days_payment_due' => (int) $this->input->post('days_payment_due'),
							'tax_code' => $this->input->post('tax_code'),
							'tax1_desc' => $this->input->post('tax1_desc'),
							'tax1_rate' => $this->input->post('tax1_rate'),
							'tax2_desc' => $this->input->post('tax2_desc'),
							'tax2_rate' => $this->input->post('tax2_rate'),
							'save_invoices' => $save_invoices,
					        'user_id' => $this->user_id,
							'display_branding' => $this->input->post('display_branding'),
							'new_version_autocheck' => $this->input->post('new_version_autocheck')
						);

			// Euro has conversion issues in DOMPDF, this is a fix for it
			$data['currency_symbol'] = ($data['currency_symbol'] == '€') ? '&#0128;' : $data['currency_symbol'];
			
			// Logo uploading
			$config['upload_path'] 		= './img/logo/';
			$config['allowed_types'] 	= 'gif|jpg|jpeg|png|PNG|JPEG|JPG|GIF';
			$config['max_size'] 		= '5000'; 
			$config['max_width'] 		= '1024';
			$config['max_height'] 		= '1024'; // these are WAY more then someone should need for a logo
			$config['file_name']        = 'company'.$this->company_id;
			$config['overwrite']        = TRUE;
			
			$this->load->library('upload', $config);

			
			if ( ! $this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors());
			
				print_r($error);
			}
			else
			{
				$logo_data = $this->upload->data();
				// add the logo into the settings update
				$data['logo'] = $logo_data['file_name'];
				$data['logo_pdf'] = $logo_data['file_name'];
			}
			

			$extra_message .= ($this->input->post('userfile') != '') ? $this->upload->display_errors('<br />') : '';

			// run the update
			if($this->input->post('id')==0)
		    {
		    	
		    	$update_settings=$this->settings_model->add_settings($data);

		    }
		    
		    else 
		    
		    {
		  	    $update_settings=$this->settings_model->update_settings($data,$this->input->post('id'));
		  	    
		    }
            
			if ($update_settings === TRUE)
			{
				$this->load->model('clients/clientcontacts_model');

				// was the email address changed, and if so, be sure this isn't the demo, and also
				// update the login email

				if ($this->input->post('primary_contact_email') != '' && $this->settings_model->get_setting('demo_flag',$this->user_id) != 'y')
				{
					$this->clientcontacts_model->email_change(1, $this->input->post('primary_contact_email'));
				}

				// was the password getting changed, and if so, be sure this isn't the demo
				if ($this->input->post('password') != '' && $this->input->post('password') == $this->input->post('password_confirm') && $this->settings_model->get_setting('demo_flag',$this->user_id) != 'y')
				{
					$this->clientcontacts_model->password_change(1, $this->input->post('password'));
				}

				$this->session->set_flashdata('status', $this->lang->line('settings_modify_success') . ' ' . $extra_message);
			}
			else
			{
				$this->session->set_flashdata('status', $this->lang->line('settings_modify_fail') . ' ' . $extra_message);
			}

			// running a redirect here instead of loading a view because glider.js seems to freeze without the reload
			redirect('settings');
		}
	}

	// --------------------------------------------------------------------

	function _validation()
	{
		$rules['company_name'] 		= "trim|max_length[75]";
		$rules['address1'] 			= "trim|max_length[100]";
		$rules['address2'] 			= "trim|max_length[100]";
		$rules['city'] 				= "trim|max_length[50]";
		$rules['province'] 			= "trim|max_length[25]";
		$rules['country'] 			= "trim|prep_for_form|max_length[25]";
		$rules['postal_code'] 		= "trim|prep_for_form|max_length[10]";
		$rules['website'] 			= "trim|prep_for_form|max_length[150]";
		$rules['primary_contact'] 	= "trim|prep_for_form|required|max_length[75]";
		$rules['primary_contact_email'] = "trim|prep_for_form|required|max_length[50]|valid_email";
		$rules['password'] 			= "min_length[4]|max_length[50]|alpha_dash";
		$rules['password_confirm']	= "matches[password]";
		$rules['logo'] 				= "trim|prep_for_form|max_length[50]";
		$rules['invoice_note_default'] = "trim|prep_for_form|max_length[2000]";
		$rules['currency_type'] 	= "trim|prep_for_form|max_length[20]";
		$rules['currency_symbol'] 	= "ltrim|max_length[9]";
		$rules['days_payment_due'] 	= "trim|prep_for_form|numeric|max_length[3]";
		$rules['tax_code'] 			= "trim|prep_for_form|max_length[50]";
		$rules['tax1_desc'] 		= "trim|prep_for_form|max_length[50]";
		$rules['tax1_rate'] 		= "trim|prep_for_form|max_length[6]";
		$rules['tax2_desc'] 		= "trim|prep_for_form|max_length[50]";
		$rules['tax2_rate'] 		= "trim|prep_for_form|max_length[6]";
		$rules['save_invoices'] 	= "trim|alpha|max_length[1]";
		$rules['display_branding'] 	= "trim|alpha|max_length[1]";
		$rules['new_version_autocheck'] = "trim|alpha|max_length[1]";

		$this->validation->set_rules($rules);

		$fields['company_name'] 	= $this->lang->line('settings_company_name');
		$fields['address1'] 		= $this->lang->line('clients_address1');
		$fields['address2'] 		= $this->lang->line('clients_address2');
		$fields['city'] 			= $this->lang->line('clients_city');
		$fields['province'] 		= $this->lang->line('clients_province');
		$fields['country'] 			= $this->lang->line('clients_country');
		$fields['postal_code'] 		= $this->lang->line('clients_postal');
		$fields['website'] 			= $this->lang->line('clients_website');
		$fields['primary_contact'] 	= $this->lang->line('settings_primary_contact');
		$fields['primary_contact_email'] = $this->lang->line('settings_primary_email');
		$fields['password'] 		= $this->lang->line('login_password');
		$fields['password_confirm']	= $this->lang->line('login_password_confirm');
		$fields['logo'] 			= $this->lang->line('settings_logo');
		$fields['invoice_note_default'] = $this->lang->line('settings_default_note');
		$fields['currency_type'] 	= $this->lang->line('settings_currency type');
		$fields['currency_symbol'] 	= $this->lang->line('settings_currency symbol');
		$fields['days_payment_due']	= $this->lang->line('settings_payment_days');
		$fields['tax_code'] 		= $this->lang->line('settings_tax_code');
		$fields['tax1_desc'] 		= $this->lang->line('invoice_tax1_description');
		$fields['tax1_rate'] 		= $this->lang->line('invoice_tax1_rate');
		$fields['tax2_desc'] 		= $this->lang->line('invoice_tax2_description');
		$fields['tax2_rate'] 		= $this->lang->line('invoice_tax2_rate');
		$fields['save_invoices'] 	= $this->lang->line('settings_display_branding');
		$fields['display_branding'] = $this->lang->line('settings_display_branding');
		$fields['new_version_autocheck'] = $this->lang->line('utilities_automatic_version_check');

		$this->validation->set_fields($fields);

		$this->validation->set_error_delimiters('<span class="error">', '</span>');
	}
}
?>