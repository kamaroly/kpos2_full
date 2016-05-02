<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package    Kinvoice
 * @subpackage Invoices
 * @name       Clientcontacts
 * @category   Controller
 * @author     Derrek
 * @author     Kamaro Lambert
 * @copyright  Kamaro Lambert
*/

include(APPPATH.'controllers/secure_area.php');

class Clientcontacts extends Secure_area {

	function __construct()
	{
		
		parent::__construct();
		//Loading languages
		//-----------------
		$this->load->language('invoices/invoices');
		//Loading libraries
		//---------------
		$this->load->library('form_validation');
		//Loading helpers
		//---------------
		$this->load->helper('invoices/ajax');
		//Loading models.
		//----------------
		$this->load->model('invoices/clients/clientcontacts_model');
	}

	// --------------------------------------------------------------------

	function index()
	{
		/**
		 * This controller is only used from the clients controller, and so is called directly.
		 * If anyone access it directly, let's just move them over to clients.
		 */
		redirect('clients/');
	}

	// --------------------------------------------------------------------

	function add($customer_id=null)
	{
		
		$this->_validation_client_contact(); // validation info for id, first_name, last_name, email, phone

		if ($this->form_validation->run() == FALSE)
		{
			if (isAjax())
			{
				echo $this->lang->line('clients_new_contact_fail');
			}
			else
			{
				$cid = (int) $this->input->post('customer_id');
				$this->data['customer_id'] = ($cid) ? $cid : $customer_id;
				$this->data['page_title'] = $this->lang->line('clients_add_contact');
				$this->load->view('clientcontacts/add',$this->data); //Define the main content
				
				
				
			}
		}
		else
		{
			$client_id = $this->clientcontacts_model->addClientContact(
																		$this->input->post('customer_id'), 
																		$this->input->post('first_name'), 
																		$this->input->post('last_name'), 
																		$this->input->post('email'), 
																		$this->input->post('phone'),
																		$this->input->post('title')
																	);

			if (isAjax())
			{
				echo $client_id;
			}
			else
			{
				$this->session->set_flashdata('clientContact', (int) $this->input->post('client_id'));
				redirect('clients/viewclient/'.$this->input->post('client_id'));
			}
		}
	}

	// --------------------------------------------------------------------

	function edit($contact_id=null)
	{
		$rules['id'] = 'trim|required|numeric';
		$fields['id'] = 'id';

		$this->_validation_client_contact(); // validation info for first_name, last_name, email, phone

		$this->data['id'] = (int) $contact_id;
       
		if ($this->form_validation->run() == FALSE)
		{
			$this->data['clientContactData'] = $this->clientcontacts_model->getContactInfo($this->data['id']);
			
			$this->data['page_title'] = $this->lang->line('clients_edit_contact');
			$this->load->view('clientcontacts/edit',$this->data); //Define the main content
			
			
			
		}
		else
		{
			$this->clientcontacts_model->editClientContact(
															$this->input->post('id'), 
															$this->input->post('client_id'),
															$this->input->post('first_name'),
															$this->input->post('last_name'), 
															$this->input->post('email'), 
															$this->input->post('phone'),
															$this->input->post('title')
														);

			$this->session->set_flashdata('message', $this->lang->line('clients_edited_contact_info'));
			$this->session->set_flashdata('clientEdit', $this->input->post('client_id'));
			redirect('clients/viewclient/'.$this->input->post('client_id'));
		}
	}

	// --------------------------------------------------------------------

	function delete()
	{
		$id = ($this->input->post('id')) ? (int) $this->input->post('id') : $this->uri->segment(3);

		if ($this->clientcontacts_model->deleteClientContact($id))
		{
			if (isAjax())
			{
				return $id;
			}
			else
			{
				$this->session->set_flashdata('clientContact', $id);
				redirect('clients/viewclient/'.$this->uri->segment(4));
			}
		}
		else
		{
			$this->session->set_flashdata('message', $this->lang->line('clients_contact_delete_fail'));
			redirect('clients/viewclient/'.$this->uri->segment(4));
		}
	}

	// --------------------------------------------------------------------

	function _validation_client_contact()
	{
		$config = array(
				array(
						'field'   => 'customer_id',
						'label'   => 'customer_id',
						'rules'   => 'trim|required|numeric'
				),
				array(
						'field'   => 'first_name',
						'label'   => 'First Name',
						'rules'   => 'trim|required|max_length[25]'
				),
				array(
						'field'   => 'last_name',
						'label'   => 'Last Name',
						'rules'   => 'trim|required|max_length[25]'
				),
				array(
						'field'   => 'email',
						'label'   => 'Email',
						'rules'   => 'trim|required|max_length[127]|valid_email'
				),
				array(
						'field'   => 'phone',
						'label'   => 'Phone',
						'rules'   => 'trim|max_length[20]'
				),
				array(
						'field'   => 'title',
						'label'   => 'Email',
						'rules'   => 'trim'
				)
				
		);
	
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<span class="alert alter-danger">', '</span>');
	}

}
?>