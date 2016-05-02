<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * FusionInvoice
 * 
 * A free and open source web based invoicing system
 *
 * @package		FusionInvoice
 * @author		Jesse Terry
 * @copyright	Copyright (c) 2012 - 2013, Jesse Terry
 * @license		http://www.fusioninvoice.com/support/page/license-agreement
 * @link		http://www.fusioninvoice.com
 * 
 */
//
class Mdl_Invoices extends MY_Model {

    public $table               = 'sales_items_temp';
    public $primary_key         = '';
    public $date_modified_field = '';

    
    public function default_select($StartDAte=null,$EndDate=null,$sale_id=null)
	{
		//Added date filter for optimization
		if($StartDAte!=null and $EndDate!=NULL)
		{
			$period_condition="WHERE date(sale_time) BETWEEN '".$StartDAte."' AND '".$EndDate."'";
		}
		elseif($sale_id)
		{
			$sale_id_condition="WHERE sale_id=$sale_id";
		}
		else {
			$sale_id_condition='';
			$period_condition='';
		}		
		$this->db->query("DROP TABLE IF EXISTS ".$this->db->dbprefix('sales_items_temp'));
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('sales_items_temp')."
		(SELECT date(sale_time) as sale_date, ".$this->db->dbprefix('sales_items').".sale_id, comment,payment_type, customer_id, 
				CONCAT(last_name,' ',first_name) as Customer_names,employee_id, 
		".$this->db->dbprefix('items').".item_id, supplier_id, quantity_purchased, item_cost_price, item_unit_price, SUM(percent) as item_tax_percent,
		discount_percent, (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('sales_items').".line as line, serialnumber, ".$this->db->dbprefix('sales_items').".description as description,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(1+(SUM(percent)/100)),2) as total,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(percent)/100),2) as tax,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased) as profit
		FROM ".$this->db->dbprefix('sales_items')."
		INNER JOIN ".$this->db->dbprefix('sales')." ON  ".$this->db->dbprefix('sales_items').'.sale_id='.$this->db->dbprefix('sales').'.sale_id'."
		INNER JOIN ".$this->db->dbprefix('items')." ON  ".$this->db->dbprefix('sales_items').'.item_id='.$this->db->dbprefix('items').'.item_id'."
		LEFT OUTER JOIN ".$this->db->dbprefix('suppliers')." ON  ".$this->db->dbprefix('items').'.supplier_id='.$this->db->dbprefix('suppliers').'.person_id'."
		LEFT JOIN ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('people').".person_id=".$this->db->dbprefix('sales').".customer_id
		LEFT OUTER JOIN ".$this->db->dbprefix('sales_items_taxes')." ON  "
		.$this->db->dbprefix('sales_items').'.sale_id='.$this->db->dbprefix('sales_items_taxes').'.sale_id'." and "
		.$this->db->dbprefix('sales_items').'.item_id='.$this->db->dbprefix('sales_items_taxes').'.item_id'." and "
		.$this->db->dbprefix('sales_items').'.line='.$this->db->dbprefix('sales_items_taxes').'.line'."
		$period_condition	$sale_id_condition
		GROUP BY sale_id, item_id, line)");

		//Update null item_tax_percents to be 0 instead of null
		$this->db->where('item_tax_percent IS NULL');
		$this->db->update('sales_items_temp', array('item_tax_percent' => 0));

		//Update null tax to be 0 instead of null
		$this->db->where('tax IS NULL');
		$this->db->update('sales_items_temp', array('tax' => 0));

		//Update null subtotals to be equal to the total as these don't have tax
		$this->db->query('UPDATE '.$this->db->dbprefix('sales_items_temp'). ' SET total=subtotal WHERE total IS NULL');
	
		$this->db->select('SALE_date dateIssued, TO_DAYS(SALE_date)- TO_DAYS(curdate()) AS daysOverdue');
		$this->db->select('sale_id, customer_id, customer_names,  SUM( quantity_purchased ) AS number_of_items');
		$this->db->select('(SELECT sum(payment_amount) FROM '.$this->db->dbprefix('sales_payments').' WHERE '.$this->db->dbprefix('sales_payments').'.sale_id='.$this->db->dbprefix('sales_items_temp').'.sale_id and credit=0)
         as amount_paid');
		$this->db->select('SUM( subtotal ) AS subtotal, SUM( total ) total, SUM( tax ) tax');
		$this->db->where("quantity_purchased >",0);
		$this->db->group_by('SALE_date, sale_id, customer_id, customer_names');
		$this->db->order_by('dateIssued desc');
		$this->db->offset($offset);
		$this->db->limit($limit);
		
		return    $this->db->get('sales_items_temp');
    }

    public function default_order_by()
    {
        $this->db->order_by('fi_invoices.invoice_date_created DESC');
    }

    public function default_join()
    {
        $this->db->join('fi_clients', 'fi_clients.client_id = fi_invoices.client_id');
        $this->db->join('fi_users', 'fi_users.user_id = fi_invoices.user_id');
        $this->db->join('fi_invoice_amounts', 'fi_invoice_amounts.invoice_id = fi_invoices.invoice_id', 'left');
        $this->db->join('fi_client_custom', 'fi_client_custom.client_id = fi_clients.client_id', 'left');
        $this->db->join('fi_user_custom', 'fi_user_custom.user_id = fi_users.user_id', 'left');
        $this->db->join('fi_invoice_custom', 'fi_invoice_custom.invoice_id = fi_invoices.invoice_id', 'left');
    }

    public function validation_rules()
    {
        return array(
            'client_name'          => array(
                'field' => 'client_name',
                'label' => lang('client'),
                'rules' => 'required'
            ),
            'invoice_date_created' => array(
                'field' => 'invoice_date_created',
                'label' => lang('invoice_date'),
                'rules' => 'required'
            ),
            'invoice_group_id'     => array(
                'field' => 'invoice_group_id',
                'label' => lang('invoice_group'),
                'rules' => 'required'
            ),
            'user_id'              => array(
                'field' => 'user_id',
                'label' => lang('user'),
                'rule'  => 'required'
            )
        );
    }

    public function validation_rules_save_invoice()
    {
        return array(
            'invoice_number'       => array(
                'field' => 'invoice_number',
                'label' => lang('invoice_number'),
                'rules' => 'required|is_unique[fi_invoices.invoice_number' . (($this->id) ? '.invoice_id.' . $this->id : '') . ']'
            ),
            'invoice_date_created' => array(
                'field' => 'invoice_date_created',
                'label' => lang('date'),
                'rules' => 'required'
            ),
            'invoice_date_due'     => array(
                'field' => 'invoice_date_due',
                'label' => lang('due_date'),
                'rules' => 'required'
            )
        );
    }

    public function create($db_array = NULL)
    {
        $invoice_id = parent::save(NULL, $db_array);

        // Create an invoice amount record
        $db_array = array(
            'invoice_id' => $invoice_id
        );

        $this->db->insert('fi_invoice_amounts', $db_array);

        // Create the default invoice tax record if applicable
        if ($this->mdl_settings->setting('default_invoice_tax_rate'))
        {
            $db_array = array(
                'invoice_id'              => $invoice_id,
                'tax_rate_id'             => $this->mdl_settings->setting('default_invoice_tax_rate'),
                'include_item_tax'        => $this->mdl_settings->setting('default_include_item_tax'),
                'invoice_tax_rate_amount' => 0
            );

            $this->db->insert('fi_invoice_tax_rates', $db_array);
        }

        return $invoice_id;
    }

    public function get_url_key()
    {
        $this->load->helper('string');
        return random_string('unique');
    }

    /**
     * Copies invoice items, tax rates, etc from source to target
     * @param int $source_id
     * @param int $target_id
     */
    public function copy_invoice($source_id, $target_id)
    {
        $this->load->model('invoices/mdl_items');
        
        $invoice_items = $this->mdl_items->where('invoice_id', $source_id)->get()->result();

        foreach ($invoice_items as $invoice_item)
        {
            $db_array = array(
                'invoice_id'       => $target_id,
                'item_tax_rate_id' => $invoice_item->item_tax_rate_id,
                'item_name'        => $invoice_item->item_name,
                'item_description' => $invoice_item->item_description,
                'item_quantity'    => $invoice_item->item_quantity,
                'item_price'       => $invoice_item->item_price,
                'item_order'       => $invoice_item->item_order
            );

            $this->mdl_items->save($target_id, NULL, $db_array);
        }

        $invoice_tax_rates = $this->mdl_invoice_tax_rates->where('invoice_id', $source_id)->get()->result();

        foreach ($invoice_tax_rates as $invoice_tax_rate)
        {
            $db_array = array(
                'invoice_id'              => $target_id,
                'tax_rate_id'             => $invoice_tax_rate->tax_rate_id,
                'include_item_tax'        => $invoice_tax_rate->include_item_tax,
                'invoice_tax_rate_amount' => $invoice_tax_rate->invoice_tax_rate_amount
            );

            $this->mdl_invoice_tax_rates->save($target_id, NULL, $db_array);
        }
    }

    public function db_array()
    {
        $db_array = parent::db_array();

        // Get the client id for the submitted invoice
        $this->load->model('clients/mdl_clients');
        $db_array['client_id'] = $this->mdl_clients->client_lookup($db_array['client_name']);
        unset($db_array['client_name']);

        $db_array['invoice_date_created'] = date_to_mysql($db_array['invoice_date_created']);
        $db_array['invoice_date_due']     = $this->get_date_due($db_array['invoice_date_created']);
        $db_array['invoice_number']       = $this->get_invoice_number($db_array['invoice_group_id']);
        $db_array['invoice_terms']        = $this->mdl_settings->setting('default_invoice_terms');

        // Generate the unique url key
        $db_array['invoice_url_key'] = $this->get_url_key();

        return $db_array;
    }

    public function get_invoice_number($invoice_group_id)
    {
        $this->load->model('invoice_groups/mdl_invoice_groups');
        return $this->mdl_invoice_groups->generate_invoice_number($invoice_group_id);
    }

    public function get_date_due($invoice_date_created)
    {
        $invoice_date_due = new DateTime($invoice_date_created);
        $invoice_date_due->add(new DateInterval('P' . $this->mdl_settings->setting('invoices_due_after') . 'D'));
        return $invoice_date_due->format('Y-m-d');
    }

    public function delete($invoice_id)
    {
        parent::delete($invoice_id);

        $this->load->helper('orphan');
        delete_orphans();
    }

    public function is_open()
    {	
    	$this->default_select();
    	print_r($this->db->get($this->table));
    	exit;
    	$this->db->select('SALE_date dateIssued, TO_DAYS(SALE_date)- TO_DAYS(curdate()) AS daysOverdue');
    	$this->db->select('sale_id, customer_id, customer_names,  SUM( quantity_purchased ) AS number_of_items');
    	$this->db->select('(SELECT sum(payment_amount) FROM '.$this->db->dbprefix('sales_payments').' WHERE 
    			'.$this->db->dbprefix('sales_payments').'.sale_id='.$this->db->dbprefix('sales_items_temp').'.sale_id 
    			 and credit=0)
         as amount_paid');
    	$this->db->select('SUM( subtotal ) AS subtotal, SUM( total ) total, SUM( tax ) tax');
    	$this->db->where("quantity_purchased >",0);
    	 
    	$this->db->group_by('SALE_date, sale_id, customer_id, customer_names');
    	$this->db->order_by('dateIssued desc');
    	$this->db->offset($offset);
    	$this->db->limit($limit);
        // Optional function to retrieve invoices with balance
        return $this->db->get($this->table);
    }

    public function is_closed()
    {
    
    	$this->db->where('ROUND(amount_paid, 2) >= ROUND(subtotal, 2)');
    	
    	$this->db->select('SALE_date dateIssued, TO_DAYS(SALE_date)- TO_DAYS(curdate()) AS daysOverdue');
    	$this->db->select('sale_id, customer_id, customer_names,  SUM( quantity_purchased ) AS number_of_items');
    	$this->db->select('(SELECT sum(payment_amount) FROM '.$this->db->dbprefix('sales_payments').' WHERE '.$this->db->dbprefix('sales_payments').'.sale_id='.$this->db->dbprefix('sales_items_temp').'.sale_id and credit=0)
         as amount_paid');
    	$this->db->select('SUM( subtotal ) AS subtotal, SUM( total ) total, SUM( tax ) tax');
    	$this->db->where("quantity_purchased >",0);
    	$this->db->group_by('SALE_date, sale_id, customer_id, customer_names');
    	$this->db->order_by('dateIssued desc');
    	$this->db->offset($offset);
    	$this->db->limit($limit);
        // Optional function to retrieve invoices without balance
        return $this;
    }

    public function is_overdue()
    {
        // Optional function to retrieve overdue invoices
      
		$this->db->having("daysOverdue <= -$days_payment_due AND (ROUND(amount_paid, 2) < ROUND(subtotal, 2) OR amount_paid is null)", '', FALSE);
		
         $this->db->select('SALE_date dateIssued, TO_DAYS(SALE_date)- TO_DAYS(curdate()) AS daysOverdue');
         $this->db->select('sale_id, customer_id, customer_names,  SUM( quantity_purchased ) AS number_of_items');
         $this->db->select('(SELECT sum(payment_amount) FROM '.$this->db->dbprefix('sales_payments').' WHERE '.$this->db->dbprefix('sales_payments').'.sale_id='.$this->db->dbprefix('sales_items_temp').'.sale_id and credit=0)
         as amount_paid');
         $this->db->select('SUM( subtotal ) AS subtotal, SUM( total ) total, SUM( tax ) tax');
         $this->db->where("quantity_purchased >",0);
         $this->db->group_by('SALE_date, sale_id, customer_id, customer_names'); 
         $this->db->order_by('dateIssued desc');
 		 $this->db->offset($offset);
		 $this->db->limit($limit);
        return $this;
    }
  
    function get_invoices($status='open',$limit=20,$offset=0,$days_payment_due=31)
    {
    
    	$this->default_select();
      if ($status == 'overdue')
       {
    	  $this->db->WHERE('(TO_DAYS(SALE_date)- TO_DAYS(curdate())) <= -'.$days_payment_due.'AND
    			 ((SELECT sum(payment_amount) FROM '.$this->db->dbprefix('sales_payments').' WHERE 
    			'.$this->db->dbprefix('sales_payments').'.sale_id='.$this->db->dbprefix('sales_items_temp').'.sale_id and credit=0)
         < ROUND(subtotal, 2) OR amount_paid is null)');
    }
    elseif ($status == 'open')
    {
    	$this->db->WHERE("(ROUND(amount_paid, 2) < ROUND(subtotal, 2) or amount_paid is null)");
    }
    elseif ($status == 'closed')
    {
    		
    	$this->db->WHERE('ROUND(amount_paid, 2) >= ROUND(subtotal, 2)');
    }
    
    $this->db->select('SALE_date dateIssued, TO_DAYS(SALE_date)- TO_DAYS(curdate()) AS daysOverdue');
    $this->db->select('sale_id, customer_id, customer_names,  SUM( quantity_purchased ) AS number_of_items');
    $this->db->select('(SELECT sum(payment_amount) FROM '.$this->db->dbprefix('sales_payments').' WHERE '.$this->db->dbprefix('sales_payments').'.sale_id='.$this->db->dbprefix('sales_items_temp').'.sale_id and credit=0)
         as amount_paid');
    $this->db->select('SUM( subtotal ) AS subtotal, SUM( total ) total, SUM( tax ) tax');
    $this->db->where("quantity_purchased >",0);
    $this->db->group_by('SALE_date, sale_id, customer_id, customer_names');
    $this->db->order_by('dateIssued desc');
    $this->db->offset($offset);
    $this->db->limit($limit);
    
    return    $this->db->get('sales_items_temp');
    }
    
    public function by_client($client_id)
    {
        $this->filter_where('fi_invoices.client_id', $client_id);
        return $this;
    }

}

?>