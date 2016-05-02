<?php 
echo "B \t ".$this->config->item('company')."&nbsp; \t endline";                                        //TAXPAYER'S NAME
echo "N \t ".$this->config->item('address')."&nbsp; \t \t endline";                                     //Shop address
echo "N \t \t TIN: ".$this->config->item('tin')."&nbsp; \t \t endline"; 
                                   //Taxpayer Identification number
if(substr($RLabel, 0,1)=='C'):
echo "N \t \t COPY&nbsp; \t \t endline";
endif;
echo "N -------------------------------------------- endline";
if(substr($RLabel,1)=='R'):
echo "N \t \t REFUND&nbsp;  \t \t endline";
echo "N -------------------------------------------- endline";
endif;

if(substr($RLabel,1)=='S'):
echo "N \t \t Welcome to our shop&nbsp; \t \t endline";                               //Commercial message 
elseif(substr($RLabel,1)=='R'):
echo "REFUND IS APPROVED ONLY FOR&nbsp; \r
      ORIGINAL SALES RECEIPT&nbsp;  \r  endline";
endif;
if(strlen($customer_tin)==9):
echo "N \t \t Client ID: $customer_tin \t \t  endline";		
endif;					  //CLIENT'S Identification (optional)
echo "N -------------------------------------------- endline";

$A_EX=0;
$TOTAL_B=0;
$item_tax=0;

foreach(array_reverse($cart, true) as $line=>$item)
	{
    if ($item['vat_type']=="A-EX") :
     
    $A_EX+=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;

    elseif ($item['vat_type']=="B") :

    $TOTAL_B+=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;
    
    $item_tax=$item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100;
     $item_tax= $item_tax*0.18;
    endif;
    $discount=($item['discount']>0)?"(Discount -".$item['discount'].")":"";

echo "N ".$item['name']." ".number_format($item['price'],2,'.','')."X".$discount."\t".number_format($item['quantity'],2,'.','')."\t".number_format(($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)+ $item_tax,2,'.','')." ".$item['vat_type']."&nbsp;endline";

	}
echo "N -------------------------------------------- endline";
if(substr($RLabel, 0,1)!='N'):
echo "N \t \t THIS IS NOT AN&nbsp;OFFICIAL RECEIPT \t \t endline";
echo "N -------------------------------------------- endline";
endif;
echo "N TOTAL \t \t \t  ".number_format($total,2,'.','')." endline";     
if($A_EX>0):
echo "N TOTAL A-EX \t \t \t".number_format($A_EX,2,'.','')." endline";                                //Total price to be paid 
endif;
echo "N TOTAL TAX B&nbsp;\t \t \t  ".number_format(array_sum($taxes),2,'.','')." endline";                             //Total TAX exempted amount
echo "N TOTAL B-18%  \t \t \t ".number_format($TOTAL_B,2,'.','')." endline"; 	
echo "N TOTAL TAX    \t \t \t   ".number_format(array_sum($taxes),2,'.','')." endline"; 				  //Total amount of TAX 			   
							                                                        
echo "N -------------------------------------------- endline";		
if(substr($RLabel, 0,1)=='T'):
echo "N \t \t TRAINING MODE \t \t endline";
echo "N -------------------------------------------- endline ";
elseif (substr($RLabel, 0,1)=='P'):
echo "N \t \t PROFORMA \t \t endline";
echo "N -------------------------------------------- endline ";
endif;
	foreach($payments as $payment_id=>$payment):
		$splitpayment=explode(':',$payment['payment_type']); 
echo "N ". $splitpayment[0]." \t \t".number_format( (0-round($payment['payment_amount']) * -1),2,'.','' )." endline";  
	endforeach;
									                //Payment method
echo "N ITEMS NUMBER \t \t ".count($cart)." endline"; 									                //Number of items sold 
echo "N -------------------------------------------- endline";
echo "N \t\t SDC INFORMATION\t \t endline";
echo "N Date: $Date \t\tTime: $TIME endline";
echo "N SDC ID:  \t\t\t\t\t  $SDC_ID endline";
echo "N RECEIPT NUMBER: \t\t $TNumber/$GNumber $RLabel endline";
if (strlen($Internal_Data)>=26) :
echo "N \t \tInternal Data:\t \t endline";
echo "N \t$Internal_Data endline";
endif;
if (strlen($Receipt_Signature)>=16) :
echo "N \t\t Receipt Signature:\t \t endline";
echo "N \t\t$Receipt_Signature \t \t endline";
endif;
echo "N -------------------------------------------- endline";
echo "N RECEIPT NUMBER: \t\t\t $receipt_number \t endline";
echo "N DATE: ".DATE('d/m/y')."\t\tTIME: ".DATE('h:i:s')." endline";
echo "N MRC: \t\t \t \t  $MRC endline";
echo "N -------------------------------------------- endline";
echo "N You were served by ".$employee;
echo "E \t". $this->config->item('return_policy')." endline";
?>
