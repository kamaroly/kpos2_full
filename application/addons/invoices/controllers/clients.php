<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package    Kinvoice
 * @subpackage Invoices
 * @name       Invoices
 * @category   Controller
 * @author     Derrek
 * @author     Kamaro Lambert
 * @copyright  Kamaro Lambert
*/

include(dirname(__FILE__) .'/auth_public.php');

class Clients extends Auth_public {

	function __construct()
	{
		
		parent::__construct();
		//if user didn't configure his company information then
		if($this->company_id<1 or $this->company_id==null)
		{
			redirect('settings');
		}
		$this->load->helper('date');
		$this->load->library('validation');
		$this->load->model('clients/clients_model');
		$this->data = null;
		$this->data['user_info'] = $this->flexi_auth->get_user_by_identity_row_array();
		$data['user_info']=$this->data['user_info'];
	}

	// --------------------------------------------------------------------

	function index()
	{
		$this->data['clientList'] = $this->clients_model->getAllClients($this->company_id,$this->user_id); // activate the option
		if ($this->session->flashdata('clientEdit'))
		{
			$this->data['message'] = $this->lang->line('clients_edited');
		}
		else
		{
			$this->data['message'] = $this->session->flashdata('message');
		}
		// Set any returned status/error messages.
		
		
		$this->data['total_rows'] = $this->clients_model->countAllClients($this->company_id,$this->user_id);
       
		
		// Run the limited version of the query
		$this->data['all_clients'] = $this->clients_model->getAllClients($this->company_id,$this->user_id);

		$this->_validation_client_contact(); // validation info for id, first_name, last_name, email, phone

		$this->data['page_title'] = $this->lang->line('menu_clients');
		$data = array(
				'kinvoice_title' => 'Kamaro Invoice', //I am setting the title of the page
				'kinvoice_heading' => 'Dashboard', //I am setting the head of the page
				'page_title'=>$this->data['page_title'],
				'base_url' => base_url(), //defining base url
				'navigation_top'=>$this->load->view( 'theme/partials/notification_area',$this->data,true), //Define the top navigation
				'main_content'=>$this->load->view('clients/index', $this->data,TRUE) //Define the main content
		);
		//Building our templates with the data
		$this->parser->parse('theme/layout/default', $data);
		
		
	}

	// --------------------------------------------------------------------

	function newclient()
	{
		// if the client already exists, then the post var client_id will come through
		if ($this->input->post('client_id'))
		{
			$this->session->set_flashdata('clientId', $this->input->post('client_id'));
			redirect('invoices/newinvoice/');
		}
		elseif ($this->input->post('newClient'))
		{
			$this->session->set_flashdata('clientName', $this->input->post('newClient'));
		}

		$this->data['clientName'] = $this->input->post('newClient'); // store the name provided in a var

		/**
		* There is a bug on this page where it is passing validation when the user first loads
		* it.  As a quick workaround, I'm detecting if they came from the new invoice form with
		* the hidden form variable "newInvoice"
		*/
		$newinv = $this->input->post('newInvoice');
		/**
		* ugh... sorry
		*/
		$this->data['page_title'] = $this->lang->line('menu_newclient');
		$this->_validation(); // Load the validation rules and fields

		if ($this->validation->run() == FALSE || $newinv != '')
		{
			$data['page_title'] = $this->lang->line('clients_create_new_client');
			$data = array(
					'kinvoice_title' => 'Kamaro Invoice', //I am setting the title of the page
					'kinvoice_heading' => 'Dashboard', //I am setting the head of the page
					'page_title'=>$this->data['page_title'],
					'base_url' => base_url(), //defining base url
					'navigation_top'=>$this->load->view( 'theme/partials/notification_area',$this->data,true), //Define the top navigation
					'main_content'=>$this->load->view('clients/newclient', $this->data,TRUE) //Define the main content
			);
			//Building our templates with the data
			$this->parser->parse('theme/layout/default', $data);
	
		}
		else
		{
			// capture information for inserting a new client
			$clientInfo = array(
				'name' => $this->input->post('clientName'),
				'address1' => $this->input->post('address1'),
				'address2' => $this->input->post('address2'),
				'city' => $this->input->post('city'),
				'province' => $this->input->post('province'),
				'country' => $this->input->post('country'),
				'postal_code' => $this->input->post('postal_code'),
				'website' => $this->input->post('website'),
				'tax_status' => $this->input->post('tax_status'),
				'tax_code' => $this->input->post('tax_code'),
				'company_id'=> $this->company_id,
				'user_id'	=> $this->user_id
			);

			// make insertion, grab insert_id
			if ($this->clients_model->addClient($clientInfo))
			{
				$this->session->set_flashdata('clientId', $this->db->insert_id());
				$this->session->set_flashdata('clientContact', TRUE);
			}
			else
			{
				show_error($this->lang->line('error_problem_inserting'));
			}

			if ($this->session->flashdata('clientName'))
			{
				redirect('invoices/newinvoice/');
			}
			else
			{
				// return to clients page
				$this->session->set_flashdata('message', $this->lang->line('clients_created'));
				redirect('clients/');
			}
		}
	}

