<?php

$lang['sales_giftcard_number']='Gift Card Number';
$lang['sales_select_insurance']='Select insurance';
$lang['sales_insurance_percentage']='Enter contribution';

$lang['sales_payment_currency']='Payment Currency';
$lang['sales_no_amount_entered']='No amount entered';
$lang['sales_must_enter_bank_name_and_check_number']='You must enter bank name and Check number';

$lang['sales_successfully_saved_quotation'] ='Pro Forma Transaction done successfully';
$lang['sales_failed_to_saved_quotation'] ='Pro Forma Transaction failed to be completed';

$lang['sales_giftcard']='Gift Card';
$lang['sales_register']='Sales Register';
$lang['sales_mode']='Register Mode';
$lang['sales_type']='Sales type';
$lang['sales_new_item'] = 'New Item';
$lang['sales_item_name'] = 'Item Name';
$lang['sales_item_number'] = 'Item #';
$lang['sales_new_customer'] = 'New';
$lang['sales_customer'] = 'Customer';
$lang['sales_no_items_in_cart']='There are no items in the cart';
$lang['sales_total']='Total';
$lang['sales_total_in']='Total in ';
$lang['sales_total_foreign']='Total in foreign';
$lang['sales_tax_percent']='Tax %';
$lang['sales_price']='Price';
$lang['sales_quantity']='Qty.';
$lang['sales_discount']='Disc %';
$lang['sales_edit']='Edit';
$lang['sales_payment']='Payment Type';
$lang['sales_edit_item']='Edit Item';
$lang['sales_find_or_scan_item']='Find/Scan Item';
$lang['sales_find_or_scan_item_or_receipt']='Find/Scan Item OR Receipt';
$lang['sales_select_customer']='Select client';
$lang['sales_start_typing_item_name']='Start Typing item\'s name or scan barcode...';
$lang['sales_start_typing_customer_name']='Search client...';
$lang['sales_sub_total']='Sub Total';
$lang['sales_tax']='Tax';
$lang['sales_comment']='Comment';
$lang['sales_unable_to_add_item']='Unable to add item to sale';
$lang['sales_sale_for_customer']='Customer:';
$lang['sales_remove_customer']='Remove Customer';
$lang['sales_error_editing_item']='Error editing item';
$lang['sales_complete_sale']='Complete Sale';
$lang['sales_complete_proforma']='Complete Pro Forma';
$lang['sales_cancel_sale']='Cancel Sale';
$lang['sales_add_payment']='Add Payment';
$lang['sales_receipt']='Sales Receipt';
$lang['sales_id']='Invoice No';
$lang['sales_normal']='Normal';
$lang['sales_grossary']='Grossary';
$lang['sales_interprener']='Interprener';
$lang['sales_sale']='SALES';
$lang['sales_return']='REFUND';
$lang['sales_confirm_finish_sale'] = 'Are you sure you want to submit this sale? This cannot be undone.';
$lang['sales_confirm_cancel_sale'] = 'Are you sure you want to clear this sale? All items will cleared.';
$lang['sales_cash'] = 'Cash';
$lang['sales_check'] = 'Check';
$lang['sales_insurance'] = 'Insurance';
$lang['sales_debit'] = 'Debit Card';
$lang['sales_credit'] = 'Credit Card';
$lang['sales_giftcard'] = 'Gift Card';

//SDC MESSAGES
$lang['sales_error_during_transaction']                            ="SDC error Occured during transaction,Please check if SDC is working well , or if it's powered on.";
$lang['sales_sdc_not_connected']                                   ="Couldn't complete sales because SDC is not connected on this computer";

$lang['sales_sdc_not_connected_to_the_port']                       ="The set sdc port (_SDC_PORT_) is not correct. Please check the connection<br /> 
        (Right Click on My Computer->Select Properties->Hardware->Device Manager->Ports (COM & LPT))<br /> 
        If there is a comm-port on which SDC is connected, make sure that it is is well set in Configuration , on Left side under Sales Data Controller Menu";

