<?php
class Sale extends CI_Model
{
	public function get_info($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}
    
	public function get_info_for_guest($invoice_url_key)
	{
		
		$this->db->where('invoice_url_key',$invoice_url_key);
		return $this->db->get('sales');
	}
	
	function exists($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
	
	function update($sale_data, $sale_id)
	{
		$this->db->where('sale_id', $sale_id);
		$success = $this->db->update('sales',$sale_data);
		
		return $success;
	}
	/**
	 * @author Kamaro Lambert
	 * @name   save_currency_sale
	 * @param  numeric $total_sold
	 */
	function save_currency_sales($sale_id,$subtotal,$total_sold)
	{
		$currencies= Model\Currency_model::all();
		foreach($currencies as $currency)
		{
			$currency_ar=array('sale_id'        =>$sale_id,
					           'currency_id'    =>$currency->curr_id,
					           'subtotal_sold'  =>$subtotal/$currency->Exchange_Rate,
					           'total_sold'     =>$total_sold/$currency->Exchange_Rate,
					           'currency_rate'  =>$currency->Exchange_Rate,
					           );
			$this->db->insert('currency_sales',$currency_ar);
		}
	  return TRUE;
	}
	
	/**
	 * @author Kamaro Lambert
	 * @param  Integer $sale_id
	 * Descript to check if the sale exists
	 */
	function check_sale_exits($sale_id)
	{
	  $this->db->where('sale_id',$sale_id);
	  return $this->db->get('sales')->num_rows();
	  
	}
	
	
	function get_max_sale_id()
	{
		$this->db->select_max('sale_id');
		return $this->db->get('sales')->row()->sale_id+1;
	}
/**
 * @Author Kamaro Lambert
 * @param  array $items
 * @param  numeric $customer_id
 * @param  numeric $employee_id
 * @param  String $comment
 * @param  array() $payments
 * @param  numeric $sale_id
 * @param  numeric $total
 * @return number
 */	
 function save ($items,$customer_id,$employee_id,$comment,$payments,$sale_id=false,$total,$invoice_number=null,$date_issued=null,$po_number=null,$sdc_information=null)
	{

		if(count($items)==0)
		{
			return -1;
		}

		//Alain Multiple payments
		//Build payment types string
		$payment_types='';
		foreach($payments as $payment_id=>$payment)
		{
			 $payment_types=$payment_types.$payment['payment_type'].': '.to_currency($payment['payment_amount']).'<br />';
		}
        
		
		$sales_data = array(
			'sale_id'             => $sale_id,
			'sale_time'           => ($date_issued!=null)?$date_issued:date('Y-m-d H:i:s'),
			'customer_id'         => $this->Customer->exists($customer_id) ? $customer_id : null,
			'employee_id'         => $employee_id,
			'payment_type'        => $payment_types,
			'comment'             => $comment,
			'invoice_date_due'    => (isset($date_due)!=null)?$date_due:date('Y-m-d',strtotime(date('Ymd').' + 31 day')),
			'invoice_number'      => ($invoice_number!=null)?$invoice_number:'',
			'PO_NUMBER'           => ($po_number!=null)?$po_number:'',
			'invoice_url_key'     => $this->get_url_key(),
			'sdc_information'     => $sdc_information,
		);
       
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
       
		//If it's to update sale
		IF($sale_id!=false)
		{
			
			$this->db->where('sale_id', $sale_id);
			$this->db->update('sales', $sales_data);
			$sale_id = $sale_id;
			
			foreach($payments as $payment_id=>$payment)
			{
				if ( substr( $payment['payment_type'], 0, strlen( $this->lang->line('sales_giftcard') ) ) == $this->lang->line('sales_giftcard') )
				{
					/* We have a gift card and we have to deduct the used value from the total value of the card. */
					$splitpayment = explode( ':', $payment['payment_type'] );
					$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $splitpayment[1] );
					$this->Giftcard->update_giftcard_value( $splitpayment[1], $cur_giftcard_value - $payment['payment_amount'] );
				}
			
				$sales_payments_data = array
				(
						'sale_id'=>$sale_id,
						'payment_type'=>$payment['payment_type'],
						'payment_amount'=>$payment['payment_amount'],
						'payment_amount_foreign'=>$payment['payment_amount_foreign']
				);
				//Check if there is a credit payment done
				if ( substr( $payment['payment_type'], 0, strlen( $this->lang->line('sales_credit') ) ) == $this->lang->line('sales_credit') )
						
				{
					$sales_payments_data['credit']=1;
				}
				$this->db->where('sale_id', $sale_id);
				$this->db->update('sales_payments',$sales_payments_data);
			}
			
			foreach($items as $line=>$item)
			{
				$cur_item_info = $this->Item->get_info($item['item_id']);
			
				$sales_items_data = array
				(
						'sale_id'=>$sale_id,
						'item_id'=>$item['item_id'],
						'line'=>$item['line'],
						'description'=>$item['description'],
						'serialnumber'=>$item['serialnumber'],
						'quantity_purchased'=>$item['quantity'],
						'discount_percent'=>$item['discount'],
						'item_cost_price' => $cur_item_info->cost_price,
						'item_unit_price'=>$item['price']
				);
				$this->db->where(array('sale_id'=> $sale_id,
						               'item_id'=> $sales_items_data['item_id'],
						               'line'   => $sales_items_data['line']));
				$this->db->update('sales_items',$sales_items_data);
			
				//Update stock quantity
				$item_data = array('quantity'=>$cur_item_info->quantity - $item['quantity']);
				$this->Item->save($item_data,$item['item_id']);
					
				//Ramel Inventory Tracking
				//Inventory Count Details
				$qty_buy = -$item['quantity'];
				$sale_remarks ='POS '.$sale_id;
				$inv_data = array
				(
						'trans_date'=>date('Y-m-d H:i:s'),
						'trans_items'=>$item['item_id'],
						'trans_user'=>$employee_id,
						'trans_comment'=>$sale_remarks,
						'trans_inventory'=>$qty_buy
				);
				$this->Inventory->insert($inv_data);
				//------------------------------------Ramel
			
				$customer = $this->Customer->get_info($customer_id);
				if ($customer_id == -1 or $customer->taxable)
				{
					foreach($this->Item_taxes->get_info($item['item_id']) as $row)
					{
						$this->db->where('sale_id', $sale_id);
						$this->db->update('sales_items_taxes', array(
								'sale_id' 	=>$sale_id,
								'item_id' 	=>$item['item_id'],
								'line'      =>$item['line'],
								'name'		=>$row['name'],
								'percent' 	=>$row['percent']
						));
					}
				}
			}
		}
	
		else 
		{

			//It's a new saled
		$this->db->insert('sales',$sales_data);
		
		$sale_id = $this->db->insert_id();

		foreach($payments as $payment_id=>$payment)
		{
			if ( substr( $payment['payment_type'], 0, strlen( $this->lang->line('sales_giftcard') ) ) == $this->lang->line('sales_giftcard') )
			{
				/* We have a gift card and we have to deduct the used value from the total value of the card. */
				$splitpayment = explode( ':', $payment['payment_type'] );
				$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $splitpayment[1] );
				$this->Giftcard->update_giftcard_value( $splitpayment[1], $cur_giftcard_value - $payment['payment_amount'] );
			}

			$sales_payments_data = array
			(
				'sale_id'=>$sale_id,
				'payment_type'=>$payment['payment_type'],
				'payment_amount'=>$payment['payment_amount']?$payment['payment_amount']:0,
				'payment_amount_foreign'=>$payment['payment_amount_foreign']?$payment['payment_amount_foreign']:0
			);
			//Check if there is a credit payment done
			if ( substr( $payment['payment_type'], 0, strlen( $this->lang->line('sales_credit') ) ) == $this->lang->line('sales_credit') )
					
			{
				$sales_payments_data['credit']=1;
			}
			$this->db->insert('sales_payments',$sales_payments_data);
		}

		foreach($items as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

			$sales_items_data = array
			(
				'sale_id'=>$sale_id,
				'item_id'=>$item['item_id'],
				'line'=>$item['line'],
				'description'=>$item['description'],
				'serialnumber'=>$item['serialnumber'],
				'quantity_purchased'=>$item['quantity'],
				'discount_percent'=>$item['discount'],
				'item_cost_price' => $cur_item_info->cost_price,
				'item_unit_price'=>$item['price']
			);

			$this->db->insert('sales_items',$sales_items_data);

			//Update stock quantity
			$item_data = array('quantity'=>$cur_item_info->quantity - $item['quantity']);
			$this->Item->save($item_data,$item['item_id']);
			
			//Ramel Inventory Tracking
			//Inventory Count Details
			$qty_buy = -$item['quantity'];
			$sale_remarks ='POS '.$sale_id;
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$item['item_id'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>$qty_buy
			);
			$this->Inventory->insert($inv_data);
			//------------------------------------Ramel

			$customer = $this->Customer->get_info($customer_id);
 			if ($customer_id == -1 or $customer->taxable)
 			{
				foreach($this->Item_taxes->get_info($item['item_id']) as $row)
				{
					$this->db->insert('sales_items_taxes', array(
						'sale_id' 	=>$sale_id,
						'item_id' 	=>$item['item_id'],
						'line'      =>$item['line'],
						'name'		=>$row['name'],
						'percent' 	=>$row['percent']
					));
				}
			}
		  }
		}
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			var_dump($items);
		exit;
			return -1;
		}
	  
		return $sale_id;
	}
	
	function delete($sale_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		$this->db->delete('sales_payments', array('sale_id' => $sale_id)); 
		$this->db->delete('sales_items_taxes', array('sale_id' => $sale_id)); 
		$this->db->delete('sales_items', array('sale_id' => $sale_id)); 
		$this->db->delete('sales', array('sale_id' => $sale_id)); 
		
		$this->db->trans_complete();
				
		return $this->db->trans_status();
	}

	function get_sale_items($sale_id)
	{
		$this->db->from('sales_items');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}

	function get_sale_payments($sale_id)
	{
		$this->db->from('sales_payments');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}

	function get_customer($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->Customer->get_info($this->db->get()->row()->customer_id);
	}

	/**
	 * @author Kamaro Lambert
	 * @name   create_sales_items_temp_table()
	 * @method We create a temp table that allows us to do easy report/sales queries 
	 */
	
	public function create_sales_items_temp_table($StartDAte=null,$EndDate=null,$sale_id=null)
	{
		//Added date filter for optimization
		if($StartDAte!=null and $EndDate!=NULL)
		{
			$period_condition=" AND date(sale_time) BETWEEN '".$StartDAte."' AND '".$EndDate."'";
		}
		elseif ($this->uri->segment(3)!='' AND $this->uri->segment(4)!='')
		{
			$period_condition=" AND date(sale_time) BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."'";
			
		}
		else
		{
          $period_condition='';
		}
		if($sale_id)
		{
			$sale_id_condition=" AND sale_id=$sale_id";
		}
		else
		 {
			$sale_id_condition='';
		}
			
		$this->db->query("DROP TABLE IF EXISTS ".$this->db->dbprefix('sales_items_temp'));
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('sales_items_temp')."
		(SELECT date(sale_time) as sale_date, ".$this->db->dbprefix('sales_items').".sale_id, comment,payment_type, customer_id, 
				CONCAT(last_name,' ',first_name) as Customer_names,employee_id, invoice_url_key,invoice_number,
		".$this->db->dbprefix('items').".item_id, supplier_id, quantity_purchased, item_cost_price, item_unit_price, SUM(percent) as item_tax_percent,
		discount_percent, (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('sales_items').".line as line, serialnumber, ".$this->db->dbprefix('sales_items').".description as description,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(1+(SUM(percent)/100)),2) as total,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(percent)/100),2) as tax,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased) as profit
		FROM ".$this->db->dbprefix('sales_items')."
		INNER JOIN ".$this->db->dbprefix('sales')." ON  ".$this->db->dbprefix('sales_items').'.sale_id='.$this->db->dbprefix('sales').'.sale_id'."
		$period_condition 	$sale_id_condition	
		INNER JOIN ".$this->db->dbprefix('items')." ON  ".$this->db->dbprefix('sales_items').'.item_id='.$this->db->dbprefix('items').'.item_id'."
		LEFT OUTER JOIN ".$this->db->dbprefix('suppliers')." ON  ".$this->db->dbprefix('items').'.supplier_id='.$this->db->dbprefix('suppliers').'.person_id'."
		LEFT JOIN ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('people').".person_id=".$this->db->dbprefix('sales').".customer_id
		LEFT OUTER JOIN ".$this->db->dbprefix('sales_items_taxes')." ON  "
		.$this->db->dbprefix('sales_items').'.sale_id='.$this->db->dbprefix('sales_items_taxes').'.sale_id'." and "
		.$this->db->dbprefix('sales_items').'.item_id='.$this->db->dbprefix('sales_items_taxes').'.item_id'." and "
		.$this->db->dbprefix('sales_items').'.line='.$this->db->dbprefix('sales_items_taxes').'.line'."
			
		GROUP BY sale_id, item_id, line)");

		//Update null item_tax_percents to be 0 instead of null
		$this->db->where('item_tax_percent IS NULL');
		$this->db->update('sales_items_temp', array('item_tax_percent' => 0));

		//Update null tax to be 0 instead of null
		$this->db->where('tax IS NULL');
		$this->db->update('sales_items_temp', array('tax' => 0));

		//Update null subtotals to be equal to the total as these don't have tax
		$this->db->query('UPDATE '.$this->db->dbprefix('sales_items_temp'). ' SET total=subtotal WHERE total IS NULL');
	}
	
	public function get_giftcard_value( $giftcardNumber )
	{
		if ( !$this->Giftcard->exists( $this->Giftcard->get_giftcard_id($giftcardNumber)))
			return 0;
		
		$this->db->from('giftcards');
		$this->db->where('giftcard_number',$giftcardNumber);
		return $this->db->get()->row()->value;
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to get the unique url key of the invoice
	 */
	public function get_url_key()
	{
		$this->load->helper('string');
		return random_string('unique');
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to select the max sale_id in the database
	 */
	function get_last_sale_id()
	{
	return	$this->db->select_max('sale_id')
		         ->get('sales')
		         ->row()
		         ->sale_id;
	}

	function set_receipt_copied($sale_id)
	{

		       $this->db->where('sale_id', $sale_id);
		return $this->db->update('sales',array('copied' => TRUE,
		                                       'copied_date' => DATE('Y-m-d h:i:s'),));
	}

	function was_copied($sale_id)
	{
    return	$this->db->select('copied,copied_date')
  		         ->where('sale_id', $sale_id)
		         ->get('sales')
		         ->row();
	}
}
?>
