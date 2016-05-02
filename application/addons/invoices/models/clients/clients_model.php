<?php
class clients_model extends MY_Model {

	/**
	 * Description client contact information
	 * @name GetClient_contats
	 * @author Kamaro Lambert
	 * @category Model
	 * @method client contact information
	 */
	function getclient_contact($id=null)
	{
		// client contact information
		$this->db->where('client_id',$id);
		$this->db->order_by("last_name", "first_name");
		$clientContacts = $this->db->get('clientcontacts');
		return $clientContacts->result();
	}
	
	/**
	 * @name  clientContactCount
	 * @author Kamaro Lambert
	 * @category Model
	 * @method to get the contacts per client
	 */
	function clientContactCount($id=null)
	{
	$this->db->where('client_id', $id);
	$this->db->order_by("last_name", "first_name");
	$clientContacts = $this->db->get('clientcontacts');
	return $clientContacts->num_rows();
	}
	
	/**
	 * @name countAllClients
	 * @author Kamaro Lambert
	 * @category Model
	 * @method to get the contacts per client
	 */
	function countAllClients($company_id,$user_id=null)
	{
		
		$this->db->where(array('company_id'=>$company_id?$company_id:0,
				                'user_id'=>$user_id?$user_id:0));
		return 	 $this->db->count_all_results('clients');
	}

	// --------------------------------------------------------------------

	function countClientInvoices($client_id)
	{
		$this->db->where('client_id', $client_id);

		return $this->db->count_all_results('invoices');
	}

	// --------------------------------------------------------------------

	function getAllClients($company_id,$user_id=null)
	{
		// we need an array of company names to associate each contact with its company
//		$companies = array();
//		foreach($this->clients_model->getAllClients()->result() as $company)
//		{
//			$companies[$company->id] = $company->name;
//		}
        $this->db->where(array('company_id'=>$company_id?$company_id:0,
        		               'user_id'=>$user_id?$user_id:0));
		$this->db->order_by('name', 'asc');

		return $this->db->get('clients');
	}
    
	// --------------------------------------------------------------------

	function get_client_info($id, $fields = '*',$company_id,$user_id=null)
	{
		$this->db->select($fields);
	 	$this->db->where('id', $id);
	 	$this->db->where(array('company_id'=>$company_id?$company_id:0,
	 			              'user_id'=>$user_id?$user_id:0));
		return $this->db->get('clients')->row();
	}

	// --------------------------------------------------------------------

	function getClientContacts($id)
	{
		$this->db->where('client_id', $id);

		return $this->db->get('clientcontacts');
	}

	// --------------------------------------------------------------------

	function addClient($clientInfo)
	{
		

		return $this->db->insert('clients', $clientInfo);;
	}

	// --------------------------------------------------------------------

	function updateClient($client_id, $clientInfo)
	{
		$this->db->where('id', $client_id);
		

		return $this->db->update('clients', $clientInfo);;
	}

	// --------------------------------------------------------------------

	function deleteClient($client_id)
	{
		// Don't allow admins to be deleted this way
		if ($client_id === 0)
		{
			return FALSE;
		}
		else
		{
			// get all invoices related to this client
			$this->db->select('id');
			$this->db->where('client_id', $client_id);
			$result = $this->db->get('invoices');

			$invoice_id_array = array(0);

			foreach ($result->result() as $invoice_id)
			{
				$invoice_id_array[] = $invoice_id->id;
			}

			// There are 5 tables of data to delete from in order to completely
			// clear out record of this client.

			$this->db->where_in('invoice_id', $invoice_id_array);
			$this->db->delete('invoice_histories');

			$this->db->where_in('invoice_id', $invoice_id_array);
			$this->db->delete('invoice_payments');

			$this->db->where('client_id', $client_id);
			$this->db->delete('clientcontacts'); 

			$this->db->where('id', $client_id);
			$this->db->delete('clients');

			$this->db->where('client_id', $client_id);
			$this->db->delete('invoices'); 

			return TRUE;
		}
	}

}
?>
