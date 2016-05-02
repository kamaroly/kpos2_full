<?php  
  function listTree( $tree=array(), $spacer="\t", $level=0 ) 
  {
     if( !is_int( $level ) ) 
     {
        $level = 0;
     }
  
  $level++;
  foreach( $tree as $key=>$val ) 
  {
	if (!isset($out)) $out = "";
	$out .= "\n<option value='".$val['nav_id']."'>".str_repeat( $spacer, $level );
    $out .= $val['nav_name'];

   if( isset($val['nav_children']) ) 
    {
      $out .= '</option>';
      $out .= listTree( $val['nav_children'], $spacer, $level+1 );
    }
    else 
    {
      $out .= '</option>';
    }
  }

  return $out;
}

?>


	
<?php echo form_open_multipart('config/save/items',array('id'=>'config_form'));?>
<div id="config_wrapper">
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('config_expiration_date').':', 'phone',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'expiration_days',
		'id'=>'phone',
		'value'=>$this->config->item('expiration_days')));?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('config_default_tax_rate_1').':', 'default_tax_1_rate',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'default_tax_1_name',
		'id'=>'default_tax_1_name',
		'size'=>'10',
		'value'=>($this->config->item('default_tax_1_name')!==FALSE AND $this->config->item('default_tax_1_name')!=0)  ? $this->config->item('default_tax_1_name') : $this->lang->line('items_sales_tax_1')));?>
		
	<?php echo form_input(array(
		'name'=>'default_tax_1_rate',
		'id'=>'default_tax_1_rate',
		'size'=>'4',
		'value'=>$this->config->item('default_tax_1_rate')));?>%
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('config_default_tax_rate_2').':', 'default_tax_1_rate',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'default_tax_2_name',
		'id'=>'default_tax_2_name',
		'size'=>'10',
		'value'=>($this->config->item('default_tax_2_name')!==FALSE AND $this->config->item('default_tax_2_name')!=0)? $this->config->item('default_tax_2_name') : $this->lang->line('items_sales_tax_2')));?>
		
	<?php echo form_input(array(
		'name'=>'default_tax_2_rate',
		'id'=>'default_tax_2_rate',
		'size'=>'4',
		'value'=>$this->config->item('default_tax_2_rate')));?>%
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
