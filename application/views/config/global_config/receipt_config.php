
<?php echo form_open_multipart('config/save/receipt',array('id'=>'config_form'));?>
<div id="config_wrapper">
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('common_return_policy').':', 'return_policy',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'return_policy',
		'id'=>'return_policy',
		'rows'=>'4',
		'cols'=>'17',
		'value'=>$this->config->item('return_policy')));?>
	</div>
</div>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('config_default_paper_size').':', 'print_after_sale',array('class'=>'wide')); ?>
	<div class='form_field'>
	 <select name="print_receipt_size">
	   <option value="receipt_tape" <?php echo ($this->config->item('print_receipt_size')=='receipt_tape')?'selected':'';?>>
	   <?php echo $this->lang->line('config_receipt_tape');?> </option>
	   
	    <option value="receipt" <?php echo ($this->config->item('print_receipt_size')=='receipt')?'selected':'';?>>
	     <?php echo $this->lang->line('config_A4');?>
	     </option>
	 </select>
	 
	</div>
</div>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('config_show_logo_on_receipt').':', 'show_logo_on_receipt',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_checkbox(array(
		'name'                =>'show_logo_on_receipt',
		'id'                  =>'show_logo_on_receipt',
		'value'               =>'show_logo_on_receipt',
		'checked'             =>$this->config->item('show_logo_on_receipt')));?>
	</div>
</div>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('config_finish_sale_confirm').':', 'print_confirm',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_checkbox(array(
		'name'=>'finish_sale_confirm',
		'id'=>'print_confirm',
		'value'=>'print_confirm',
		'checked'=>$this->config->item('finish_sale_confirm')));?>
	</div>
</div>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('config_print_after_sale').':', 'print_after_sale',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_checkbox(array(
		'name'=>'print_after_sale',
		'id'=>'print_after_sale',
		'value'=>'print_after_sale',
		'checked'=>$this->config->item('print_after_sale')));?>
	</div>
</div>


<?php 
echo form_submit(array(
	'name'=>'dosubmit',
	'id'=>'submit',
	'value'=>$this->lang->line('common_submit'),
	'class'=>'btn btn-small btn-primary float_right')
);
?>
</div>
<?php
echo form_close();
?>
<div id="feedback_bar"></div>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$('#config_form').validate({
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
			company: "required",
			address: "required",
    		phone: "required",
    		default_tax_rate:
    		{
    			required:true,
    			number:true
    		},
    		email:"email",
    		return_policy: "required"    		
   		},
		messages: 
		{
     		company: "<?php echo $this->lang->line('config_company_required'); ?>",
     		address: "<?php echo $this->lang->line('config_address_required'); ?>",
     		phone: "<?php echo $this->lang->line('config_phone_required'); ?>",
     		default_tax_rate:
    		{
    			required:"<?php echo $this->lang->line('config_default_tax_rate_required'); ?>",
    			number:"<?php echo $this->lang->line('config_default_tax_rate_number'); ?>"
    		},
     		email: "<?php echo $this->lang->line('common_email_invalid_format'); ?>",
     		return_policy:"<?php echo $this->lang->line('config_return_policy_required'); ?>"
	
		}
	});
});
</script>
