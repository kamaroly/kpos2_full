<style>
<!--

.success {
color: #ffffff;
text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
background-color: #5bb75b;
background-image: -moz-linear-gradient(top, #62c462, #51a351);
background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#51a351));
background-image: -webkit-linear-gradient(top, #62c462, #51a351);
background-image: -o-linear-gradient(top, #62c462, #51a351);
background-image: linear-gradient(to bottom, #62c462, #51a351);
background-repeat: repeat-x;
border-color: #51a351 #51a351 #387038;
border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff62c462', endColorstr='#ff51a351', GradientType=0);
filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}
.primary {
color: #ffffff;
text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
background-color: #006dcc;
background-image: -moz-linear-gradient(top, #0088cc, #0044cc);
background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc));
background-image: -webkit-linear-gradient(top, #0088cc, #0044cc);
background-image: -o-linear-gradient(top, #0088cc, #0044cc);
background-image: linear-gradient(to bottom, #0088cc, #0044cc);
background-repeat: repeat-x;
border-color: #0044cc #0044cc #002a80;
border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);
filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}



-->
</style>
<?php echo form_open_multipart('config/save/sdc',array('id'=>'config_form'));?>
<div id="config_wrapper">
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>

<div class="field_row clearfix">  
<?php echo form_label($this->lang->line('config_sdc_port').':', 'sdc_port',array('class'=>'wide required')); ?>
  <div class='form_field'>
  <?php echo form_dropdown('sdc_port',$this->avalable_comport_list(),$this->config->item('sdc_port') );
    ?>
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

<?php echo anchor('config/index/sdc/checkstatus','Check Sdc Status',array('class'=>'btn btn-small btn-primary float_right'));?>
<?php if(isset($sdc_status)):?>
<table class="table table-bordered table-striped">
  <TR>
       <TH class="primary">STATUS NAME</TH>
       <TH class="primary">STATUS VALUE</TH>
  </TR>
<?php foreach ($sdc_status as $key => $value):?>
  <TR>
  	   <TD><?php echo $key;?></TD>
  	   <TD><?php echo $value;?></TD>
  </TR>
<?php endforeach;?>
</table>
<?php endif;?>


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
			sdc_port: "required",
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
