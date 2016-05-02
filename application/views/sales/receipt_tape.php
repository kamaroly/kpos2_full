
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $this->lang->line('sales_id').": ".$sale_id; ?></title>
<style type="text/css" media="all">
	body { margin: 40px; 
	      background: #FFFFFF; 
	      color: #000000; 
	      font-size: 10pt; 
	      font-family: Arial,Helvetica,sans-serif;
	      }
	p    { margin: 0px 0px 1em 0px;}
	.center {text-align: center;}
	.left   {text-align: left;}
	.right  {text-align: right;}
	.bold   {font-weight: bold;}
	.underline {text-decoration: underline;}	
	.indent {padding-left: 2em;}
	#heading{ text-align: center;}
	#header {text-align: left;}
	#details thead td {font-weight: bold; text-decoration:underline; }
	#details thead tr {border-bottom:1px solid #cccc;}
	#totals {}	
</style>
<style type="text/css" media="print">
	body {
	 margin: 0px;
	  background: #FFFFFF; 
	  color: #000000; font-size: 10pt; font-family: Arial,Helvetica,sans-serif;}
</style>
</head>
<body>
<p><center>
		
			<?php
$image_properties = array(
		'src' => 'images/company_logo/'.$this->config->item('company_logo'),
		'alt' => 'KPharmacy',
		'width' => '250',
		'height' => '30',
);
echo img($image_properties);?><br/>			
	

		<p><b><?php echo $receipt_title; ?></b>
		
</p>
<p> <?php echo $transaction_time ?></p>
		</center>
	</p>
	
	<p id="header"> 
		<?php echo $this->lang->line('sales_id').": ".$sale_id; ?><br>
		<?php echo $this->lang->line('employees_employee').": ".$employee; ?><br>
		
		
									
	</p>
		
	<table id="details" width='100%'>
		<thead>
			<tr>
				<td>Item</td>
				<td>Qt</td>
				<td class="right">Amount</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach(array_reverse($cart, true) as $line=>$item):	?>					
			<tr >
				<td style="border-bottom: 1px solid #000000;"><b><?php echo $item['name']; ?></b></td>
				<td style="border-bottom: 1px solid #000000;"><?php echo $item['quantity']; ?> @ <?php echo to_currency($item['price']); ?></td>
				<td  style="border-bottom: 1px solid #000000;" class="right"><?php echo to_currency(round($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)); ?></td>
			</tr>			
		   <?php endforeach;?>
		</tbody>
	</table>
	<br/>
	<div align="right">
	<table id="totals">
		
		<tr>
			<td class="right"><?php echo $this->lang->line('sales_total'); ?></td>
			<td class="bold right"><?php echo to_currency($total); ?></td>
		</tr>
		<tr>
			<td class="right bold underline"><?php echo $this->lang->line('sales_payment'); ?></td>
			<td></td>
		</tr>
		<?php	foreach($payments as $payment_id=>$payment): ?>
		<tr >
			<td class="right"> <?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?></td>
			<td class="right"><?php echo to_currency( round($payment['payment_amount']) * -1 ); ?></td>
		</tr>		
		<?php endforeach;?>
			</table>
	</div>		
	
					

	
	<p><center><p>Thank you for shopping with us.
<br />Please come again.</p></center></p>
<div id='barcode'>
	c
	</div>
<script type="text/javascript">
window.print();

</script>

</body>

</html>
