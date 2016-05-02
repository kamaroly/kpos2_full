<?php

if(!empty($R_NAM)):
echo 'R_NAM '.$R_NAM ."\r\n";
endif;

if(!empty($R_VRN)):
echo 'R_VRN '.$R_VRN ."\r\n";
endif;

if(!empty($R_TIN)):
echo 'R_TIN '.$R_TIN ."\r\n";
endif;
if(!empty($R_ADR)):
echo 'R_ADR '.$R_ADR ."\r\n";
endif;

if(!empty($R_TXT)):
echo 'R_TXT '.'" ----------------------------------- "'."\r\n";
echo 'R_TXT '.$R_TXT ."\r\n";
echo 'R_TXT '.'" ----------------------------------- "'."\r\n";
endif;
$i=1;

//Loop all the items in the cart
foreach ($transactions as $transaction):
echo 'R_TRP "'.$transaction['name'].'" '.$transaction['quantity'].' * '.$transaction['price'].' '.$transaction['vat_type']."\r\n";
$i++;
endforeach;
echo '" ----------------------------------- "'."\r\n";

//check the payments methods
foreach ($R_PM1 as $payments_type=>$payments_amount):

//Payment is done via cash
if($payments_amount['payment_type']==$this->lang->line('sales_cash') ):	

echo 'R_PM1  '.$payments_amount['payment_amount']."\r\n";

//Payment is done via check
elseif($payments_amount['payment_type']==$this->lang->line('sales_check') ):

echo 'R_PM3  '.$payments_amount['payment_amount']."\r\n";

//Payment is done via credit or debit card
elseif($payments_amount['payment_type']==$this->lang->line('sales_debit')  or $payments_amount['payment_type']==$this->lang->line('sales_credit') ):

echo 'R_PM2  '.$payments_amount['payment_amount']."\r\n";

endif;

endforeach;

echo 'R_STT '.$R_STT ."\r\n";