	// --------------------------------------------------------------------

	function notes($client_id)
	{
		$notes = $this->input->post('client_notes');
		$notes_submit = $this->input->post('notes_submit') ? TRUE : FALSE;

		$this->data['row'] = $this->clients_model->get_client_info($client_id);

		// new notes?  Update, move them on, and tell them its good
		if ($notes_submit)
		{
			$this->clients_model->updateClient($client_id, array('client_notes'=>$notes));
			// Set a custom error message.
			$this->flexi_auth_model->set_status_message($this->lang->line('clients_edited'), 'config');
			$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
				
			redirect('clients/viewclient/'.$client_id);
		}
		else
		{
			$this->data['page_title'] = $this->lang->line('clients_notes').' : '.$this->data['row']->name;
			
			
			$data = array(
					'kinvoice_title' => 'Kamaro Invoice', //I am setting the title of the page
					'kinvoice_heading' => 'Dashboard', //I am setting the head of the page
					'page_title'=>$this->data['page_title'],
					'base_url' => base_url(), //defining base url
					'navigation_top'=>$this->load->view( 'theme/partials/notification_area',$this->data,true), //Define the top navigation
					'main_content'=>$this->load->view('clients/notes',$this->data,TRUE) //Define the main content
			);
			//Building our templates with the data
			$this->parser->parse('theme/layout/default', $data);
				
		}
	}

	// --------------------------------------------------------------------

	function edit()
	{
		$this->_validation(); // Load the validation rules and fields

		if ($this->validation->run() == FALSE)
		{
			$cid = (int) $this->input->post('id');
			$this->data['id'] = ($cid) ? $cid : $this->uri->segment(3);

			$this->data['row'] = $this->clients_model->get_client_info($this->data['id'],'*',$this->company_id,$this->user_id);

			$this->data['page_title'] = $this->lang->line('clients_edit_client');
			$data = array(
					'kinvoice_title' => 'Kamaro Invoice', //I am setting the title of the page
					'kinvoice_heading' => 'Dashboard', //I am setting the head of the page
					'page_title'=>$this->data['page_title'],
					'base_url' => base_url(), //defining base url
					'navigation_top'=>$this->load->view( 'theme/partials/notification_area',$this->data,true), //Define the top navigation
					'main_content'=>$this->load->view('clients/edit',$this->data,TRUE) //Define the main content
			);
			//Building our templates with the data
			$this->parser->parse('theme/layout/default', $data);
			
		}
		else
		{
			$clientInfo = array(
								'id' => (int) $this->input->post('id'),
								'name' => $this->input->post('clientName'),
								'address1' => $this->input->post('address1'),
								'address2' => $this->input->post('address2'),
								'city' => $this->input->post('city'),
								'province' => $this->input->post('province'),
								'country' => $this->input->post('country'),
								'postal_code' => $this->input->post('postal_code'),
								'website' => $this->input->post('website'),
								'tax_status' => $this->input->post('tax_status'),
								'tax_code' => $this->input->post('tax_code'),
					            'company_id'=> $this->company_id,
					            'user_id'	=> $this->user_id
								);

			$this->clients_model->updateClient($clientInfo['id'], $clientInfo);
			$this->session->set_flashdata('clientEdit', $clientInfo['id']);
			redirect('clients/viewclient/'.$this->input->post('id'));
		}
	}

	// --------------------------------------------------------------------

	function delete($client_id)
	{
		// get number of invoices for when we ask if they are sure they want to remove this client
		$data['numInvoices'] = $this->clients_model->countClientInvoices($client_id);

		$this->session->set_flashdata('deleteClient', $client_id);
		$data['deleteClient'] = $client_id;

		$data['page_title'] = $this->lang->line('clients_delete_client');
		$this->load->view('clients/delete', $data);
	}

	// --------------------------------------------------------------------

