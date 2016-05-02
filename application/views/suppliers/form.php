<?php
echo form_open('suppliers/save/'.$person_info->person_id,array('id'=>'supplier_form'));
?>
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="supplier_basic_info">
<legend><?php echo $this->lang->line("suppliers_basic_information"); ?></legend>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('suppliers_company_name').':', 'company_name', array('class'=>'required')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'company_name',
		'id'=>'company_name_input',
		'value'=>$person_info->company_name)
	);?>
	</div>
</div>
<!--
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_acc_name').':', 'location',array('class'=>'wide')); ?>
	<div class='form_field'>
	<select   name="acc_id" <?=(isset($table['gotchild']) && $table['gotchild'] == 1) ? 'DISABLED' : '';?> onchange="changetfrom();">
            	<?php  foreach($accounts['asset'] as $v):?>
					<option value="<?=$v['cid'];?>" <?=($v['cid']==$person_info->acc_id)?'selected':'';?>
					<?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Account > <?=$v['acc_name'];?>  > <?=$v['cname'];?>
					</option>
				<?php   endforeach;?>

				
				<?php  foreach($accounts['liability'] as $v):?>
					<option value="<?=$v['cid'];?>" <?=($v['cid']==$person_info->acc_id)?'selected':'';?>
					 <?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Account >  <?=$v['cname'];?>
					</option>
				<?php   endforeach;?>

            	
				<?php  $hold = 'no'; ?> 
				<?php  foreach($tree_table as $v):?>
				<?php  
					if (isset($table['cid']) && $table['cid'] == $v['cid'] ) {
						$cur_lvl = $v['lvl'];
						$hold = 'yes';
					}
					elseif ($hold == 'yes') {
						$hold = ($cur_lvl >= $v['lvl']) ? 'no' : 'yes';
					} 
				?>
				<?php  if (!isset($table['cid'])) $table['cid'] = 'dummy'; ?>  
				<?php  if ($table['cid'] != $v['cid'] && $hold != 'yes' && $v['lvl'] != 1) : ?>
					<option value="<?=$v['cid'];?>"<?=($v['cid']==$person_info->acc_id)?'selected':'';?>
					<?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Budget > <?=$v['cname'];?>
					</option>
				<?php  endif; ?>
				<?php   endforeach;?>
			</select>
	</div>
</div>
-->
<?php $this->load->view("people/form_basic_info"); ?>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('suppliers_account_number').':', 'account_number'); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'account_number',
		'id'=>'account_number',
		'value'=>$person_info->account_number)
	);?>
	</div>
</div>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('suppliers_tin_number').':', 'tin_number'); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tin_number',
		'id'=>'tin_number',
		'value'=>$person_info->tin?$person_info->tin:'')
	);?>
	</div>
</div>
<?php
echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>$this->lang->line('common_submit'),
    'class'=>'btn btn-small btn-primary'
	)
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
	$('#supplier_form').validate({
		submitHandler:function(form)
		{
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_person_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			company_name: "required",
			first_name: "required",
			last_name: "required",
    		email: "email"
   		},
		messages: 
		{
     		company_name: "<?php echo $this->lang->line('suppliers_company_name_required'); ?>",
     		last_name: "<?php echo $this->lang->line('common_last_name_required'); ?>",
     		email: "<?php echo $this->lang->line('common_email_invalid_format'); ?>"
		}
	});
});
</script>