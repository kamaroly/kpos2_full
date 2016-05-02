
	<?php echo form_open('invoices/payment', array( 'name' => 'enterPayment','id'=>'payment_form'), array('id'=>$sale_id));?>

		<table class="table table-bordered table-striped invoice_items stripe">
		<tbody>
		<tr>
			<td>
			 	<?php echo $this->lang->line('invoice_date_paid_full');?>
			</td>
			<td>	<div class='form_field'> 
				<input type="text" name="date_paid" id="date_paid" value="<?php echo date('Y-m-d'); ?>"/>
				</div>
			</td>
			</tr>
			<tr>
			<td>
		        <?php echo $this->lang->line('sales_payment').':   ';?>
		    </td>
			<td>
				<?php echo form_dropdown( 'payment_type',
						                   $payment_options, '');?>
		    </td>
			</tr>
			<tr>
			<td>  <?php echo $this->lang->line('invoice_amount');?>
			
				<span><?php echo $this->config->item('currency');?> </span>
				</td>
			<td> 
				<input type="text" name="amount" id="amount" maxlength="10" size="10" /> 
			</td>
			</tr>
			<tr>
			<td colspan="2">
            
				<span><?php echo $this->lang->line('invoice_note') . ' ' . $this->lang->line('invoice_note_max_chars');?></span>
				<br>
				<textarea name="payment_note" type="text" id="private_note" cols="42" rows="7"></textarea>
				
			</td>
			</tr>
			<tr>
			<td colspan="2">
			<input type="submit" name="makePayment" style="float:right;"
			value="<?php echo $this->lang->line('menu_enter_payment');?>" class="btn btn-small btn-primary" /> 
			
			</td>
			</tbody>
			</table>
	

	<?php echo form_close();?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	
	$('#date_paid').datePicker({ dateFormat: 'dd-mm-yy' });
	
});
</script>