	function delete_confirmed()
	{
		$client_id = (int) $this->session->flashdata('deleteClient');

		if ($this->clients_model->deleteClient($client_id))
		{
			$this->session->set_flashdata('message', $this->lang->line('clients_deleted'));
			redirect('clients/');
		}
		else
		{
			$this->session->set_flashdata('message', $this->lang->line('clients_deleted_error'));
			redirect('clients/');
		}
	}

	// --------------------------------------------------------------------

	function _validation()
	{
		$rules['clientName'] 	= 'trim|required|max_length[75]|htmlspecialchars';
		$rules['website'] 		= 'trim|htmlspecialchars|max_length[150]';
		$rules['address1'] 		= 'trim|htmlspecialchars|max_length[100]';
		$rules['address2'] 		= 'trim|htmlspecialchars|max_length[100]';
		$rules['city'] 			= 'trim|htmlspecialchars|max_length[50]';
		$rules['province'] 		= 'trim|htmlspecialchars|max_length[25]';
		$rules['country'] 		= 'trim|htmlspecialchars|max_length[25]';
		$rules['postal_code'] 	= 'trim|htmlspecialchars|max_length[10]';
		$rules['tax_status'] 	= 'trim|htmlspecialchars|exact_length[1]|numeric|required';
		$rules['tax_code'] 		= 'max_length[75]';
		$this->validation->set_rules($rules);

		$fields['clientName'] 	= $this->lang->line('clients_name');
		$fields['website'] 		= $this->lang->line('clients_website');
		$fields['address1'] 	= $this->lang->line('clients_address1');
		$fields['address2'] 	= $this->lang->line('clients_address2');
		$fields['city'] 		= $this->lang->line('clients_cityt');
		$fields['province'] 	= $this->lang->line('clients_province');
		$fields['country'] 		= $this->lang->line('clients_country');
		$fields['postal_code'] 	= $this->lang->line('clients_postal');
		$fields['tax_status'] 	= $this->lang->line('invoice_tax_status');
		$fields['tax_code'] 	= $this->lang->line('settings_tax_code');
		$this->validation->set_fields($fields);

		$this->validation->set_error_delimiters('<span class="error">', '</span>');
	}

	// --------------------------------------------------------------------

	function _validation_client_contact()
	{
		$rules['client_id'] 	= 'trim|required|htmlspecialchars|numeric';
		$rules['first_name'] 	= 'trim|required|htmlspecialchars|max_length[25]';
		$rules['last_name'] 	= 'trim|required|htmlspecialchars|max_length[25]';
		$rules['email'] 		= 'trim|required|htmlspecialchars|max_length[127]|valid_email';
		$rules['phone'] 		= 'trim|htmlspecialchars|max_length[20]';
		$rules['title'] 		= 'trim|htmlspecialchars';
		$this->validation->set_rules($rules);

		$fields['client_id'] 	= $this->lang->line('clients_id');
		$fields['first_name'] 	= $this->lang->line('clients_first_name');
		$fields['last_name'] 	= $this->lang->line('clients_last_name');
		$fields['email'] 		= $this->lang->line('clients_email');
		$fields['phone'] 		= $this->lang->line('clients_phone');
		$fields['title'] 		= $this->lang->line('clients_title');
		$this->validation->set_fields($fields);

		$this->validation->set_error_delimiters('<span class="error">', '</span>');
	}
	
	/**
	 * @name viewclients
	 * @author Kamaro Lambert
	 * @category Method
	 * @param integer client_id
	 */
	
     function viewclient($id=null)
     {
     	$this->data['clientContacts'] =$this->clients_model->getclient_contact($id);
     	$this->data['clientContactCount']=$this->clients_model->clientContactCount($id);
     	$this->data['client'] = $this->clients_model->get_client_info($id,'*',$this->company_id,$this->user_id);
     	
     	$this->data['page_title'] = $this->lang->line('clients_create_new_client');
     	$data = array(
     			'kinvoice_title' => 'Kamaro Invoice', //I am setting the title of the page
     			'kinvoice_heading' => 'Dashboard', //I am setting the head of the page
     			'page_title'=>'View Client',
     			'base_url' => base_url(), //defining base url
     			'navigation_top'=>$this->load->view( 'theme/partials/notification_area',$this->data,true), //Define the top navigation
     			'main_content'=>$this->load->view('clients/viewclient',$this->data,TRUE) //Define the main content
     	);
     	//Building our templates with the data
     	$this->parser->parse('theme/layout/default', $data);
     	
     	
     }
}
?>