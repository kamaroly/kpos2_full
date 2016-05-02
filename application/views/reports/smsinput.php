<?php
echo form_open('reports/smsreport/');
?>
<div class="field_row clearfix">
<?php echo form_label('Today total Expenses:', 'item',array('class'=>'wide')); ?>
	<div class='form_field'>
		<?php echo form_input(array(
			'name'=>'expenses',
			'id'=>'expenses'
		));?>
	</div>
</div>
<input type="submit" class="btn btn-success" value="send SMS"/>
<?php echo form_close();?>