<?php
if (isset($pagination)) :
	echo $pagination;
endif;
?>


<table class="table  table-striped table-bordered table-hover" id="invoiceListings">
	<thead id="invoiceRows">
	<tr>
		<th class="invNum" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('invoice_invoice');?></th>
		<th class="dateIssued" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('invoice_date_issued');?></th>
		<th class="clientName" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('clients_name');?></th>
		<th class="amount"     style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('invoice_amount');?></th>
		<th class="status"     style="background-color: #2b61a9;color: #FFFFFF;">
		<?php echo ($this->uri->segment(2)=='quotations')?$this->lang->line('common_actions'): $this->lang->line('common_actions');?>
		
		</th>
		
		<?php if(isset($recurring)):?>
		<?php if($recurring==true):?> <th class="status" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('start_date');?></th>  <?php endif;?>
		<?php if($recurring==true):?> <th class="status" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('end_date');?>  </th>  <?php endif;?>
		<?php if($recurring==true):?> <th class="status" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('every');?> </th>  <?php endif;?>
	    <?php endif;?>
	
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
   
	foreach($query->result() as $row): 

		$invoice_date = mysql_to_unix($row->dateIssued);
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
		<td><?php echo  $row->dateIssued;?></td>
		<td class="cName">
		<?php echo  $row->customer_names?$row->customer_names:'Customer not available';?>
		</td>
		<td><?php echo  to_currency($row->subtotal);?></td>
		<td>
		
		<?php if(isset($recurring)):?>
		<?php
		if ($row->amount_paid >= ($row->subtotal))
		{
		// paid invoices
		echo anchor('invoices/view/'.$row->sale_id, $this->lang->line('invoice_closed'), array('title' => 'invoice status'));
		}
		elseif (mysql_to_unix($row->dateIssued) >= strtotime('-30 days')) 
		{
		// owing less then the overdue days amount
		echo anchor('invoices/view/'.$row->sale_id, $this->lang->line('invoice_open'), array('title' => 'invoice status'));
		}
		else
		{ 
		// owing more then the overdue days amount
		// convert days due into a timestamp, and add the days payement is due in seconds
		$due_date = mysql_to_unix($row->dateIssued) +( 60*60*24); 
		$line = "<span class='error'>" . timespan($due_date, now()) . ' '.$this->lang->line('invoice_overdue').'</span>';
		echo anchor('invoices/view/'.$row->sale_id, $line, array('title' => 'invoice status'));
		?>
		
		
		<?php }		?>
		<a href="<?php echo site_url('invoices/sales/editsale/'.$row->sale_id);?>">
		<img border="0" src="<?php echo base_url();?>/images/b_edit.png" title="Edit" alt="Edit">
		</a>
		</td>
		<?php  if($recurring==true):?> <td><?php echo $row->recur_start_date;?></td>  <?php endif;?>
		<?php  if($recurring==true):?> <td><?php echo $row->recur_end_date;?>  </td>  <?php endif;?>
		<?php  if($recurring==true):?> <td><?php echo $this->lang->line($row->recur_frequency);?>     </td>  <?php endif;?>
		
	    <?php else:?>
	    <?php echo anchor('invoices/sales/quotetoinvoice/'.$row->sale_id,$this->lang->line('quote_to_invoice'));?>|
	    <?php echo anchor('invoices/sales/editquote/'.$row->sale_id,$this->lang->line('common_edit'));?>|
	    <?php echo anchor('invoices/sales/viewquote/'.$row->sale_id,$this->lang->line('common_view'));?>
	   <?php endif?>
	
	</tr>
	<?php
	endforeach;
	endif;
	?>
	</tbody>
</table>
