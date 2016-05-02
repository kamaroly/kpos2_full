<p>
	<label for="first_name" class="required"><span><?php echo $this->lang->line('clients_first_name');?>*:</span></label> 
	<?php
		echo form_input(
							array(
								'name'		=> 'first_name',
								'id'		=> 'first_name',
								'value'		=>  set_value('first_name'),
								'size'		=> '25',
								'maxlength'	=> '25'
								)
						);
		echo $this->form_validation->first_name_error;
	?>
</p>

<p>
	<label for="last_name" class="required"><span><?php echo $this->lang->line('clients_last_name');?>*:</span></label> 
	<?php
		echo form_input(
							array(
								'name'		=> 'last_name',
								'id'		=> 'last_name',
								'value'		=>  set_value('last_name'),
								'size'		=> '25',
								'maxlength'	=> '25'
								)
						);
		echo $this->form_validation->last_name_error;
	?>
</p>

<p>
	<label for="email" class="required"><span><?php echo $this->lang->line('clients_email');?>*:</span></label> 
	<?php
		echo form_input(
							array(
								'name'		=> 'email',
								'id'		=> 'email',
								'value'		=>  set_value('email'),
								'size'		=> '25',
								'maxlength'	=> '50'
								)
						);
		echo $this->form_validation->email_error;
	?>
</p>

<p>
	<label for="phone"><span><?php echo $this->lang->line('clients_phone');?>:</span></label> 
	<?php
		echo form_input(
							array(
								'name'		=> 'phone',
								'id'		=> 'phone',
								'value'		=>  set_value('phone'),
								'size'		=> '20',
								'maxlength'	=> '20'
								)
						);
		echo $this->form_validation->phone_error;
	?>
</p>

<p class="required">
	* <?php echo $this->lang->line('actions_required_fields');?>
</p>
