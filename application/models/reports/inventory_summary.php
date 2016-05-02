<?php
require_once("report.php");
class Inventory_summary extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_item_name'), $this->lang->line('reports_item_number'),
		             $this->lang->line('reports_count'), $this->lang->line('reports_reorder_level'),
				     $this->lang->line('reports_unit_price'), $this->lang->line('reports_total_unit_price'),
				     $this->lang->line('reports_cost_price'),$this->lang->line('reports_total_remaining_price'),'tax(%)',);
		}
	
	public function getData(array $inputs)
	{
		$this->db->select('a.name, a.item_number, a.quantity, a.reorder_level,a.unit_price,a.cost_price,b.percent as item_tax');
		$this->db->from('items a');
		$this->db->join('items_taxes b','a.item_id=b.item_id','LEFT');
		$this->db->where('a.deleted', 0);	
		$this->db->order_by('a.name');
		
		return $this->db->get()->result_array();

	}
	
	public function getSummaryData(array $inputs)
	{
		return array();
	}
}
?>