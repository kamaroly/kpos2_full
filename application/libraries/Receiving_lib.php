<?php

class Receiving_lib

{

	var $CI;



  	function __construct()

	{

		$this->CI =& get_instance();

	}



	function get_cart()

	{

		if(!$this->CI->session->userdata('cartRecv'))

			$this->set_cart(array());



		return $this->CI->session->userdata('cartRecv');

	}



	function set_cart($cart_data)

	{

		$this->CI->session->set_userdata('cartRecv',$cart_data);

	}



	function get_supplier()

	{

		if(!$this->CI->session->userdata('supplier'))
		{

			$this->set_supplier(-1);
		}

		return $this->CI->session->userdata('supplier');

	}



	function set_supplier($supplier_id)

	{

		$this->CI->session->set_userdata('supplier',$supplier_id);

	}

	/**
	 * @author Kamaro Lambert
	 * @method to set the tax for the purchase receipt
	 */


	function set_receipt_tax($tax_amount)
	{	
		//Set the tax for the receipt
		$this->CI->session->set_userdata('receipt_tax',$tax_amount);
				
	}
	
	/**
	 * @author Kamaro Lambert
	 * @method to get the receipt tax
	 */
	function get_receipt_tax()
	
	{
	
		if(!$this->CI->session->userdata('receipt_tax') OR $this->CI->session->userdata('receipt_tax')===NULL)
		{
			$this->set_receipt_tax(-1);
		}
		return $this->CI->session->userdata('receipt_tax');
	
	}
	
    /**
     *@author Kamaro Lambert
     *@method to clear the receipt tax 
     */
	function clear_receipt_tax()
	{
	   $this->CI->session->unset_userdata('receipt_tax');
	}
	
	function get_mode()

	{

		if(!$this->CI->session->userdata('recv_mode'))

			$this->set_mode('receive');



		return $this->CI->session->userdata('recv_mode');

	}



	function set_mode($mode)

	{

		$this->CI->session->set_userdata('recv_mode',$mode);

	}



	function add_item($item_id,$quantity=1,$discount=0,$price=null,$description=null,$serialnumber=null)

