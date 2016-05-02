
<?php echo form_open('invoices/notes/' . $row->id, array('id'=>'private_note_form'));?>

		
		<p>
			<textarea name="private_note" type="text" id="private_note" cols="50" rows="7"></textarea>
		</p>

		<p>
			<input type="submit" value="<?php echo $this->lang->line('invoice_add_note');?>" class="btn btn-small btn-primary"/> 
			<input onclick="Effect.BlindUp('private_note_form', {duration: '0.4'});" type="reset" 
			value="<?php echo $this->lang->line('actions_cancel');?>" name="close" id="close" class="btn btn-small btn-inverse"/>
		</p>

<?php echo form_close();?>