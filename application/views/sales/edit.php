
<div id="edit_sale_wrapper">
	
	
	<?php echo form_open("sales/save/".$sale_info['sale_id'],array('id'=>'sales_edit_form')); ?>
	<ul id="error_message_box"></ul>
	
	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('sales_receipt').':', 'customer'); ?>
		<div class='form_field'>
			<?php echo anchor('sales/receipt/'.$sale_info['sale_id'], $sale_type.' '.$sale_info['sale_id'], array('target' => '_blank'));?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('sales_date').':', 'date'); ?>
		<div class='form_field'>
			<?php echo form_input(array('name'=>'date','value'=>date('m/d/Y', strtotime($sale_info['sale_time'])), 'id'=>'date'));?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('sales_customer').':', 'customer'); ?>
		<div class='form_field'>
			<?php echo form_dropdown('customer_id', $customers, $sale_info['customer_id'], 'id="customer_id"');?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('sales_employee').':', 'employee'); ?>
		<div class='form_field'>
			<?php echo form_dropdown('employee_id', $employees, $sale_info['employee_id'], 'id="employee_id"');?>
		</div>
	</div>

	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('sales_payment').':', 'employee'); ?>
		<div class='form_field'>
			<?php echo form_dropdown( 'payment_type',
						                  $payment_options, $payment_method,
						                  'id="payment_types" style="float:right;"' ); 	
						                  ?>
          <?php echo $sale_info['payment_type']; ?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('sales_comment').':', 'comment'); ?>
		<div class='form_field'>
			<?php echo form_textarea(array('name'=>'comment','value'=>$sale_info['comment'],'rows'=>'4','cols'=>'23', 'id'=>'comment'));?>
		</div>
	</div>
	
	<?php
	echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>$this->lang->line('common_submit'),
		'class'=>'btn btn-small btn-primary')
	);
	?>
	</form>
	<?php echo form_open("sales/delete/".$sale_info['sale_id'],array('id'=>'sales_delete_form')); ?>
		<?php
		echo form_submit(array(
			'name'=>'submit',
			'id'=>'submit',
			'value'=>$this->lang->line('sales_delete_entire_sale'),
			'class'=>'button red')
		);
		?>
		
	</form>
</div>
<div id="feedback_bar"></div>


<script type="text/javascript" language="javascript">
$(document).ready(function()
{	
	$('#date').datePicker({startDate: '01/01/1970'});
	$("#sales_delete_form").submit(function()
	{
		if (!confirm('<?php echo $this->lang->line("sales_delete_confirmation"); ?>'))
		{
			return false;
		}
	});
	
	$('#sales_edit_form').validate({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				if(response.success)
				{
					set_feedback(response.message,'success_message',false);
				}
				else
				{
					set_feedback(response.message,'error_message',true);	
					
				}
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
   		},
		messages: 
		{
		}
	});
});
</script>