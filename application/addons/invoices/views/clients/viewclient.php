<div class="row">
<div class="span5">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5>
											<i class="icon-user"></i>
											<?php echo $this->lang->line('clients_name').' :' . $client->name;?>
										</h5>

												
										</div>
								
									<div class="widget-body">
										<div class="widget-main">
										<p>
			<?php if ($client->address1 != '') {echo '<i class="icon-map-marker"></i>  '.$client->address1;}?>
			<?php if ($client->address2 != '') {echo ', ' . $client->address2;}?>
			<?php if ($client->address1 != '' || $client->address2 != '') {echo '<br />';}?>
			<?php if ($client->city != '') {echo '<i class="icon-map-marker"></i>   '.$client->city;}?>
			<?php if ($client->province != '') {echo ', ' . $client->province;}?>
			<?php if ($client->country != '') {echo ', ' . $client->country;}?>
			<?php if ($client->postal_code != '') {echo ' ' . $client->postal_code;}?>
			<?php if ($client->city != '' || $client->province != '' || $client->country != '' || $client->postal_code != '') {echo '<br />';}?>
			<?php echo anchor((prep_url($client->website)),'<i class="icon-globe"></i> '.prep_url($client->website));?>
		</p>

		<p>
			<?php echo $this->lang->line('invoice_tax_status');?>: 
			<?php echo ($client->tax_status) ? $this->lang->line('invoice_taxable') : $this->lang->line('invoice_not_taxable');?>
		</p>
         <div class="hr hr8 hr-double"></div>
		<p class="client_options">
			<?php echo anchor('clients/notes/'.$client->id,'<i class="icon-pencil"></i>'. $this->lang->line('clients_notes'),
					 array('class' => 'client_notes btn btn-minier btn-primary"'));?> | 
			<?php echo anchor('clients/edit/'.$client->id, '<i class="icon-edit"></i>'.$this->lang->line('clients_edit_client'),
					 array('class' => 'client_edit btn btn-minier btn-primary"'));?> | 
			<?php echo anchor('clients/delete/'.$client->id, '<i class="icon-remove"></i>'.$this->lang->line('clients_delete_client'), 
					array('class'=>'btn btn-minier btn-danger"'));?>
		</p>

		</div>
		</div>
		</div>
	</div>
	
	
<div class="span6">
	<div class="widget-box">
			<div class="widget-header widget-header-flat widget-header-small">
										<h5>
											<i class="icon-envelope-alt"></i>
											<?php echo $this->lang->line('clients_contacts').' of ' . $client->name;?>
										</h5>

													<?php echo anchor('clientcontacts/add/' . $client->id,
															          $this->lang->line('clients_add_contact').
															          '<span style="display:none;"> '.
															           $this->lang->line('clients_to').' '. $client->name.'
															 </span>','class="btn btn-minier btn-primary dropdown-toggle"');?>
												
										</div>
									</div>

									<div class="widget-body">
										<div class="widget-main">
											<div class="clientInfo" id="clientInfo<?php echo $client->id;?>">

		<div class="contactList" id="contactList<?php echo $client->id;?>">

			<?php
			

			if ( ! $clientContactCount)
			{
				echo '<p id="nocontact' . $client->id . '">' . $this->lang->line('clients_no_invoice_listed') . ' ' . $client->name . '</p>';
			}
			else
			{
				?>
				<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th ><?php echo $this->lang->line('clients_first_name');?></th>
								<th><?php echo $this->lang->line('clients_email');?></th>
								<th><?php echo $this->lang->line('clients_phone');?></th>
								<th><?php echo $this->lang->line('actions_select_below');?></th>
								</tr>
							</thead>
						<tbody>
				<?php foreach($clientContacts as $contactRow):?>
				    <tr>
				   <td><?php echo $contactRow->first_name . ' ' . $contactRow->last_name;?></td>
				   <td><?php echo mailto($contactRow->email,$contactRow->email);?></td>
				   <td><?php echo  $contactRow->phone;?></td>
				
				<?php 
					echo '</td><td class="clienteditdelete">';
					echo anchor ('clientcontacts/edit/'.$contactRow->id, $this->lang->line('actions_edit')) . ' | ';
					echo anchor ('clientcontacts/delete/'.$contactRow->id.'/'.$client->id, $this->lang->line('actions_delete'), array('class' => 'ajaxDelContact', 'id' => '_'.$contactRow->id));
					
				
				
				endforeach;
			}
			?>
			</tr>
			</tbody>
			</table>
		</div>
										</div><!--/widget-main-->
									</div><!--/widget-body-->
								</div><!--/widget-box-->
							</div>			

</div>



	
