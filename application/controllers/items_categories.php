<?php
/**
 * 
 * @author Kamaro LAMBERT
 * @method to deal with the categories of the items
 * @category Controllers
 *
 */
/*
 * We are extending the secure area to make sure the controller is protected
 */
require_once ("secure_area.php");
/**
 * Our class withh extend the secure area
 * @author user
 *
 */
Class Items_categories extends  secure_area
{
	/**
	 * Initiating the constructor of the class
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->language('module');
		$this->load->language('items');
		//loading the categories model
		$this->load->model('Mdl_items_categories');
		
	}
	/**
	 * @author Kamaro Lambert
	 * @name Index()
	 * @category method
	 * @method for the entry of our class that deals with categories of the items
	 */
	
	function index()
	{
		//loading the categories model
		$this->load->model('Mdl_items_categories');
		
		//getting all data we have in the table
		$config['base_url'] = site_url('/items_categories/index');
		$config['total_rows'] = $this->Mdl_items_categories->count_all();
		$config['per_page'] = '20';
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		
		$data['manage_table']=get_items_categories_manage_table( $this->Mdl_items_categories->get_categories(null, $config['per_page'], $this->uri->segment( $config['uri_segment'] ) ), $this );
		$this->load->view('items_categories/manage',$data);
	
		
		
	}
	/**
	 * @author Kamaro Lambert
	 * @method to add or modify category of the items
	 * @param unknown_type $item_category_id
	 */
	function view($item_category_id=-1)
	{
		//loading the categories model
		$this->load->model('Mdl_items_categories');
		
		if($item_category_id >0)
		{
		$data['item_info']=$this->Mdl_items_categories->get_categories($item_category_id, 1, 0)->row() ;
		}
		else 
		{
			$item_info=array();
			$item_info['id']='';
			$item_info['cname']='';
			$item_info['description']='';
			$data['item_info']=(object) $item_info;
			
		}
		
		$this->load->view("items_categories/form",$data);
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to save a category.
	 * 
	 */
	function save($item_category_id=-1)
	{
		
		$item_category_data = array(
				'cname'=>$this->input->post('name'),
				'description'=>$this->input->post('description'),
				
		);
	
		
		$cur_item_category_info = $this->Mdl_items_categories->get_categories($item_category_id, 1, 1)->row() ;
	
		
	 
		if($item_category_id=$this->Mdl_items_categories->save($item_category_data,$item_category_id))
		{
			
			//New item
			if($item_category_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_adding')));
				
			}
			else //previous item
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_updating')));
			}
				
			
			
		}
		else//failure
		{
		echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_adding_updating')));
		}
	
	}
	
	/**
	 * @author KAMARO Lambert
	 * @method to get one row
	 */
	function get_row()
	{
		$item_category_id = $this->input->post('row_id');
		$data_row=get_item_data_row($this->Mdl_items_categories->get_categories($item_category_id, 1, 1)->row(),$this);
		echo $data_row;
	}
	/**
	 * @author Kamaro Lambert
	 * @method to delete one category
	 * 
	 */
	function delete()
	{
		$categories_to_delete=$this->input->post('ids');
		
		if($this->Mdl_items_categories->delete_list($categories_to_delete))
		{
			
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_deleted').' '.
					count($categories_to_delete).' '.$this->lang->line('items_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_cannot_be_deleted')));
		}
	}
	/**
	 * @author Kamaro Lambert
	 * @method to search
	 */
	function search()
	{
		$search=$this->input->post('search');
		$data_rows=get_items_category_manage_table_data_rows($this->Mdl_items_categories->search($search),$this);
		echo $data_rows;
	}
	
	/*
	 Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Mdl_items_categories->get_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}
	/*
	 get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 360;
	}
}