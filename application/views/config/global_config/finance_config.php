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


	
<?php echo form_open_multipart('config/save/finance',array('id'=>'config_form'));?>
<div id="config_wrapper">
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>



<Div class="field_row clearfix">
     <?php echo form_label($this->lang->line('config_foreign_cash_account').':', 'phone',array('class'=>'wide')); ?>

     <div class="form_field">
     			<select   name="foreign_cash_account" <?=(isset($table['gotchild']) && $table['gotchild'] == 1) ? 'DISABLED' : '';?> onchange="changetfrom();">
            	<?php  foreach($accounts['asset'] as $v):?>
					<option value="<?=$v['cid'];?>" <?=($v['cid']==$this->config->item('foreign_cash_account'))?'selected':'';?>
					<?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Account > <?=$v['acc_name'];?>  > <?=$v['cname'];?>
					</option>
				<?php   endforeach;?>

				
				<?php  foreach($accounts['liability'] as $v):?>
					<option value="<?=$v['cid'];?>" <?=($v['cid']==$this->config->item('foreign_cash_account'))?'selected':'';?>
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
					<option value="<?=$v['cid'];?>" <?=($v['cid']==$this->config->item('foreign_cash_account'))?'selected':'';?>
					<?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Budget > <?=$v['cname'];?>
					</option>
				<?php  endif; ?>
				<?php   endforeach;?>
			</select>
			</div>
</Div>
<Div class="field_row clearfix">
     <?php echo form_label($this->lang->line('config_local_cash_account').':', 'phone',array('class'=>'wide')); ?>

     <div class="form_field">
     			<select  name="local_cash_account" <?=(isset($table['gotchild']) && $table['gotchild'] == 1) ? 'DISABLED' : '';?> onchange="changetfrom();">
            	<?php  foreach($accounts['asset'] as $v):?>
					<option value="<?=$v['cid'];?>" <?=($v['cid']==$this->config->item('local_cash_account'))?'selected':'';?>
					 <?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Account > <?=$v['acc_name'];?>  > <?=$v['cname'];?>
					</option>
				<?php   endforeach;?>

				
				<?php  foreach($accounts['liability'] as $v):?>
					<option value="<?=$v['cid'];?>"  <?=($v['cid']==$this->config->item('local_cash_account'))?'selected':'';?>
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
					<option value="<?=$v['cid'];?>"  <?=($v['cid']==$this->config->item('local_cash_account'))?'selected':'';?>
					   <?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Budget > <?=$v['cname'];?>
					</option>
				<?php  endif; ?>
				<?php   endforeach;?>
			</select>
			</div>
</Div>
<div class="field_row clearfix">
<?php echo form_label($this->lang->line('config_insurance_parent_acccount').':', 'default_tax_1_rate',array('class'=>'wide')); ?>
   <div class="form_field">
    	<select name="insurance_parent_account">
				<?php   foreach($acc_type as $v):?>
					<option value="<?=$v['acc_id'];?>" <?php echo ($v['acc_id']==$this->config->item('insurance_parent_account'))?'Selected':'';?>
					<?php  if(isset($v['acc_id']) && isset($table['cparent']) && $v['acc_id'] == $table['cparent']) echo "SELECTED";?>> <?=$v['acc_name'];?></option>
				<?php   endforeach;?>
		</select>
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