	{

		//make sure item exists in database.

		if(!$this->CI->Item->exists($item_id))

		{

			//try to get item id given an item_number

			$item_id = $this->CI->Item->get_item_id($item_id);



			if(!$item_id)

				return false;

		}



		//Get items in the receiving so far.

		$items = $this->get_cart();



        //We need to loop through all items in the cart.

        //If the item is already there, get it's key($updatekey).

        //We also need to get the next key that we are going to use in case we need to add the

        //item to the list. Since items can be deleted, we can't use a count. we use the highest key + 1.



        $maxkey=0;                       //Highest key so far

        $itemalreadyinsale=FALSE;        //We did not find the item yet.

		$insertkey=0;                    //Key to use for new entry.

		$updatekey=0;                    //Key to use to update(quantity)



		foreach ($items as $item)

		{

            //We primed the loop so maxkey is 0 the first time.

            //Also, we have stored the key in the element itself so we can compare.

            //There is an array function to get the associated key for an element, but I like it better

            //like that!



			if($maxkey <= $item['line'])

			{

				$maxkey = $item['line'];

			}



			if($item['item_id']==$item_id)

			{

				$itemalreadyinsale=TRUE;

				$updatekey=$item['line'];

			}

		}



		$insertkey=$maxkey+1;



		//array records are identified by $insertkey and item_id is just another field.

		$item = array(($insertkey)=>

		array(

			'item_id'=>$item_id,

			'line'=>$insertkey,

			'name'=>$this->CI->Item->get_info($item_id)->name,

			'description'=>$description!=null ? $description: $this->CI->Item->get_info($item_id)->description,

			'serialnumber'=>$serialnumber!=null ? $serialnumber: '',

			'allow_alt_description'=>$this->CI->Item->get_info($item_id)->allow_alt_description,

			'is_serialized'=>$this->CI->Item->get_info($item_id)->is_serialized,

			'quantity'=>$quantity,

            'discount'=>$discount,

			'acc_id'=>$this->CI->Item->get_info($item_id)->acc_id,

			'price'=>$price!=null ? $price: $this->CI->Item->get_info($item_id)->cost_price

			)

		);



		//Item already exists

		if($itemalreadyinsale)

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



	function is_valid_receipt($receipt_receiving_id)

	{

		//RECV #

		$pieces = explode(' ',$receipt_receiving_id);



		if(count($pieces)==2)

		{

			return $this->CI->Receiving->exists($pieces[1]);

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



	function return_entire_receiving($receipt_receiving_id)

	{

		//POS #

		$pieces = explode(' ',$receipt_receiving_id);

		$receiving_id = $pieces[1];



		$this->empty_cart();

		$this->delete_supplier();



		foreach($this->CI->Receiving->get_receiving_items($receiving_id)->result() as $row)

		{

			$this->add_item($row->item_id,-$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);

		}

		$this->set_supplier($this->CI->Receiving->get_supplier($receiving_id)->person_id);

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



	function copy_entire_receiving($receiving_id)

	{

		$this->empty_cart();

		$this->delete_supplier();



		foreach($this->CI->Receiving->get_receiving_items($receiving_id)->result() as $row)

		{

			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);

		}

		$this->set_supplier($this->CI->Receiving->get_supplier($receiving_id)->person_id);



	}



	function delete_item($line)

	{

		$items=$this->get_cart();

		unset($items[$line]);

		$this->set_cart($items);

	}



	function empty_cart()

	{

		$this->CI->session->unset_userdata('cartRecv');

	}



	function delete_supplier()

	{

		$this->CI->session->unset_userdata('supplier');

	}



	function clear_mode()

	{

		$this->CI->session->unset_userdata('receiving_mode');

	}

	function clear_comment()

	{

		$this->CI->session->unset_userdata('comment');

	}

	



	function clear_all()

	{

		$this->clear_mode();

		$this->empty_cart();

		$this->delete_supplier();

		$this->empty_cart();

		$this->clear_comment();

		$this->empty_payments();
		
		$this->clear_receipt_tax();

	}



	function get_total()

	{

		$total = 0;

		foreach($this->get_cart() as $item)

		{

            $total+=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100);

		}

		//Check if we have tax which is already set
		$tax=$this->get_receipt_tax();
		if($tax!=-1 AND !empty($tax) AND is_numeric($tax))
		{
			//Add tax to the total 
			$total+=$tax;
		}

		return $total;

	}

	/**

	 * @author Kamaro Lambert

	 * @name  get_amount_due()

	 * @return numeric

	 */

	function get_amount_due()

	{

		$amount_due=0;

		$payment_total = $this->get_payments_total();

		$sales_total=$this->get_total();

		$amount_due=to_currency_no_money($sales_total - $payment_total);

		return $amount_due;

	}

	/**

	 * @Author Kamaro Lambert

	 * @name   get_payments_total

	 * @Method to get the total paid amount

	 * 

	 */

	function get_payments_total()

	{

		$subtotal = 0;

		foreach($this->get_payments() as $payments)

		{

			$subtotal+=$payments['payment_amount'];

		}

		return to_currency_no_money($subtotal);

	}

	

	

	/**

	 * @Author Kamaro Lambert

	 * @method to get payments

	 */

	function get_payments()

	{

		if( !$this->CI->session->userdata( 'payments' ) )

			$this->set_payments( array( ) );

	

		return $this->CI->session->userdata('payments');

	}

	/**

	 * @author Kamaro Lambert

	 * @param numeric $payment_id

	 * @param numeric $payment_amount

	 * @param numeric $accid

	 * @return boolean

	 */

	function add_payment($payment_id,$payment_amount)

	{

		$payments=$this->get_payments();

		$payment = array($payment_id=>

				array(

						'payment_type'=>$payment_id,

						'payment_amount'=>$payment_amount

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

	/**

	 * @Author Kamaro Lambert

	 * @name   set_payments

	 * @param array $payments_data

	 */

	//Alain Multiple Payments

	function set_payments($payments_data)

	{

		$this->CI->session->set_userdata('payments',$payments_data);

	}

	

	/**

	 * @Author Kamaro Lambert

	 * @param numeric $payment_id

	 * @param numeric $payment_amount

	 * @return boolean

	 */

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

	/**

	 * @Author Kamaro Lambert

	 * @name  delete_payment

	 * @param numeric $payment_id

	 */

	function delete_payment( $payment_id )

	{

		$payments = $this->get_payments();

		unset( $payments[urldecode( $payment_id )] );

		$this->set_payments( $payments );

	}

	

	/**

	 * @Author Kamaro Lambert

	 * @method to empty payments

	 */

	function empty_payments()

	{

		$this->CI->session->unset_userdata('payments');

	}

}

?>