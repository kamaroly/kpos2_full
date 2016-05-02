<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<?php
echo'sfasdfa';

echo form_open('items_categories/save/'.$item_info->id,array('id'=>'item_form'));
?>

<fieldset id="item_basic_info">
<legend><?php echo $this->lang->line("items_category_basic_information"); ?></legend>


<div class="field_row clearfix">
<?php echo form_label($this->lang->line('items_category_name').':', 'name',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'name',
		'id'=>'name',
		'value'=>$item_info->cname?$item_info->cname:'')
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('items_category_description').':', 'category',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'description',
		'id'=>'category',
		'value'=>$item_info->description?$item_info->description:'')
	);?>
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
</fieldset>
<?php
echo form_close();
?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	

	$('#item_form').validate({
		submitHandler:function(form)
		{
		/*
			make sure the hidden field #item_number gets set
			to the visible scan_item_number value
			*/
		
			$('#item_number').val($('#scan_item_number').val());
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_item_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			name:"required",
			description:"required",
			
   		},
		messages:
		{
			name:"<?php echo $this->lang->line('items_category_name_required'); ?>",
			category:"<?php echo $this->lang->line('items_category_required'); ?>",
		

		}
	});
});
</script>