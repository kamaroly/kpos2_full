<div id="required_fields_message"><?php echo $this->lang->line('items_edit_fields_you_want_to_update'); ?></div>
<ul id="error_message_box"></ul>
<?php
echo form_open('items/bulk_update/',array('id'=>'item_form'));
?>
<fieldset id="item_basic_info">
<legend><?php echo $this->lang->line("items_basic_information"); ?></legend>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_name').':', 'name',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'name',
		'id'=>'name')
	);?>
	</div>
</div>


<div class="field_row clearfix">
<?php echo form_label($this->lang->line('items_category').':', 'category',array('class'=>'required wide')); ?>

<?php $categories_items=array(0=>'Select categories',);?>
<?php foreach ($items_categories as $catagory):?>
<?php $categories_items[$catagory->id]=$catagory->cname;?>
<?php endforeach;?>
	<div class='form_field'>
	<?php echo form_dropdown('category_id', $categories_items);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_supplier').':', 'supplier',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('supplier_id', $suppliers, '');?>
	</div>
</div>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_cost_price').':', 'cost_price',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'cost_price',
		'size'=>'8',
		'id'=>'cost_price')
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_unit_price').':', 'unit_price',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'unit_price',
		'size'=>'8',
		'id'=>'unit_price')
	);?>
	</div>
</div>


<div class="field_row clearfix">
<?php echo form_label($this->lang->line('items_vat_type').':', 'vat_type',array('class'=>'required wide')); ?>
<div class='form_field'>
<?php echo form_dropdown('vat_type',array('V1'=>'V1',
		                                  'V2'=>'V2',
		                                  'V3'=>'V3',
		                                  'V4'=>'V4',
		                                  'V5'=>'V5')
		                   );
?>
</div>		                                  
</div>


<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_tax_1').':', 'tax_percent_1',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tax_names[]',
		'id'=>'tax_name_1',
		'size'=>'8',
		'value'=> isset($item_tax_info[0]['name']) ? $item_tax_info[0]['name'] : $this->lang->line('items_sales_tax'))
	);?>
	</div>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tax_percents[]',
		'id'=>'tax_percent_name_1',
		'size'=>'3',
		'value'=> isset($item_tax_info[0]['percent']) ? $item_tax_info[0]['percent'] : '')
	);?>
	%
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_tax_2').':', 'tax_percent_2',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tax_names[]',
		'id'=>'tax_name_2',
		'size'=>'8',
		'value'=> isset($item_tax_info[1]['name']) ? $item_tax_info[1]['name'] : '')
	);?>
	</div>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tax_percents[]',
		'id'=>'tax_percent_name_2',
		'size'=>'3',
		'value'=> isset($item_tax_info[1]['percent']) ? $item_tax_info[1]['percent'] : '')
	);?>
	%
	</div>
</div>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_reorder_level').':', 'reorder_level',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'reorder_level',
		'id'=>'reorder_level')
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_location').':', 'location',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'location',
		'id'=>'location')
	);?>
	</div>
</div>
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_acc_name').':', 'location',array('class'=>'wide')); ?>
	<div class='form_field'>
	<select   name="acc_id" <?=(isset($table['gotchild']) && $table['gotchild'] == 1) ? 'DISABLED' : '';?> onchange="changetfrom();">
            	<?php  foreach($accounts['asset'] as $v):?>
					<option value="<?=$v['cid'];?>" 
					<?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Account > <?=$v['acc_name'];?>  > <?=$v['cname'];?>
					</option>
				<?php   endforeach;?>

				
				<?php  foreach($accounts['liability'] as $v):?>
					<option value="<?=$v['cid'];?>"
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
					<option value="<?=$v['cid'];?>"
					<?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Budget > <?=$v['cname'];?>
					</option>
				<?php  endif; ?>
				<?php   endforeach;?>
			</select>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('items_description').':', 'description',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'description',
		'id'=>'description',
		'rows'=>'5',
		'cols'=>'17')		
	);?>
	</div>
</div>

<div class="field_row clearfix">

<?php echo form_label($this->lang->line('items_allow_alt_desciption').':', 'allow_alt_description',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('allow_alt_description', $allow_alt_desciption_choices);?>

	</div>

</div>



<div class="field_row clearfix">

<?php echo form_label($this->lang->line('items_is_serialized').':', 'is_serialized',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('is_serialized', $serialization_choices);?>

	</div>

</div>

<?php
echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>$this->lang->line('common_submit'),
	'class'=>'submit_button float_right')
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
	$("#category").autocomplete("<?php echo site_url('items/suggest_category');?>",{max:100,minChars:0,delay:10});
    $("#category").result(function(event, data, formatted)
    {
    });
	$("#category").search();
	
	$('#item_form').validate({
		submitHandler:function(form)
		{
			if(confirm("<?php echo $this->lang->line('items_confirm_bulk_edit') ?>"))
			{
				//Get the selected ids and create hidden fields to send with ajax submit.
				var selected_item_ids=get_selected_values();
				for(k=0;k<selected_item_ids.length;k++)
				{
					$(form).append("<input type='hidden' name='item_ids[]' value='"+selected_item_ids[k]+"' />");
				}
				
				
				$(form).ajaxSubmit({
				success:function(response)
				{
					tb_remove();
					post_bulk_form_submit(response);
				},
				dataType:'json'
				});
			}

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			unit_price:
			{
				number:true
			},
			tax_percent:
			{
				number:true
			},
			quantity:
			{
				number:true
			},
			reorder_level:
			{
				number:true
			}
   		},
		messages: 
		{
			unit_price:
			{
				number:"<?php echo $this->lang->line('items_unit_price_number'); ?>"
			},
			tax_percent:
			{
				number:"<?php echo $this->lang->line('items_tax_percent_number'); ?>"
			},
			quantity:
			{
				number:"<?php echo $this->lang->line('items_quantity_number'); ?>"
			},
			reorder_level:
			{
				number:"<?php echo $this->lang->line('items_reorder_level_number'); ?>"
			}

		}
	});
});
</script>