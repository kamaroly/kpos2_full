<?php
if (isset($pagination)) :
	echo $pagination;
endif;
?>

<table class="table table-bordered table-striped" id="invoiceListings">
	<thead id="invoiceRows">
	<tr>
		<th class="invNum" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('invoice_invoice');?></th>
		<th class="dateIssued" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('invoice_date_issued');?></th>
		<th class="clientName" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('clients_name');?></th>
		<th class="amount"     style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('invoice_amount');?></th>
		<th class="status"     style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('invoice_status');?></th>
		</tr>
	</thead>
	<tbody>
<?php
if (isset($total_rows) && $total_rows == 0):
?>
	<tr>
		<td colspan="5">
			<?php echo $this->lang->line('invoice_no_invoice_match');?>
		</td>
	</tr>
<?php
 else:
 
	$last_retrieved_month = 0;
	$display_month = TRUE; // for later use in a setting preference
   
	foreach($quotation_sales as $row): 

		$invoice_date = mysql_to_unix($row->sale_time);
		if ($last_retrieved_month != date('F', $invoice_date) && $display_month):
?>

	<tr>
		<td colspan="5" class="monthbreak"><?php echo date('F', $invoice_date);?></td>
	</tr>

<?php 
		endif; 
		$last_retrieved_month = date('F', $invoice_date);
		// localized month
		$display_date = formatted_invoice_date($row->dateIssued);

?>
	<tr>
		<td><?php echo  $row->sale_id;?></td>
		<td><?php echo  date('Y-m-d',strtotime($row->sale_time));?></td>
		<td class="cName">
		<?php echo  ($row->first_name!=null and $row->last_name!=null)?$row->customer_names:'Customer not available';?>
		</td>
			
	</tr>
	<?php
	endforeach;
	endif;
	?>
	</tbody>
</table>
