<?php
class settings_models extends CI_Model 
{
	/**
	 * @name getCompanyInfo
	 * @author Bamboo invoice
	 * @Author Kamaro Lambert
	 * @method to return the user company information
	 * @param unknown_type $user_id
	 */

	function getCompanyInfo($user_id=null)
	{
		$this->db->where('user_id',$user_id);
		return $this->db->get('settings');
	}

	/**
	 * @name get_setting
	 * @author Kamaro Lambert 
	 * @method to get the settings of the given user 
	 * @param unknown_type $field
	 * @param unknown_type $user_id
	 * @return boolean
	 */
	function get_settingssss($field,$user_id=null)
	{
		       $this->db->where('user_id',$user_id);
		$row = $this->db->get('settings');

		if ($row->num_rows() === 1)
		{
			return $row->row()->$field;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * @name update_settings()
	 * @author Kamaro Lambert
	 * @method to add new company information in the database
	 * @params array data
	 */

	function update_settings($data = array(), $id =0)
	{
		print_r($data);
		return true;
		exit;
		if (count($data) == 0)
		{
			return TRUE; // no changes, just return a success
		}
      
			$this->db->where('id', $id);
			
			return $this->db->update('settings', $data);
		
		
	}
	/**
	 * @name add_settings()
	 * @author Kamaro Lambert
	 * @method to add new company information in the database
	 * @params array data
	 */
	
	
	public function add_settings($data=array())
	{
		if (count($data) == 0)
		{
			return TRUE; // no changes, just return a success
		}
		
		return $this->db->insert('settings', $data);
	}
	

}
?>