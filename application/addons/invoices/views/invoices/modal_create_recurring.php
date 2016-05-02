<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	
	$('#recur_start_date').datePicker({ dateFormat: 'dd-mm-yy' });
	$('#recur_end_date').datePicker({ dateFormat: 'dd-mm-yy' });
	
	
});
</script>

<div id="modal_create_recurring" class="modal hide">
	<?php echo form_open(site_url('invoices/ajax/create_recurring')); ?>
	<input type="hidden" name="sale_id" value="<?php echo $sale_id;?>">
			<table class="table table-bordered table-striped invoice_items stripe">
			<caption><h3><?php echo $this->lang->line('create_recurring'); ?></h3></caption>
		<tbody>
		<tr>
			<td><?php echo $this->lang->line('every'); ?>:</td>
			<td>
                    <select name="recur_frequency" id="recur_frequency" style="width: 150px;">
                        <?php foreach ($recur_frequencies as $key=>$lang) { ?>
                        <option value="<?php echo $key; ?>" <?php if ($key == '1M') { ?>selected="selected"<?php } ?>><?php echo $this->lang->line($lang); ?></option>
                        <?php } ?>
                    </select>
		    </td>
         </tr>
           <tr>
			<td><?php echo $this->lang->line('start_date'); ?>:</td>
			<td><input size="16" type="text" name="recur_start_date" id="recur_start_date" readonly></td>
            </tr>
        <tr>
			<td><?php echo $this->lang->line('end_date'); ?>: </td>
			<td><input size="16" type="text" name="recur_end_date" id="recur_end_date" readonly><span class="help-block"><?php echo $this->lang->line('optional'); ?></span></td>
        </tr>
        <tr>
			<td colspan="2">
            
			<input type="submit" class="btn btn-small btn-success" style="float:right;"
			value="<?php echo $this->lang->line('submit'); ?>" />
		</td>
		</tr>
		</tbody>
		</table>

	</form>

</div>