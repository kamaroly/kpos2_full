<?php
Class Mdl_items_categories Extends MY_Model
{
	
	function __construct()
	{
		$this->_table='items_categories';
	}
	/**
	 * function to get all the items in the table
	 * @author Kamaro Lambert
	 * @name get_categories()
	 * @param unknown_type $id
	 */
	function get_categories($id=null,$limit=0,$offset=0)
	{
		//we first check if the use wants a specific categorie
		if ($id!=null)
		{ 
			$this->db->where('id',$id);
		}
		$this->db->where('deleted',0);
		   //we set the limit
		$this->db->limit($limit,$offset);
		return $this->db->get($this->_table);
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name Count_all
	 * @example count_all()
	 * @method to count all categories we have in the databse which are not deleted
	 */
	function count_all()
	{
		$this->db->from($this->_table);
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	/**
	 *@author Kamaro Lambert
	 *@method to save a category
	 *
	 */
	/*
	 Inserts or updates a item
	*/
	function save(&$item_category_data,$item_category_id=false)
	{
		
		if (!$item_category_id or !$this->exists($item_category_id))
		{
			
			if($this->db->insert($this->_table,$item_category_data))
			{
				$item_data['id']=$this->db->insert_id();
				return true;
			}
			
			return false;
		}
	   
		$this->db->where('id', $item_category_id);
		return $this->db->update($this->_table,$item_category_data);
	}
	/**
	 * @author Kamaro Lambert
	 * @method to delete category
	 * (non-PHPdoc)
	 * @see MY_Model::delete()
	 */
	function delete($category_ids)
	{
		$this->db->where('id', $item_id);
		return $this->db->update($this->_table, array('deleted' => 1));
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to determine
	 * @description Determines if a given item_id is an item
	*/
	function exists($item_category_id)
	{
		$this->db->from($this->_table);
		$this->db->where('id',$item_category_id);
		$query = $this->db->get();
	
		return ($query->num_rows()==1);
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to determine if category existy by the name
	 * @description Determines if a given item_id is an item
	 * @return category_id
	 */
	function exists_by_name($item_category_name)
	{
		$this->db->select('id');
		$this->db->from($this->_table);
		$this->db->where('cname',$item_category_name);
		$query = $this->db->get();
	
		return $query->row();
	}
	/**
	 * @author Kamaro Lambert
	 * @method to search
	 * 
	 */

	/*
	 Preform a search on items
	*/
	function search($search)
	{
		$this->db->from($this->_table);
		$this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%' or
		description LIKE '%".$this->db->escape_like_str($search)."%' and deleted=0)");
		$this->db->order_by("name", "asc");
		return $this->db->get();
	}
	/**
	 * @author Kamaro Lambert
	 * @method to delete
	 * @param unknown_type $item_ids
	 */
	function delete_list($category_id)
	{
		$this->db->where_in('id',$category_id);
		return $this->db->update($this->_table, array('deleted' => 1));
	}
	
	/*
	 Get search suggestions to find items
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
	
		$this->db->from($this->_table);
		$this->db->like('name', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->name;
		}
	
		$this->db->select('description');
		$this->db->from($this->_table);
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->like('description', $search);
		$this->db->order_by("description", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->category;
		}
	
		$this->db->from($this->_table);
		$this->db->like('id', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("id", "asc");
		$by_item_number = $this->db->get();
		foreach($by_item_number->result() as $row)
		{
			$suggestions[]=$row->item_number;
		}
	
	
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}
	
	
}