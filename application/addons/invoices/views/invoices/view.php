
<hr />
    <div class="row">
    <div class="span4">
	<h3><?php echo isset($customer->last_name)?$customer->last_name:''.' '.isset($customer->first_name)?$customer->first_name:'';?></h3>

	<p>
		<?php if ($customer->address_1 != '') {echo $customer->address_1;}?>
		
		<?php if ($customer->address_2 != '') {echo ', ' . $customer->address_2;}?>
		<?php if ($customer->address_1 != '' || $customer->address_2 != '') {echo '<br />';}?>
		<?php if ($customer->city != '') {$customer->city;}?>
	
		<?php if ($customer->state != '') {if ($customer->city != '') {echo ', ';} echo $customer->state;}?>
		<?php if ($customer->country != '') {if ($customer->zip != '' || ($customer->state == '' && $customer->city != '')){echo ', ';} echo $customer->country;}?>
		<?php if ($customer->zip != '') {echo ' ' . $customer->zip;}?>
		<?php if ($customer->city != '' || $customer->state != '' || $customer->country != '' || $customer->zip != '') {echo '<br />';}?>
				
	</p>
	</div>
	<div class="span3 ">
	<h2>
	<p>
		<strong>
			<?php echo $this->lang->line('invoice_invoice');?> <?php echo $sale_id;?><br />
			<?php echo $date_invoice_issued;?>
		</strong>
	
	</p>
	</h2>
	</div>
	<div class="span3 " style="float:right">
	<div class="invoiceViewHold">
	<div id="companyDetails">
	<?php
$image_properties = array(
		'src' => 'images/company_logo/'.$this->config->item('company_logo'),
		'alt' => 'KPharmacy',
		'class' => 'img-polaroid',
		'width' => '200',
		'height' => '100',
		'title' => 'That was quite a night',
		'rel' => 'lightbox',
);
echo img($image_properties);?>
		<h3><?php echo $this->config->item('company');?>	</h3>
		
		<p>
			<?php if ($this->config->item('address') != '') {echo $this->config->item('address');}?>
			<br/>
			<?php if ($this->config->item('address')!= '') {echo ', ' . $this->config->item('address');}?>
			<br/>
			<?php if ($this->config->item('address') != '') {echo ' ' . $this->config->item('address');}?>
			<br/>
			<?php echo auto_link(prep_url($this->config->item('website')));?>
			
		</p>

	</div>
	</div>
	

	<p><?php echo $this->lang->line('invoice_status') . ': ' . $status;?></p>
	</div>
   </div>
	<table class="table table-bordered table-striped invoice_items stripe">
		<tr>
			<th><?php echo $this->lang->line('invoice_quantity');?></th>
			<th><?php echo $this->lang->line('invoice_work_description');?></th>
			<th><?php echo $this->lang->line('invoice_amount_item');?></th>
			<th><?php echo $this->lang->line('invoice_total');?></th>
		</tr>
		<?php foreach (array_reverse($cart, true) as $line=>$item):?>
		<tr>
			<td><p><?php echo str_replace('.00', '', $item['quantity']);?></p></td>
			<td><?php echo auto_typography($item['name']);?></td>
			<td><p><?php echo to_currency($item['price']); ?></p></td>
			 
			<td><p><?php echo to_currency(round($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)); ?></p></td>
		</tr>
		<?php endforeach;?>
	</table>
	<p>
		<?php echo $subtotal;?>
		<?php foreach($taxes as $name=>$value) { ?>
		<tr>
			<td colspan="4" style='text-align:right;'><?php echo $name; ?>:</td>
			<td colspan="2" style='text-align:right;'><?php echo to_currency(round($value)); ?></td>
		</tr>
	<?php }; ?>
		<p><?php echo $total;?></p>
		<p><?php echo $total_paid;?></p>
		<p><?php echo $total_outstanding;?></p>
		
<?php exit;?>
	</p>

	<p>
		<strong><?php echo $this->lang->line('invoice_payment_term');?>: <?php echo $this->settings_model->get_setting('days_payment_due');?> <?php echo $this->lang->line('date_days');?></strong> 
		(<?php echo $date_invoice_due;?>)
	</p>

	<?php if ($companyInfo->tax_code != ''):?>
	<p><?php echo $companyInfo->tax_code;?></p>
	<?php endif;?>

	<p><?php echo auto_typography($row->invoice_note);?></p>

	<div class="well work_description" id="invoicework_description">

		<h4><?php echo $this->lang->line('invoice_history_comments');?></h4>

		<?php if ($invoiceHistory->num_rows() == 0):?>

			<ul>
				<li><?php echo $this->lang->line('invoice_history_comments');?></li>
			</ul>

		<?php else:
			foreach($invoiceHistory->result() as $row): ?>
				<div style="clear:left; margin: 10px 0;">

					<p class="dateHolder"><?php echo formatted_invoice_date($row->date_sent);?></p>

					<?php if ($row->contact_type == 2): ?>
						<div class="comment"><p class="commentintro"><?php echo $this->lang->line('invoice_comment');?></p><p><?php echo auto_typography(html_entity_decode(str_replace('\n', "\n", $row->email_body)));?></p></div>
						<?php else: ?>
						<div class="comment"><p class="commentintro"><?php echo $this->lang->line('invoice_sent_to');?> <?php echo implode(", ", unserialize($row->clientcontacts_id));?></p><?php echo auto_typography(str_replace('\n', "\n", $row->email_body));?></p></div>
					<?php endif; ?>

				</div>
		<?php 
			endforeach;
		endif; // ends if ($invoiceHistory->num_rows() ==0)
		?>

		<h4><?php echo $this->lang->line('invoice_payment_history');?></h4>

		<ul id="invoiceHistory">
			<?php
			if ($paymentHistory->num_rows() == 0)
			{
				echo "<li>" . $this->lang->line('invoice_no_payments_entered') . "</li>\n";
			}
			else
			{
				foreach($paymentHistory->result() as $row): ?>
				<li>
					<?php
					// localized month
					echo $this->lang->line('cal_' . strtolower(date('F', mysql_to_unix($row->date_paid))));
					// day and year numbers
					echo date(' j, Y', mysql_to_unix($row->date_paid));
					?> : <?php echo $this->settings_model->get_setting('currency_symbol') . $row->amount_paid;?>. <em>&quot;<?php echo ($row->payment_note=="0")?'There was no payment note entered':$row->payment_note;?>&quot;</em>
				</li>
			<?php
				endforeach;
			} // ends if ($invoiceHistory->num_rows() ==0)
			?>
		</ul>

	</div>
	