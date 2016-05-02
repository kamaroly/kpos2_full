<p class="error" id="emailSuccess" style="display: none;"><?php echo $this->lang->line('invoice_email_success');?></p>

	<?php echo form_open('invoices/mail/' . $sale_number, array('id' => 'emailInvoice', 'name' => 'emailInvoice'), array('invoice_number'=>$sale_number, 'isAjax'=>'false'));?>
	<?php if ($clientContacts->num_rows() == 0):?>

				<p><?php echo $this->lang->line('invoice_email_invoice_to') . ' ' . anchor('clients', $this->lang->line('menu_clients'));?></p>

			<?php else:?>

			<fieldset id="recipients">
				<p>
		<table class="table table-bordered table-striped invoice_items stripe">
		<thead>
		<tr>
		<th ><?php echo $this->lang->line('clients_first_name');?></th>
			<th><?php echo 'Sent';?></th>
			</tr>
			</thead>
			<tbody>
					<?php foreach($clientContacts->result() as $contactRow): ?>
					<tr><td>
				       <label style="color:#000000;text-shadow:none;"><?php echo form_label($contactRow->first_name . ' ' . $contactRow->last_name);?>
				      </label>
				       </td><td>
				       <input name="recipients[]" id="<?php echo 'recipient' . $contactRow->id;?>" type="checkbox"
				        value="<?php echo $contactRow->id;?>" />
				        </td>
				        </tr>
				     
					<?php endforeach;?>
					</tbody>
					</table>
					<label  style="color:#000000;text-shadow:none;">
					<input name="primary_contact" style="padding:10px;" type="checkbox" value="y" /><em>
					<?php echo '   '. $this->lang->line('invoice_blind_copy_me')?></em></label>
				</p>
			</fieldset>

			<div id="emailHolder">

				<p>
					<label style="color:#000000;text-shadow:none;">
						<?php echo $this->lang->line('invoice_email_message');?><br />
						<textarea name="email_body" type="text" id="email_body" cols="48" rows="7"></textarea>
					</label>
				</p>

				<p>
					<span class="error" id="emailError"></span> 
					<input type="submit" name="sendEmail" id="sendEmail" class="btn btn-small btn-success"
					 value="<?php echo $this->lang->line('menu_email_invoice');?>" /> 
					<input onclick="Effect.BlindUp('emailInvoice', {duration: '0.4'});"
						 type="reset" value="<?php echo $this->lang->line('actions_cancel');?>"
						 class="btn btn-small btn-inverse"
						  name="close" id="close" />
				</p>

			</div>
			<?php endif; ?>

	<?php echo form_close();?>