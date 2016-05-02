<table class="table table-bordered table-striped">
	<tr>
		<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_suspended_sale_id'); ?></th>
		<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_date'); ?></th>
		<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_customer'); ?></th>
		<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_comments'); ?></th>
		<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_unsuspend_and_delete'); ?></th>
	</tr>
	
	<?php
	foreach ($suspended_sales as $suspended_sale)
	{
	?>
		<tr>
			<td><?php echo $suspended_sale['sale_id'];?></td>
			<td><?php echo date('m/d/Y',strtotime($suspended_sale['sale_time']));?></td>
			<td>
				<?php
				if (isset($suspended_sale['customer_id']))
				{
					$customer = $this->Customer->get_info($suspended_sale['customer_id']);
					echo $customer->first_name. ' '. $customer->last_name;
				}
				else
				{
				?>
					&nbsp;
				<?php
				}
				?>
			</td>
			<td><?php echo $suspended_sale['comment'];?></td>
			<td>
			<?php echo anchor('invoices/opendrafts/'.$suspended_sale['sale_id'],'Continue working',array('class'=>'btn btn-small btn-primary'));?>
			</td>
		</tr>
	<?php
	}
	
	?>
	
</table>