<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* This file is part of Kpos, a PHP based Point of sale system built for
* SME.
*
* Copyright (c) 2013 Kamaro Lambert.
* http://github.com/kamaroly/kpos
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
* @package application/libraries
* @author FireSale <support@kamaroly.com>
* @copyright 2013 kamaroly.
* @version dev
* @link http://github.com/kamaroly/kpos
*
*/

class Sale_lib
{
	//initiating the CodeIgniter instace
	//---------------------------------
	var $CI;
    
  	function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * @Author Kamaro Lambert
	 * @method to get the current cart
	 * @example sale_lib->get_Cart()
	 */

	function get_cart()
	{
		if(!$this->CI->session->userdata('cart'))
			$this->set_cart(array());

		return $this->CI->session->userdata('cart');
	}

	/**
	 * @Author Kamaro Lambert
	 * @method to set data ino the cart
	 * @param array $cart_data
	 * @example sale_lib->set_cart(array());
	 */
	function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('cart',$cart_data);
	}

	/**
	 * @Author Kamaro Lambert
	 * @method to get current payments and it's able to handle multiple payments
	 * @example sale_lib->get_payments
	 */
	function get_payments()
	{
		if( !$this->CI->session->userdata( 'payments' ) )
			$this->set_payments( array( ) );

		return $this->CI->session->userdata('payments');
	}
	
	/**
	 * @Author Kamaro Lambert
	 * @method to set payments in the current cart session and that has ability to set multiple payments through the array
	 * @example sale_lib->set_payment()
	 */

	function set_payments($payments_data)
	{
		$this->CI->session->set_userdata('payments',$payments_data);
	}
	/**
	 * @Author Kamaro Lambert
	 * @method to get the comment for the current cart session
	 * @example sale_lib->get_comment()
	 */
	function get_comment() 
	{
		return $this->CI->session->userdata('comment');
	}
	function get_invoice_url_key()
	{
		return $this->CI->session->userdata('invoice_url_key');
	}
    /**
     * @Author Kamaro Lambert
     * @method to set the purchase order number
     * @param varchar $purchase_order_number
     * @example sale_lib->set_po_number
     */
	function set_po_number($purchase_order_number)
	{
		$this->CI->session->set_userdata('po_number',$purchase_order_number);
	}
	/**
	 * @author Kamaro Lambert
	 * @method to get the current PO number which is in the session
	 * @example sale_lib->get_po_number();
	 */
	function get_po_number()
	{
		return $this->CI->session->userdata('po_number');
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to cleaer the po_numbe
	 * @example sale_lib->clear_po_number();
	 */
	function clear_po_number()
	{
		$this->CI->session->unset_userdata('po_number');
	}
	
	/**
	 * @Author Kamaro Lambert
	 * @method to set the invoice number 
	 * @param varchar $invoice_number
	 * @example sale_lib->set_invoice_number
	 */
	function set_invoice_number($invoice_number)
	{
		$this->CI->session->set_userdata('invoice_number',$invoice_number);
	}
	/**
	 * @author Kamaro Lambert
	 * @method to get the current invoice number 
	 *  @example sale_lib->get_invoice_number();
	 */
	function get_invoice_number()
	{
		return $this->CI->session->userdata('invoice_number');
	}
	/**
	 * @author Kamaro Lambert
	 * @method to cleaer the invoice number
	 * @example sale_lib->clear_po_number();
	 */
	function clear_invoice_number()
	{
		$this->CI->session->unset_userdata('invoice_number');
	}
	
	/**
	 * @Author Kamaro Lambert
	 * @method to set the date an invoice is issued on
	 * @param varchar $date_issued
	 * @example sale_lib->set_date_issued($date)
	 */
	function set_date_issued($date_issued)
	{
		$this->CI->session->set_userdata('date_issued',$date_issued);
	}
	/**
	 * @author Kamaro Lambert
	 * @method to get the current session invoice issued date
	 * @example sale_lib->get_date_issued();
	 */
	function get_date_issued()
	{
		return $this->CI->session->userdata('date_issued');
	}
	/**
	 * @author Kamaro Lambert
	 * @method to cleaer the session invoice date
	 * @example sale_lib->clear_date_issued()
	 */
	function clear_date_issued()
	{
		$this->CI->session->unset_userdata('date_issued');
	}
	
		
	/**
	 * @Author Kamaro Lambert
	 * @method to set the comment
	 * @param string $comment
	 */
	function set_comment($comment) 
	{
		$this->CI->session->set_userdata('comment', $comment);
	}
    /**
     * @Author Kamaro Lambert
     * @method to clear the comment which is currently in the session
     */
	function clear_comment() 	
	{
		$this->CI->session->unset_userdata('comment');
	}
	/**
	 * @Author Kamaro Lambert
	 * @method to get email receipt which has been set in the current session
	 * @example sale_lib->get_email_receipt()
	 */
	function get_email_receipt() 
	{
		return $this->CI->session->userdata('email_receipt');
	}
    
	/**
	 * @Author Kamaro Lambert
	 * @method to set the email receipt in the session as saling is happening
	 * @param string $email_receipt
	 */
	function set_email_receipt($email_receipt) 
	{
		$this->CI->session->set_userdata('email_receipt', $email_receipt);
	}

	function clear_email_receipt() 	
	{
		$this->CI->session->unset_userdata('email_receipt');
	}
	/**
	 * @Author Kamaro Lambert
	 * @method to get category items
	 */
	function get_category_items()
	{
		return $this->CI->session->userdata('category_items');
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to set category items
	 * @param array $category_items
	 */
	function set_category_items($category_items)
	{
		
		$this->CI->session->set_userdata('category_items', $category_items);
		$this->clear_categories();
	}
	
	/**
	 * @author Kamaro Lambert
	 * @Method to clear category_items
	 */
	function clear_category_items()
	{
		$this->CI->session->unset_userdata('category_items');
	}
	
	/**
	 * @Author Kamaro Lambert
	 * @method to get category items
	 */
	function get_categories()
	{
		return $this->CI->session->userdata('categories');
	}
	
	/**
	 * @Author Kamaro Lambert
	 * @method to set category items
	 * @param array $category_items
	 */
	function set_categories($categories)
	{
	
		$this->CI->session->set_userdata('categories', $categories);
		$this->clear_category_items();
	}
	
	/**
	 * Method to clear category_items
	 */
	function clear_categories()
	{
		$this->CI->session->unset_userdata('categories');
	}
 
	/**
	 * @author Kamaro Lambert
	 * @name   convert_currency
	 * @param  $to_convert_amount AMOUNT TO BE CONVERTED
	 * @param default $local_currency_rate LOCAL CURRENCY RATE FOR THE ENTERED AMOUNT
	 * @param numeric $to_convert_rate CONVERTED AMOUNT TO RETURN
	 */
	function convert_currency($to_convert_amount,$local_currency_rate,$to_convert_rate)
	{
		return ($to_convert_amount*$local_currency)/$to_convert_rate;
	}
	/**
	 * @Author Kamaro Lambert
	 * @param integer $payment_id
	 * @param numeric $payment_amount
	 * @param numeric $accid
	 * @return boolean
	 */
	function add_payment($payment_id,$payment_amount,$accid=null,$default_currency=null)
	{
		$payments=$this->get_payments();
		
		//get current currency
		$current_currency=$this->get_currency();
		
		$current_payment_currency_info= Model\Currency_model::find($current_currency);
		//check if client is paying in foreign or local currency
		if($this->get_currency()==$default_currency)
		{   // Client is  paying in local money
			$payment_amount_foreign=$payment_amount/$current_payment_currency_info->Exchange_Rate;
		}
		
		if($this->get_currency()!=$default_currency)
		{  // Client is  paying in foreign money
			$payment_amount_foreign=$payment_amount;
			$payment_amount=$payment_amount*$current_payment_currency_info->Exchange_Rate;
		}
		$payment = array($payment_id=>
		array(
			'payment_type'          =>$payment_id,
			'payment_amount'        =>$payment_amount,
			'payment_amount_foreign'=>$payment_amount_foreign,
			'accid'                 =>$accid
			)
		);

		//payment_method already exists, add to payment_amount
		if(isset($payments[$payment_id]))
		{
			$payments[$payment_id]['payment_amount']+=$payment_amount;
		}
		else
		{
			//add to existing array
			$payments+=$payment;
		}

		$this->set_payments($payments);
		return true;

	}

	//Alain Multiple Payments
	function edit_payment($payment_id,$payment_amount)
	{
		$payments = $this->get_payments();
		if(isset($payments[$payment_id]))
		{
			$payments[$payment_id]['payment_type'] = $payment_id;
			$payments[$payment_id]['payment_amount'] = $payment_amount;
			$this->set_payments($payment_id);
		}

		return false;
	}

	//Alain Multiple Payments
	function delete_payment( $payment_id )
	{
		$payments = $this->get_payments();
		unset( $payments[urldecode( $payment_id )] );
		$this->set_payments( $payments );
	}

	//Alain Multiple Payments
	function empty_payments()
	{
		$this->CI->session->unset_userdata('payments');
	}

	//Alain Multiple Payments
	function get_payments_total()
	{
		$subtotal = 0;
		foreach($this->get_payments() as $payments)
		{
		    $subtotal+=$payments['payment_amount'];
		}
		return to_currency_no_money($subtotal);
	}

	//Alain Multiple Payments
	function get_amount_due()
	{
		$amount_due=0;
		$payment_total = $this->get_payments_total();
		$sales_total=$this->get_total();
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_customer()
	{
		if(!$this->CI->session->userdata('customer'))
		{
			$this->set_customer(-1);
		}
		return $this->CI->session->userdata('customer');
	}

	function set_customer($customer_id)
	{
		$this->CI->session->set_userdata('customer',$customer_id);
	}
	
	function set_sale_id($sale_id)
	{
		$this->CI->session->set_userdata('sale_id',$sale_id);
	}
	
	function get_sale_id()
	{
		return $this->CI->session->userdata('sale_id');
	}
	
	function clear_sale_id()
	{
		return $this->CI->session->unset_userdata('sale_id');
	}
	
	function get_mode()
	{
		if(!$this->CI->session->userdata('sale_mode'))
		{
			$this->set_mode('sale');
		}
		return $this->CI->session->userdata('sale_mode');
	}
	
	function get_currency()
	{
		if(!$this->CI->session->userdata('sale_currency'))
		{
			$dafault_currencies= Model\Currency_model::limit(1,0)->find_by_Default(1);
			foreach ($dafault_currencies as $def_currency )
			{
				$this->set_currency($def_currency->curr_id);
				break;
			}
			
		}
		return $this->CI->session->userdata('sale_currency');
	}
	
	function get_type()
	{
		if(!$this->CI->session->userdata('sale_type'))
			$this->set_type('normal');
	
		return $this->CI->session->userdata('sale_type');
	}

	function set_mode($mode)
	{
		$this->CI->session->set_userdata('sale_mode',$mode);
	}
	
	function set_currency($currency)
	{
		$this->CI->session->set_userdata('sale_currency',$currency);
	}
	
	
	function  set_invoice_url_key($invoice_url_key)
	{
	$this->CI->session->set_userdata('invoice_url_key',$invoice_url_key);
	}
	
	function set_type($type)
	{
		$this->CI->session->set_userdata('sale_type',$type);
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to add item to the cart
	 * @param numeric $item_id
	 * @param numeric $quantity
	 * @param numeric  $discount
	 * @param numeric $price
	 * @param text $description
	 * @param alpha-numeric $serialnumber
	 * @return boolean
	 */
	function add_item($item_id,$quantity=1,$discount=0,$price=null,$description=null,$serialnumber=null)
	{
		//make sure item exists
		if(!$this->CI->Item->exists($item_id))
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}


		//Alain Serialization and Description

		//Get all items in the cart so far...
		$items = $this->get_cart();

        //We need to loop through all items in the cart.
        //If the item is already there, get it's key($updatekey).
        //We also need to get the next key that we are going to use in case we need to add the
        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

        $maxkey=0;                       //Highest key so far
        $itemalreadyinsale=FALSE;        //We did not find the item yet.
		$insertkey=0;                    //Key to use for new entry.
		$updatekey=0;                    //Key to use to update(quantity)

		foreach ($items as $item)
		{
            //We primed the loop so maxkey is 0 the first time.
            //Also, we have stored the key in the element itself so we can compare.

			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			}
            
			//Let's check if the item is already in the cart
			if($item['item_id']==$item_id)
			{
				//Item in the cart now let's update
				$itemalreadyinsale=TRUE;
				$updatekey=$item['line'];
			}
		}

		$insertkey=$maxkey+1;

		//array/cart records are identified by $insertkey and item_id is just another field.
		if($this->get_type()=='normal')//check if its the normal sale
		{
		$item = array(($insertkey)=>
		array(
			'item_id'=>$item_id,
			'line'=>$insertkey,
			'name'=>$this->CI->Item->get_info($item_id)->name,
			'vat_type'=>$this->CI->Item->get_info($item_id)->vat_type,
			'item_number'=>$this->CI->Item->get_info($item_id)->item_number,
			'description'=>$description!=null ? $description: $this->CI->Item->get_info($item_id)->description,
			'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
			'allow_alt_description'=>$this->CI->Item->get_info($item_id)->allow_alt_description,
			'is_serialized'=>$this->CI->Item->get_info($item_id)->is_serialized,
			'quantity'=>$quantity,
            'discount'=>$discount,
			'acc_id'=>$this->CI->Item->get_info($item_id)->acc_id,
			'price'=>$price!=null ? $price: $this->CI->Item->get_info($item_id)->unit_price
			)
		);
		}
	    elseif($this->get_type()=='grossary')//check if its the user has selected glossary sale
		{
		$item = array(($insertkey)=>
		array(
			'item_id'=>$item_id,
			'line'=>$insertkey,
			'name'=>$this->CI->Item->get_info($item_id)->name,
			'vat_type'=>$this->CI->Item->get_info($item_id)->vat_type,
			'item_number'=>$this->CI->Item->get_info($item_id)->item_number,
			'description'=>$description!=null ? $description: $this->CI->Item->get_info($item_id)->description,
			'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
			'allow_alt_description'=>$this->CI->Item->get_info($item_id)->allow_alt_description,
			'is_serialized'=>$this->CI->Item->get_info($item_id)->is_serialized,
			'quantity'=>$quantity,
            'discount'=>$discount,
		    'acc_id'=>$this->CI->Item->get_info($item_id)->acc_id,
			'price'=>$price!=null ? $price: $this->CI->Item->get_info($item_id)->grossary_price
			)
		);
		}
		elseif($this->get_type()=='interprener')//check if its the user has selected interprener sale
		{
			$item = array(($insertkey)=>
					array(
							'item_id'=>$item_id,
							'line'=>$insertkey,
							'name'=>$this->CI->Item->get_info($item_id)->name,
							'vat_type'=>$this->CI->Item->get_info($item_id)->vat_type,
							'item_number'=>$this->CI->Item->get_info($item_id)->item_number,
							'description'=>$description!=null ? $description: $this->CI->Item->get_info($item_id)->description,
							'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
							'allow_alt_description'=>$this->CI->Item->get_info($item_id)->allow_alt_description,
							'is_serialized'=>$this->CI->Item->get_info($item_id)->is_serialized,
							'quantity'=>$quantity,
							'discount'=>$discount,
							'acc_id'=>$this->CI->Item->get_info($item_id)->acc_id,
							'price'=>$price!=null ? $price: $this->CI->Item->get_info($item_id)->interprener_price
					)
			);
		}
		//Item already exists and is not serialized, add to quantity
		if($itemalreadyinsale && ($this->CI->Item->get_info($item_id)->is_serialized ==0) )
		{
			$items[$updatekey]['quantity']+=$quantity;
		}
		else
		{
			//add to existing array
			$items+=$item;
		}

		$this->set_cart($items);
		return true;

	}
	
	function out_of_stock($item_id)
	{
		//make sure item exists
		if(!$this->CI->Item->exists($item_id))
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		
		$item = $this->CI->Item->get_info($item_id);
		$quanity_added = $this->get_quantity_already_added($item_id);
		
		if ($item->quantity - $quanity_added < 0)
		{
			return true;
		}
		
		return false;
	}
	/**
	 * @author Kamaro Lambert
	 * @method to get the quantity which was already added to the item
	 */
	function get_quantity_already_added($item_id)
	{
		$items = $this->get_cart();
		$quanity_already_added = 0;
		foreach ($items as $item)
		{
			if($item['item_id']==$item_id)
			{
				$quanity_already_added+=$item['quantity'];
			}
		}
		
		return $quanity_already_added;
	}
	/**
	 * @author Kamaro Lambert
	 * @param numeric $line_to_get
	 * @return |number
	 */
	function get_item_id($line_to_get)
	{
		$items = $this->get_cart();

		foreach ($items as $line=>$item)
		{
			if($line==$line_to_get)
			{
				return $item['item_id'];
			}
		}
		
		return -1;
	}

	function edit_item($line,$description,$serialnumber,$quantity,$discount,$price)
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			$items[$line]['description'] = $description;
			$items[$line]['serialnumber'] = $serialnumber;
			$items[$line]['quantity'] = $quantity;
			$items[$line]['discount'] = $discount;
			$items[$line]['price'] = $price;
			$this->set_cart($items);
		}

		return false;
	}

	function is_valid_receipt($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);

		if(count($pieces)==2)
		{
			return $this->CI->Sale->exists($pieces[1]);
		}

		return false;
	}
	
	function is_valid_item_kit($item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2)
		{
			return $this->CI->Item_kit->exists($pieces[1]);
		}

		return false;
	}

	function return_entire_sale($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);
		$sale_id = $pieces[1];

		$this->empty_cart();
		$this->remove_customer();

		foreach($this->CI->Sale->get_sale_items($sale_id)->result() as $row)
		{
			$this->add_item($row->item_id,-$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
		}
		$this->set_customer($this->CI->Sale->get_customer($sale_id)->person_id);
	}
	
	function add_item_kit($external_item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$external_item_kit_id);
		$item_kit_id = $pieces[1];
		
		foreach ($this->CI->Item_kit_items->get_info($item_kit_id) as $item_kit_item)
		{
			$this->add_item($item_kit_item['item_id'], $item_kit_item['quantity']);
		}
	}

	function copy_entire_sale($sale_id)
	{
		$this->empty_cart();
		$this->remove_customer();

		foreach($this->CI->Sale->get_sale_items($sale_id)->result() as $row)
		{
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
            
			//Check if it's a return
		    if($row->quantity_purchased < 0)
		    {
		    	$this->set_mode(strtolower($this->CI->lang->line('sales_return')));
		    }
		}
		foreach($this->CI->Sale->get_sale_payments($sale_id)->result() as $row)
		{
			$this->add_payment($row->payment_type,$row->payment_amount);
		}
		$this->set_customer($this->CI->Sale->get_customer($sale_id)->person_id);
		$this->set_po_number($this->CI->Sale->get_info($sale_id)->row()->po_number);
		$this->set_date_issued($this->CI->Sale->get_info($sale_id)->row()->sale_time);
		
		$this->set_invoice_url_key($this->CI->Sale->get_info($sale_id)->row()->invoice_url_key);
		$this->set_sale_id($sale_id);
        
	}
	
	function copy_entire_suspended_sale($sale_id)
	{
		$this->empty_cart();
		$this->remove_customer();

		foreach($this->CI->Sale_suspended->get_sale_items($sale_id)->result() as $row)
		{
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
		}
		foreach($this->CI->Sale_suspended->get_sale_payments($sale_id)->result() as $row)
		{
			$this->add_payment($row->payment_type,$row->payment_amount);
		}
		$this->set_customer($this->CI->Sale_suspended->get_customer($sale_id)->person_id);
		$this->set_comment($this->CI->Sale_suspended->get_comment($sale_id));
		$this->set_sale_id($sale_id);
	}

	function copy_entire_quotation($quotation_id)
	{
		$this->empty_cart();
		$this->remove_customer();
	   
		foreach($this->CI->Sale_quotations->get_sale_items($quotation_id)->result() as $row)
		{
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
		}
		foreach($this->CI->Sale_quotations->get_sale_payments($quotation_id)->result() as $row)
		{
			$this->add_payment($row->payment_type,$row->payment_amount);
		}
		$this->set_customer($this->CI->Sale_quotations->get_customer($quotation_id)->person_id);
		$this->set_comment($this->CI->Sale_quotations->get_comment($quotation_id));
		
		
		$this->set_po_number($this->CI->Sale_quotations->get_info($quotation_id)->row()->po_number);
		$this->set_date_issued($this->CI->Sale_quotations->get_info($quotation_id)->row()->sale_time);
		
		$this->set_invoice_url_key($this->CI->Sale_quotations->get_info($quotation_id)->row()->invoice_url_key);
		$this->set_sale_id($sale_id);
		
		//set_po_number
	}
	
	function delete_item($line)
	{
		$items=$this->get_cart();
		unset($items[$line]);
		$this->set_cart($items);
	}

	function empty_cart()
	{
		$this->CI->session->unset_userdata('cart');
	}

	function remove_customer()
	{
		$this->CI->session->unset_userdata('customer');
	}

	function clear_mode()
	{
		$this->CI->session->unset_userdata('sale_mode');
	}
	function clear_currency()
	{
		$this->CI->session->unset_userdata('sale_currency');
	}

	function clear_invoice_url_key()
	{
		$this->CI->session->unset_userdata('invoice_url_key');
	}
	/**
	 * @Author Kamaro Lambert
	 * @method to clear all session for the current cart.
	 * @example sale_lib->clear_all();
	 */
	function clear_all()
	{
		$this->clear_mode();
		$this->clear_currency();
		$this->empty_cart();
		$this->clear_comment();
		$this->clear_email_receipt();
		$this->clear_category_items();
		$this->empty_payments();
		$this->remove_customer();
		//this will be userfull if the user is using the invoice module to create invoice
		//-----------------------------------------------------------------------------
		$this->clear_invoice_number();
		$this->clear_po_number();
		$this->clear_date_issued();
		$this->clear_invoice_url_key();
		
		$this->clear_sale_id();
	}

	/**
	 * @Author Kamaro Lambert
	 * @method to get current taxes
	 * @return multitype:|multitype:number
	 */
	function get_taxes()
	{
		$customer_id = $this->get_customer();
		$customer = $this->CI->Customer->get_info($customer_id);

		//Do not charge sales tax if we have a customer that is not taxable
		if (!$customer->taxable and $customer_id!=-1)
		{
		   return array();
		}

		$taxes = array();
		
		
		
		foreach($this->get_cart() as $line=>$item)
		{
			$tax_info = $this->CI->Item_taxes->get_info($item['item_id']);

		
			foreach($tax_info as $tax)
			{
				$name = $tax['percent'].'% ' . $tax['name'];
				$tax_amount=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
                
				
				if (!isset($taxes[$name]))
				{
					$taxes[$name] = 0;
				}
				$taxes[$name] += $tax_amount;
			}
			
			//Check if VAT is V2 = 18 % as per tanzania low
			if($item['vat_type']=='V2')
			{
				
				$name = '18% V2';
			  if (!isset($taxes[$name]))
				{
					$taxes[$name] = 0;
				}
				
				$taxes[$name] +=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)*((18)/100);
			}
			elseif($item['vat_type']=='V2')  //Check if VAT is V3 = 10 % as per tanzania low
			{
				$name = '10% V3';
				if (!isset($taxes[$name]))
				{
					$taxes[$name] = 0;
				}
				$taxes[$name] +=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)*((10)/100);
			}
			
		}

		return $taxes;
	}

	function get_subtotal()
	{
		$subtotal = 0;
		foreach($this->get_cart() as $item)
		{
		    $subtotal+=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100);
		}
		return to_currency_no_money($subtotal);
	}

	function get_total()
	{
		$total = 0;
		foreach($this->get_cart() as $item)
		{
            $total+=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100);
		}

		foreach($this->get_taxes() as $tax)
		{
			$total+=$tax;
		}
		//check if client is paying in foreign or local currency
		if($this->get_currency()==$this->CI->config->item('currency_symbol'))
		{   // Client is  paying in local money
		  return to_currency_no_money($total);
		}
		if($this->get_currency()==$this->CI->config->item('exchange_rate_name'))
		{  // Client is  paying in local money
		
		  return  to_currency_no_money(round(($total/$this->CI->config->item('exchange_rate')),2));
		}
		else {
			// Client is  paying in local money
			return to_currency_no_money($total);
		}
		
	}
}
?>