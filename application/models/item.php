<?php
class Item extends CI_Model
{
	/*
	Determines if a given item_id is an item
	*/
	function exists($item_id)
	{
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('items.deleted',0);
		$this->db->where('item_id',$item_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
   
	/*
	 Determines if a given item is already in database
	*/
	function exists_by_field($item_fields=array())
	{
		
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where($item_fields);
		$query = $this->db->get();
	
		return ($query->num_rows()==1);
	}
	
	/*
	 Determines if a given item is already in database and return its id
	*/
	function get_id_by_field($item_fields=array())
	{
		$this->db->select('item_id');
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where($item_fields);
		$query = $this->db->get();
	
		return $query->row();
	}
	/*
	Returns all the items
	*/
	function get_all($limit=10000, $offset=0)
	{
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('items.deleted',0);
		$this->db->order_by("name", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}
	
	function count_all()
	{
		$this->db->from('items');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
    /**
     * @author Kamaro Lambert
     * @method to count all items with low stock
     */
	function count_all_lowstock()
	{
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->where('quantity <=','reorder_level', false);
		return $this->db->count_all_results();
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to count all expiring items in the stock
	 */
	function count_all_expiring()
	{
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('items.deleted',0);
	
		
		$this->db->where('DATEDIFF(  `expiration_date`,  DATE( NOW( ) ) )<',$this->config->item('expiration_days'), false);
		return $this->db->count_all_results();
	}
	
	function get_all_filtered($low_inventory=0,$is_serialized=0,$no_description)
	{
		$this->db->from('items');
		if ($low_inventory !=0 )
		{
			$this->db->where('quantity <=','reorder_level', false);
		}
		if ($is_serialized !=0 )
		{
			$this->db->where('is_serialized',1);
		}
		if ($no_description!=0 )
		{
			$this->db->where('description','');
		}
		$this->db->where('deleted',0);
		$this->db->order_by("name", "asc");
		return $this->db->get();
	}

	/*
	Gets information about a particular item
	*/
	function get_info($item_id)
	{
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('item_id',$item_id);
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('items');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}

			return $item_obj;
		}
	}

	/*
	Get an item id given an item number
	*/
	function get_item_id($item_number)
	{
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('items.deleted',0);
		$this->db->where('item_number',$item_number);

		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->item_id;
		}

		return false;
	}

	/*
	Gets information about multiple items
	*/
	function get_multiple_info($item_ids)
	{
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where_in('item_id',$item_ids);
		$this->db->order_by("item_id", "asc");
		return $this->db->get();
	}

	/*
	Inserts or updates a item
	*/
	function save(&$item_data,$item_id=false)
	{
		if (!$item_id or !$this->exists($item_id))
		{
			$condition=array('item_number'=>$item_data['item_number']);
			
		 if ($this->exists_by_field($condition))
			{////Item  exist, let's update
			 $this->db->where('item_number', $item_data['item_number']);
			 if($this->db->update('items',$item_data))
			  {
			  $item_data['item_id']=$this->get_id_by_field($condition)->item_id;
			  return true;
			  }
			}
			//check if the item number doesn't exist 
				//Item doesn't exist, let's insert
	        if($this->db->insert('items',$item_data))
	          {
				$item_data['item_id']=$this->db->insert_id();
				return true;
			   }
			
			
			return false;
		}

		$this->db->where('item_id', $item_id);
		return $this->db->update('items',$item_data);
	}

	/*
	Updates multiple items at once
	*/
	function update_multiple($item_data,$item_ids)
	{
		$this->db->where_in('item_id',$item_ids);
		return $this->db->update('items',$item_data);
	}

	/*
	Deletes one item
	*/
	function delete($item_id)
	{
		$this->db->where('item_id', $item_id);
		return $this->db->update('items', array('deleted' => 1));
	}

	/*
	Deletes a list of items
	*/
	function delete_list($item_ids)
	{
		$this->db->where_in('item_id',$item_ids);
		return $this->db->update('items', array('deleted' => 1));
 	}

 	/*
	Get search suggestions to find items
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('items.deleted',0);
		$this->db->like('name', $search);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->name;
		}

		$this->db->select('category_id_id');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->like('category_id_id', $search);
		$this->db->order_by("category_id_id", "asc");
		$by_category_id = $this->db->get();
		foreach($by_category_id->result() as $row)
		{
			$suggestions[]=$row->category_id;
		}

		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('items.deleted',0);
		$this->db->like('item_number', $search);
		$this->db->order_by("item_number", "asc");
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

	function get_item_search_suggestions($search,$limit=25,$return_array=false)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('items.deleted',0);
		$this->db->like('name', $search);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		//Check if we need array
		if(!$return_array)
		{
		foreach($by_name->result() as $row)
		 {
			$suggestions[]=$row->item_id.'|'.$row->name;
		 }
		}
		else 
		{
			foreach($by_name->result() as $row)
			{
				$suggestions[$row->item_id]=$row->name;
			}
			
		}
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('items.deleted',0);
		$this->db->like('item_number', $search);
		$this->db->order_by("item_number", "asc");
		$by_item_number = $this->db->get();
		
		//Check if we need array
		if(!$return_array)
		{
		  foreach($by_item_number->result() as $row)
		   {
			$suggestions[]=$row->item_id.'|'.$row->item_number;
		   }
		}
		else
		{
			foreach($by_item_number->result() as $row)
			{
				$suggestions[$row->item_id]=$row->item_number;
			}
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}
/**
 * @author Kamaro Lambert
 * @method to get the category id by the suggestions
 * @param unknown_type $category_id
 * @return multitype:NULL
 */
	function get_category_id_suggestions($category_id)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('category_id');
		$this->db->from('items');
		$this->db->where('category_id', $category_id);
		$this->db->where('deleted', 0);
		$this->db->order_by("category_id", "asc");
		$by_category_id = $this->db->get();
		foreach($by_category_id->result() as $row)
		{
			$suggestions[]=$row->category_id;
		}

		return $suggestions;
	}
	
	/**
	 * @author Kamaro Lambert
	 * @name   items_by_category
	 * @method to get items by their category
	 * @params int $categoryid
	 */
	function items_by_category($category_id)
	{
		$suggestions = array();
		$this->db->select('item_id,name,unit_price,description,image');
		$this->db->where('category_id', $category_id);
		$this->db->where('deleted', 0);
		$this->db->order_by("category_id", "asc");
		$items= $this->db->get('items');
		
		return $items->result();
	}

	/*
	Preform a search on items
	*/
	function search($search)
	{
		$this->db->from('items');
		$this->db->join('items_categories','items.category_id=items_categories.id','LEFT');
		$this->db->where('items.deleted',0);
		$this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%' or 
		item_number LIKE '%".$this->db->escape_like_str($search)."%' or 
		category_id LIKE '%".$this->db->escape_like_str($search)."%') ");
		$this->db->order_by("name", "asc");
		return $this->db->get();	
	}

	function get_categories()
	{
		$this->db->select('category_id');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->order_by("category_id", "asc");

		return $this->db->get();
	}
}
?>
