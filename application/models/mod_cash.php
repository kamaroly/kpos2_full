<?php
Class Mod_cash extends CI_Model
{
	public function _construct()
	{
		parent::_construct();
	}
	public function temp_tables()
	{
		//Creating temporary tables which are going to be used in cash module
		//creating temporary table for temp from
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_from_accounts (SELECT a.`Transaction_id`, 
        a.`date`, a.`Amount`, 
		status,
    	a.`from_account_id`,
        a.`description` as transfer_desc,
	    a.`personal_id`,
	    b.name to_name, 
	    b.Description account_desc,
	    b.Date_created AS user_created_date,
	    CONCAT(c.first_name," ",c.last_name) as employee_names
        FROM ospos_cash_transaction a, ospos_t_accounts b,ospos_people c
        WHERE a.from_account_id = b.Account_id and a.personal_id=c.person_id);');
		
		//creating the temporary table for to transaction
		
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_to_Transaction (
                          SELECT a.`Transaction_id`, 
                          a.`date`, a.`Amount`, 
	                 	  status,
    	                  a.`to_account_id`,
                          a.`description` as transfer_desc,
	                     a.`personal_id`,
	                      b.name to_name, 
	                      b.Description account_desc,
	                      b.Date_created AS user_created_date,
	                     CONCAT(c.first_name," ",c.last_name) as employee_names
                         FROM ospos_cash_transaction a, ospos_t_accounts b,ospos_people c
                         WHERE a.to_account_id = b.Account_id and a.personal_id=c.person_id);');
		
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_all_transaction(
                          SELECT a . * , b.`status` to_status,
				          b.to_account_id, b.`date` AS transfer_date,
				          b.`to_name` , b.`user_created_date` to_created_date
                          FROM `ospos_temp_from_Transaction` AS a, ospos_temp_to_Transaction b
                          WHERE a.`Transaction_id` = b.`Transaction_id`); ');
		
		}
	//We are retrieving all Accounts we have in database
	function get_all_accounts($cond)
	{
		if ($cond!=null)
		{
			$this->db->where($cond);
			$query=$this->db->get('ospos_t_accounts');
			return $query->result();
		}
		else
		{
		
		$query=$this->db->get('ospos_t_accounts');
		return $query->result();
		}
		
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
	//FUNCTION TO UPDATE THE ACCOUNT
	function update_account($cond,$info)
	{
		$this->db->where($cond);
		$ch=$this->db->update('ospos_t_accounts',$info);
		if($ch)
		{
		return true;
		}
		else 
		{
			return false;
		}
				
	}
	
	//We are going to get all Cash transaction that has been made
	function view_transactions($cond)
	{
		//Creating temporary tables which are going to be used in cash module
		//creating temporary table for temp from
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_from_accounts (SELECT a.`Transaction_id`, 
        a.`date`, a.`Amount`, 
		status,
    	a.`from_account_id`,
        a.`description` as transfer_desc,
	    a.`personal_id`,
	    b.name from_name, 
	    b.Description account_desc,
	    b.Date_created AS user_created_date,
	    CONCAT(c.first_name," ",c.last_name) as employee_names
        FROM ospos_cash_transaction a, ospos_t_accounts b,ospos_people c
        WHERE a.from_account_id = b.Account_id and a.personal_id=c.person_id);');
		
		//creating the temporary table for to transaction
		
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_to_Transaction (
                          SELECT a.`Transaction_id`, 
                          a.`date`, a.`Amount`, 
	                 	  status,
    	                  a.`to_account_id`,
                          a.`description` as transfer_desc,
	                      a.`personal_id`,
	                      b.name to_name, 
	                      b.Description account_desc,
	                      b.Date_created AS user_created_date,
	                     CONCAT(c.first_name," ",c.last_name) as employee_names
                         FROM ospos_cash_transaction a, ospos_t_accounts b,ospos_people c
                         WHERE a.to_account_id = b.Account_id and a.personal_id=c.person_id);');
		
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_all_transaction(
                          SELECT a . * , b.`status` to_status,
				          b.to_account_id, b.`date` AS transfer_date,
				          b.`to_name` , b.`user_created_date` to_created_date
                          FROM `ospos_temp_from_accounts` AS a, ospos_temp_to_Transaction b
                          WHERE a.`Transaction_id` = b.`Transaction_id`); ');
		
		
		if ($cond!=null)
		{
			$this->db->where($cond);
			$query=$this->db->get('ospos_temp_all_transaction');
			return $query->result();
		}
		else 
		{
				
		$query=$this->db->get('ospos_temp_all_transaction');
		return $query->result();
		}
	}
	//we are going to save all Cash Transaction that has been made
	
	function save_cash_transaction($transactions)
	{
		
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
	
	function edit_transaction($cond,$update)
	{
		$this->db->where($cond);
		if($this->db->update('ospos_cash_transaction',$update))
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
	//FUNCTION TO RETRIEVE THE PERSONAL HOW HAVE DONE TRANSACTION
	
	//DELETING GIVEN TRANSACTION
	FUNCTION delete_transaction($cond)
	{
		$this->db->where($cond);
	    if ($this->db->delete('ospos_cash_transaction'))
		{
			Return "Transaction $transaction Deleted Successful";
		}
		
		else
			
		{
			Return "Error occured while deleting $transaction Transaction";
		}
	}
	//get all reports
	function report_result()
	{
		
		//Creating temporary tables which are going to be used in cash module
		//creating temporary table for temp from
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_from_accounts (SELECT a.`Transaction_id`, 
        a.`date`, a.`Amount`, 
		status,
    	a.`from_account_id`,
        a.`description` as transfer_desc,
	    a.`personal_id`,
	    b.name from_name, 
	    b.Description account_desc,
	    b.Date_created AS user_created_date,
	    CONCAT(c.first_name," ",c.last_name) as employee_names
        FROM ospos_cash_transaction a, ospos_t_accounts b,ospos_people c
        WHERE a.from_account_id = b.Account_id and a.personal_id=c.person_id);');
		
		//creating the temporary table for to transaction
		
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_to_Transaction (
                          SELECT a.`Transaction_id`, 
                          a.`date`, a.`Amount`, 
	                 	  status,
    	                  a.`to_account_id`,
                          a.`description` as transfer_desc,
	                      a.`personal_id`,
	                      b.name to_name, 
	                      b.Description account_desc,
	                      b.Date_created AS user_created_date,
	                     CONCAT(c.first_name," ",c.last_name) as employee_names
                         FROM ospos_cash_transaction a, ospos_t_accounts b,ospos_people c
                         WHERE a.to_account_id = b.Account_id and a.personal_id=c.person_id);');
		
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_all_transaction(
                          SELECT a . * , b.`status` to_status,
				          b.to_account_id, b.`date` AS transfer_date,
				          b.`to_name` , b.`user_created_date` to_created_date
                          FROM `ospos_temp_from_accounts` AS a, ospos_temp_to_Transaction b
                          WHERE a.`Transaction_id` = b.`Transaction_id`); ');
		    $this->db->where(array("status"=>$status));
		    $this->db->where(array('personal_id'=>$personal_id));
		    $this->db->where('transfer_date BETWEEN "'.$from_date. ' 00:00:00" and "'. $to_date.' 00:00:00"');
			$query=$this->db->get('ospos_temp_all_transaction');
			return $query->result();
		
	}
	//GET THE SUM OF THE AMOUNT 
	function report_sum($cond)
	{  
		//Creating temporary tables which are going to be used in cash module
		//creating temporary table for temp from
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_from_accounts (SELECT a.`Transaction_id`, 
        a.`date`, a.`Amount`, 
		status,
    	a.`from_account_id`,
        a.`description` as transfer_desc,
	    a.`personal_id`,
	    b.name from_name, 
	    b.Description account_desc,
	    b.Date_created AS user_created_date,
	    CONCAT(c.first_name," ",c.last_name) as employee_names
        FROM ospos_cash_transaction a, ospos_t_accounts b,ospos_people c
        WHERE a.from_account_id = b.Account_id and a.personal_id=c.person_id);');
		
		//creating the temporary table for to transaction
		
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_to_Transaction (
                          SELECT a.`Transaction_id`, 
                          a.`date`, a.`Amount`, 
	                 	  status,
    	                  a.`to_account_id`,
                          a.`description` as transfer_desc,
	                      a.`personal_id`,
	                      b.name to_name, 
	                      b.Description account_desc,
	                      b.Date_created AS user_created_date,
	                     CONCAT(c.first_name," ",c.last_name) as employee_names
                         FROM ospos_cash_transaction a, ospos_t_accounts b,ospos_people c
                         WHERE a.to_account_id = b.Account_id and a.personal_id=c.person_id);');
		
		$this->db->query('CREATE TEMPORARY TABLE ospos_temp_all_transaction(
                          SELECT a . * , b.`status` to_status,
				          b.to_account_id, b.`date` AS transfer_date,
				          b.`to_name` , b.`user_created_date` to_created_date
                          FROM `ospos_temp_from_accounts` AS a, ospos_temp_to_Transaction b
                          WHERE a.`Transaction_id` = b.`Transaction_id`); ');
		$this->db->select_sum('Amount');
		$this->db->from('ospos_temp_all_transaction');
		$this->db->where($cond);
		$query=$query = $this->db->get();
		return $query->result;
	
	}
}
?>