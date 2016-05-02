

<h2><?php echo $page_title;?></h2>
<?php echo validation_errors(); ?>
<?php echo form_open($this->uri->uri_string(), array('id' => 'clientcontact'), array('customer_id'=>$customer_id));?>
<?php echo $this->validation->error_string; ?>

	<?php $this->load->view('clientcontacts/client_contact_add_form');?>

	<p><?php echo form_submit('submit', $this->lang->line('clients_save_client'), 'id="createClient" class="btn btn-small btn-info"');?></p>

<?php echo form_close();?>
