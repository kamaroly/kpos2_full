<?php
Header('Cache-Control: no-cache');
Header('Pragma: no-cache');

?>

<p class="button">
	<?php echo anchor('clients/newclient', $this->lang->line('clients_create_new_client'), array('class'=>'clientnew'));?>
</p>

<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="spacer_200"><?php echo $this->lang->line('clients_name');?></th>
								<th><?php echo $this->lang->line('clients_address1');?></th>
								<th><?php echo $this->lang->line('invoice_tax_status');?></th>
								<th><?php echo $this->lang->line('actions_select_below');?></th>
								</tr>
							</thead>
<?php if (count($all_clients->result())>0) : ?>
						<tbody>
					
		 <?php foreach($all_clients->result() as $client): ?>
		 
		    	<tr>
				<td>  <?php echo anchor( 'clients/viewclient/'.$client->id, $client->name);?></td>
				<td>  <?php echo anchor( 'clients/viewclient/'.$client->id, $client->address1.' '.$client->address2);?></td>
				<td>  <?php echo anchor(  'clients/viewclient/'.$client->id, $client->tax_status);?></td>
              <td>
	          <?php echo anchor('clients/notes/'.$client->id, $this->lang->line('clients_notes'), array('class' => 'client_notes'));?> | 
			  <?php echo anchor('clients/edit/'.$client->id, $this->lang->line('clients_edit_client'), array('class' => 'client_edit'));?> | 
			  <?php echo anchor('clients/delete/'.$client->id, $this->lang->line('clients_delete_client'), array('class'=>'lbOn deleteConfirm client_delete'));?>
		     </td>
      <?php endforeach; ?>

<?php else:?>

<?php endif;?>
</tr>
</tbody>
</table>

<p><?php echo $this->lang->line('clients_you_have');?> 
<?php echo $total_rows;?> <?php echo $this->lang->line('clients_clients_registered');?></p>

<script type="text/javascript">
<!--<![CDATA[
	accorianClientDivs = document.getElementsByClassName('clientInfo');
	for (i=0; i<accorianClientDivs.length; i++) {
		accorianClientDivs[i].style.display = 'none'; // this seems to be the only way to kick IE's butt... setAttribute I miss you...
	}
// ]]> -->
</script>
