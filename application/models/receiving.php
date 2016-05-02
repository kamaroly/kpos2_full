<?php
class Receiving extends CI_Model
{
	
   public function receiving_per_supplier($start_date, $end_date)
	{
		//Selecting the need columns
		$this->db->select("DATE(  `receiving_time` ) as receive_date,receiving_id,`tin`, `received_amount` , `tax_amount` ");
		$this->db->from('receivings');
		$this->db->join('people','receivings.supplier_id=person_id','LEFT');
		$this->db->WHERE("DATE(  `receiving_time` ) BETWEEN  '$start_date' AND  '$end_date'");
		//$this->db->WHERE("`received_amount` >",0);
		
		return $this->db->get();
	}
	
	public function get_info($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id',$receiving_id);
		return $this->db->get();
	}

	function exists($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id',$receiving_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

	function save ($items,$supplier_id,$employee_id,$comment,$payment_type,$receiving_id=false,$payments=null,$tax_amount=0,$received_amount=0)
	{
		if(count($items)==0)
			return -1;

		//Alain Multiple payments
		//Build payment types string
		$payment_types='';
		foreach($payments as $payment_id=>$payment)
		{
			$payment_types=$payment_types.$payment['payment_type'].': '.to_currency($payment['payment_amount']).'<br />';
		}
		
		$receivings_data = array(
		'supplier_id'=> $this->Supplier->exists($supplier_id) ? $supplier_id : null,
		'employee_id'=>$employee_id,
		'payment_type'=>$payment_types,
		'comment'=>$comment,
		'tax_amount'=>$tax_amount,
		'received_amount'=>$received_amount		
		);
		
	
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
       
		$this->db->insert('receivings',$receivings_data);
		$receiving_id = $this->db->insert_id(); //Get the sale ID of the recent performed transaction
        
		//Let's save the payments to the database 
		foreach($payments as $payment_id=>$payment)
		{
		  $receivings_payments_data = array
			(
					'receiving_id'=>$receiving_id,
					'payment_type'=>$payment['payment_type'],
					'payment_amount'=>$payment['payment_amount'],
			);
		  
	   //Check if there is a credit payment done
	    if ( substr( $payment['payment_type'], 0, strlen( $this->lang->line('sales_credit') ) ) == $this->lang->line('sales_credit') )
		  		
		   {
		  	$receivings_payments_data['credit']=1;
		  }	
			$this->db->insert('receivings_payments',$receivings_payments_data);
		}
		
        
		foreach($items as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

			$receivings_items_data = array
			(
				'receiving_id'=>$receiving_id,
				'item_id'=>$item['item_id'],
				'line'=>$item['line'],
				'description'=>$item['description'],
				'serialnumber'=>$item['serialnumber'],
				'quantity_purchased'=>$item['quantity'],
				'discount_percent'=>$item['discount'],
				'item_cost_price' => $cur_item_info->cost_price,
				'item_unit_price'=>$item['price']
			);

			$this->db->insert('receivings_items',$receivings_items_data);

			//Update stock quantity
			$item_data = array('quantity'=>$cur_item_info->quantity + $item['quantity']);
			$this->Item->save($item_data,$item['item_id']);
			
			$qty_recv = $item['quantity'];
			$recv_remarks ='RECV '.$receiving_id;
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$item['item_id'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$recv_remarks,
				'trans_inventory'=>$qty_recv
			);
			$this->Inventory->insert($inv_data);

			$supplier = $this->Supplier->get_info($supplier_id);
		}
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return -1;
		}

		return $receiving_id;
	}

	function get_receiving_items($receiving_id)
	{
		$this->db->from('receivings_items');
		$this->db->where('receiving_id',$receiving_id);
		return $this->db->get();
	}

	function get_supplier($receiving_id)
	{
		$this->db->from('receivings');
		$this->db->where('receiving_id',$receiving_id);
		return $this->Supplier->get_info($this->db->get()->row()->supplier_id);
	}
	
	//We create a temp table that allows us to do easy report/receiving queries
	public function create_receivings_items_temp_table()
	{
		$this->db->query("DROP TABLE IF EXISTS ".$this->db->dbprefix('receivings_items_temp'));
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('receivings_items_temp')."
		(SELECT date(receiving_time) as receiving_date, ".$this->db->dbprefix('receivings_items').".receiving_id,
		 comment,payment_type, employee_id, 
		".$this->db->dbprefix('items').".item_id, 
		".$this->db->dbprefix('receivings').".supplier_id,
		 quantity_purchased, 
		item_cost_price, item_unit_price,
		discount_percent, 
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('receivings_items').".line as line, 
		serialnumber, ".$this->db->dbprefix('receivings_items').".description as description,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100),2) as total,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100),2)+tax_amount as total_with_tax,
		tax_amount,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased) as profit
				
		FROM ".$this->db->dbprefix('receivings_items')."
		INNER JOIN ".$this->db->dbprefix('receivings')." ON  ".$this->db->dbprefix('receivings_items').'.receiving_id='.$this->db->dbprefix('receivings').'.receiving_id'."
		INNER JOIN ".$this->db->dbprefix('items')." ON  ".$this->db->dbprefix('receivings_items').'.item_id='.$this->db->dbprefix('items').'.item_id'."
		INNER JOIN ".$this->db->dbprefix('suppliers')." ON  ".$this->db->dbprefix('suppliers').'.person_id='.$this->db->dbprefix('receivings').'.supplier_id'."
	    WHERE  DATE(".$this->db->dbprefix('receivings').".receiving_time) BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."'
		GROUP BY receiving_id, item_id, line)");
	}


}
?>
