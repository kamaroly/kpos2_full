<?php
function to_currency($number)
{
	$CI =& get_instance();
	
	$dafault_currencies= Model\Currency_model::limit(1,0)->find_by_Default(1);
	

	foreach ($dafault_currencies as $currency )
	{
	 
	 $currency_symbol = $currency->Symbol;
	 $currency=$currency;
	 break;
	}
	
	if($number >= 0)
	{
		return $currency_symbol.number_format($number, $CI->config->item('decimal_number')?$CI->config->item('decimal_number'):0, $currency->Decimal_Separator,$currency->Thousand_Separator );
    }
    else
    {
    	return '-'.$currency_symbol.number_format($number, $CI->config->item('decimal_number')?$CI->config->item('decimal_number'):0, $currency->Decimal_Separator,$currency->Thousand_Separator  );
    }
}


function to_currency_no_money($number)
{
	$CI =& get_instance();
	return number_format($number, $CI->config->item('decimal_number')? $CI->config->item('decimal_number'):0, '.',  '');
}
?>