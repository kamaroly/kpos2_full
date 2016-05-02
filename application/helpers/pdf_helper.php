<?php

if (!defined('BASEPATH'))  exit('No direct script access allowed');

/*
 * Kamaro Point of Sale
 * 
 * An open source web based invoicing system
 *
 * @package		KPos
 * @author		Kamaro Lambert
 * @copyright	Copyright (c) 2012 - 2013 KPos

 * 
 */

function generate_invoice_pdf($invoice_id,$stream=true)
{
       $CI = & get_instance();

       //Loading language
 		//-----------------
 		$CI ->lang->load('date');
 	
 		//Loading helpers
 		//------------------
 		$CI ->load->helper('file','js_calendar');
 	
 		//Load sales library
 		$CI ->load->library('Sale_lib');
 	
 		//Get Date information
 		//--------------------
 		$CI ->data['invoiceDate'] = date("Y-m-d");
 	
 	
 	
 		//Get sales information
 		//---------------------
 		$data['sale_info']=$sale_info = $CI ->Sale->get_info($invoice_id)->row_array();
 		
 		//Getting the sale_id of the sale
 		//------------------------------
 		$sale_id=$sale_info['sale_id'];
 		
 	  
 		if (!$sale_id)
 		{
 			show_404();
 		}
 		$CI ->sale_lib->copy_entire_sale($sale_id);
 		$data['cart']=$CI ->sale_lib->get_cart();
 	
 		$CI ->invoices_model->mark_viewed($sale_id);
 		 
 		//Payments
 		//--------------
 		$data['payments']=$CI ->sale_lib->get_payments();
 		$data['total_paid']=$CI ->invoices_model->get_total_paid($sale_id)->payment_amount;
 		$data['subtotal']=round($CI ->sale_lib->get_subtotal());
 		$data['taxes']=$CI ->sale_lib->get_taxes();
 		$data['total']=round($CI ->sale_lib->get_total());
 		$data['total_outstanding']=$data['total']-$data['total_paid'];
 		$data['amount_change']=to_currency($CI ->sale_lib->get_amount_due() * -1);
 	
 	
 		//Receipt information
 		//-------------------
 		$data['receipt_title']=$CI ->lang->line('sales_receipt');
 		$data['date_invoice_issued']= date('m/d/Y h:i:s a', strtotime($sale_info['sale_time']));
 		$customer_id=$CI ->sale_lib->get_customer();
 		$emp_info=$CI ->Employee->get_info($sale_info['employee_id']);
 		$data['payment_type']=$sale_info['payment_type'];
 		$data['invoiceHistory'] = $CI ->invoices_model->getInvoiceHistory($sale_id);
 		$date_invoiced_time=strtotime($data['date_invoice_issued']);
 	
 		$data['sale_number']=$sale_id;
 		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
 		
 		//Get Status information
 		//-------------------------
 		if ($data['total_paid'] >= $data['total'])
 		{
 			// paid invoices
 			$data['status'] = '<span>'.$CI ->lang->line('invoice_closed').'</span>';
 			$data['class']='paid';
 		}
 		elseif (mysql_to_unix($date_invoiced_time) >= time()-(30 * 60*60*24))
 		{
 			// owing less then 30 days
 			$data['status'] = '<span>'.$CI ->lang->line('invoice_open').'</span>';
 			$data['class']='unpaid';
 		}
 		else
 		{
 			// owing more then 30 days
 			$due_date = $date_invoiced_time + (30 * 60*60*24);
 			$data['class']='unpaid';
 			$data['status'] = '<span class="error">'.timespan(mysql_to_unix($date_invoiced_time) + (30 * 60*60*24), now()). ' '.$CI ->lang->line('invoice_overdue').'</span>';
 		}
 	
 		if($customer_id!=-1)
 		{
 			$data['client']=$CI ->Customer->get_info($customer_id);
 			 
 		}
 	
 		$data['remove_buttons']=true;
 	    $html =$CI ->load->view("guest/invoices_view",$data,TRUE);
 	    
 	    $CI->load->helper('mpdf');
 	    $CI ->sale_lib->clear_all();
 	    return pdf_create($html, $CI->lang->line('invoice') . '_' . str_replace(array('\\', '/'), '_', $sale_info['invoice_number']), $stream);
 	    
 		
  }
