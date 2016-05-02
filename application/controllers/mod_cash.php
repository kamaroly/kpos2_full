<?php
Class Mod_cash extends CI_Model
{
	public function _construct()
	{
		parent::_construct();
	}
	//We are retrieving all Accounts we have in database
	function get_all_accounts()
	{
		$query=$this->db->get('ospos_t_accounts');
		return $query->result();
	}
	//We are creating new account in the database
	function save_account($account_info)
	{
		$insert=$this->db->insert('ospos_t_accounts',$account_info);
	    if($insert)
	    {
	    	return "Account Created Successfull";
	    }
	    else
	    {
	    	Return "Error Occured when trying to create new Account";
	    }
	}
	//We are going to get informatio for one account
	function view_account($id)
	{
	    $this->db->where('Account_id',$id);
	    $query->db->get('ospos_t_accounts');
		$this->index();
	}
	
	//We are going to get all Cash transaction that has been made
	function view_transactions()
	{
		$query=$this->db->get('ospos_cash_transaction');
		return $query->result();
	}
	//we are going to save all Cash Transaction that has been made
	
	function save_cash_transaction($transactions)
	{
		$transactions=array(
				
				);
		$insert=$this->db->insert('ospos_cash_transaction',$transactions);
		if($insert)
		{
			return "Transaction  Have done Successfull";
		}
		else
		{
			Return "Error during Transaction, Check  you have done everything correctly";
		}
	}
	
	//WE ARE GOING TO EDIT TRANSACTION WHICH HAS BEEN MADE
	
	function edit_cash_transaction($transaction)
	{
		$this->db->where($data);
		if($this->db->update('ospos_cash_transaction'))
		{
			Return "Transaction updated Successful";
		}
		else
		{
			Return "Unable to update this transaction";
		}
		
	}
	
	//UPDATING GIVEN ACCOUNT INFORMATION
	
	function edit_account($account)
	{
		$this->db->where($account);
		if($this->db->update('ospos_t_accounts'))
		{
			Return "Account updated Successful";
		}
		else
		{
			Return "Error Occured During Account Update";
		}
	}
	
	//DELETING GIVEN ACCOUNT ID
	function delete_account($account_id)
	{
		$this->db->where($account_id);
		if ($this->db->delete('ospos_t_accounts'))
		{
			Return "Account $account_id Deleted Successful";
		}
		else
		{
			Return "Error occured while deleting $account_id Account";
		}
	}
	
	//DELETING GIVEN TRANSACTION
	FUNCTION delete_transaction($transaction_id)
	{
		$this->db->where($transaction);
	    if ($this->db->delete())
		{
			Return "Transaction $transaction Deleted Successful";
		}
		
		else
			
		{
			Return "Error occured while deleting $transaction Transaction";
		}
	}
}
?>