$lang['sdc_error_internal_memory_full']                            ="SDC Internal memory full.";
$lang['sdc_error_internal_data_corrupted']                         ="SDC internal data corrupted.";
$lang['sdc_error_internal_memory_error']                           ="SDC Internal memory error.";
$lang['sdc_error_real_Time_Clock_error']                           ="SDC Real Time Clock error.";
$lang['sdc_error_wrong_command_code']                              ="SDC wrong command code.";
$lang['sdc_error_wrong_data_format_in_the_CIS_request_data']       ="Wrong data format in the CIS request data.";
$lang['sdc_error_wrong_TIN_in_the_CIS_request_data']               ="wrong TIN in the CIS request data.";
$lang['sdc_error_wrong_tax_rate_in_the_CIS_request_data']          ="Wrong tax rate in the CIS request data";
$lang['sdc_error_invalid_receipt_number_int_the_CIS_request_data'] ="Invalid receipt number int the CIS request data";

$lang['sdc_error_sdc_not_activated']                               ="SDC not activated";
$lang['sdc_error_sdc_already_activated']                           ="SDC already activated";
$lang['sdc_error_sim_card_error']                                  ="SIM card error";
$lang['sdc_error_gprs_modem_error']                                ="GPRS modem error";
$lang['sdc_error_hardware_intervention_is_necessary']              ="Hardware intervention is necessary";

$lang['sdc_is_busy']                                               ="SDC is busy/not responding please switch it off  and on again.";


//Insurances
$lang['sales_rama'] = 'RAMA';
$lang['sales_soras'] = 'SORAS';
$lang['sales_colar'] = 'COLAR';
$lang['sales_radiant'] = 'RADIANT';


$lang['sales_amount_tendered'] = 'Amount Tendered';
$lang['sales_change_due'] = 'Change Due';
$lang['sales_payment_not_cover_total'] = 'Payment Amount does not cover Total';

$lang['sales_transaction_failed'] = 'Sales Transaction Failed';
$lang['sales_transaction_success'] = 'Sales Transaction completed successfully';

$lang['sales_must_enter_numeric'] = 'Must enter numeric value for amount tendered';
$lang['sales_must_enter_numeric_giftcard'] = 'Must enter numeric value for giftcard number';
$lang['sales_serial'] = 'Serial';
$lang['sales_description_abbrv'] = 'Desc';
$lang['sales_item_out_of_stock'] = 'Item is Out of Stock';
$lang['sales_item_insufficient_of_stock'] = 'Item is Insufficient of Stock';
$lang['sales_quantity_less_than_zero'] = 'Warning, Desired Quantity is Insufficient. You can still process the sale, but check your inventory';
$lang['sales_successfully_updated'] = 'Sale successfully updated';
$lang['sales_unsuccessfully_updated'] = 'Sale unsuccessfully updated';
$lang['sales_edit_sale'] = 'Edit Sale';
$lang['sales_employee'] = 'Employee';
$lang['sales_successfully_deleted'] = 'Sale successfully deleted';
$lang['sales_unsuccessfully_deleted'] = 'Sale unsuccessfully deleted';
$lang['sales_delete_entire_sale'] = 'Delete entire sale';
$lang['sales_delete_confirmation'] = 'Are you sure you want to delete this sale, this action cannot be undone';
$lang['sales_date'] = 'Sale Date';
$lang['sales_delete_successful'] = 'You have successfully deleted a sale';
$lang['sales_delete_unsuccessful'] = 'You have unsuccessfully deleted a sale';
$lang['sales_suspend_sale'] = 'Suspend Sale';
$lang['sales_confirm_suspend_sale'] = 'Are you sure you want to suspend this sale?';
$lang['sales_suspended_sales'] = 'Suspended Sales';
$lang['sales_suspended_sale_id'] = 'Suspended Sale ID';
$lang['sales_date'] = 'Date';
$lang['sales_customer'] = 'Customer';
$lang['sales_comments'] = 'Comments';
$lang['sales_unsuspend_and_delete'] = 'Unsuspend and Delete';
$lang['sales_unsuspend'] = 'Unsuspend';
$lang['sales_successfully_suspended_sale'] = 'Your sale has been successfully suspended';
$lang['sales_successfully_saved_quatation'] = 'Your Quotation has been successfully saved';
$lang['sales_successfully_saved_draft'] = 'Your Draft has been successfully saved';
$lang['sales_email_receipt'] = 'E-Mail Receipt';
$lang['sales_please_select_client']='You must select the customer in order to continue';

$lang['sales_you_are_selling_on_credit_have_to_choose_customer']='You are selling on credit, You have to choose the customer';
?>