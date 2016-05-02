<?php $this->load->view("partial/header"); ?>

<div id="title_bar"><?php echo $this->lang->line('recvs_register'); ?></div>

<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>



    <div id="register_wrapper">
    <?php echo form_open("receivings/change_mode",array('id'=>'mode_form')); ?>
		<span><?php echo $this->lang->line('recvs_mode') ?></span>
	<?php echo form_dropdown('mode',$modes,$mode,'onchange="$(\'#mode_form\').submit();"'); ?>
	</form>
	
	<?php echo form_open("receivings/add",array('id'=>'add_item_form')); ?>
	<label id="item_label" for="item">

	<?php
	if($mode=='receive')
	{
		echo $this->lang->line('recvs_find_or_scan_item');
	}
	else
	{
		echo $this->lang->line('recvs_find_or_scan_item_or_receipt');
	}
	?>
	</label>
	
<?php echo form_input(array('name'=>'item','id'=>'item','size'=>'40'));?>
<div id="new_item_button_register" >
		<?php echo anchor("items/view/-1/width:360",
		"<div class='btn btn-small btn-primary'><span>".$this->lang->line('sales_new_item')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_item')));
		?>
	</div>

</form>

<!-- Receiving Items List -->

<table id="rounded-corner" style="width:100%;">
<thead>
<tr>
<th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>

<th style="width:30%;"><?php echo $this->lang->line('recvs_item_name'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('recvs_cost'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('recvs_quantity'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('recvs_discount'); ?></th>
<th style="width:15%;"><?php echo $this->lang->line('recvs_total'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('recvs_edit'); ?></th>
</tr>
</thead>
<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='7'>
<div class='warning_message' style='padding:7px;'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
</tr></tr>
<?php
}
else
{
	foreach(array_reverse($cart, true) as $line=>$item)
	{
		echo form_open("receivings/edit_item/$line");
	?>
		<tr>
		<td><?php echo anchor("receivings/delete_item/$line",'['.$this->lang->line('common_delete').']');?></td>

		<td style="align:center;"><?php echo $item['name']; ?><br />

		<?php
			echo $item['description'];
      		echo form_hidden('description',$item['description']);
		?>
		<br />


		<?php if ($items_module_allowed)
		{
		?>
			<td><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6'));?></td>
		<?php
		}
		else
		{
		?>
			<td><?php echo $item['price']; ?></td>
			<?php echo form_hidden('price',$item['price']); ?>
		<?php
		}
		?>
		<td>
		<?php
        	echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));
		?>
		</td>


		<td><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'3'));?></td>
		<td><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
		<td><?php echo form_submit("edit_item", $this->lang->line('sales_edit_item'),"class='btn btn-small btn-primary small'");?></td>
		</tr>
		</form>
	<?php
	}
}
?>
</tbody>
</table>
</div>

<!-- Overall Receiving -->

<div id="overall_sale">
	<?php
	if(isset($supplier))
	{
		echo $this->lang->line("recvs_supplier").': <b>'.$supplier. '</b><br />';
		echo anchor("receivings/delete_supplier",'['.$this->lang->line('common_delete').' '.$this->lang->line('suppliers_supplier').']');
	}
	else
	{
		echo form_open("receivings/select_supplier",array('id'=>'select_supplier_form')); ?>
		<label id="supplier_label" for="supplier"><?php echo $this->lang->line('recvs_select_supplier'); ?></label>
		<?php echo form_input(array('name'=>'supplier',
				                    'id'=>'supplier',
				                    'size'=>'17',
				                    'style'=>'float:left;',
				                    'value'=>$this->lang->line('recvs_start_typing_supplier_name')));?>
		
		<div style="text-align:center;font-size:10px;float:left;"><?php echo $this->lang->line('common_or'); ?>
		<?php echo anchor("suppliers/view/-1/width:350",
		"<div class='btn btn-small btn-primary' style='margin:0 auto;'>".$this->lang->line('recvs_new_supplier')."</div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('recvs_new_supplier')));
		?>
		</div>
		</form>
		<div class="clearfix">&nbsp;</div>
		<?php
	}
	?>

	<div id='sale_details'>
		<div class="float_left"style='width:55%;font-size:16px;font-weight:bold;background-color:#ffc002;' ><?php echo $this->lang->line('sales_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-size:16px;font-weight:bold;background-color:#ffc002;"><?php echo to_currency($total); ?></div>
	
	   
	    <div class="float_left"style='width:55%;font-size:16px;font-weight:bold;background-color:#45FFFF;' ><?php echo $this->lang->line('sales_change_due'); ?>:</div>
		<div class="float_left" style="width:45%;font-size:16px;font-weight:bold;background-color:#45FFFF;"><?php echo to_currency($amount_due); ?></div>
	
	</div>
	
	<div>
 
    <?php 
    if(isset($receipt_tax)):
  
    	echo '<br /> <br />';
    	echo anchor("receivings/delete_tax",'['.$this->lang->line('common_delete').']');
        echo $this->lang->line("recvs_tax").': <b>'.$receipt_tax. '</b><br />';
   else:
    ?>
	<?php echo form_open("receivings/add_tax",array('id'=>'add_tax_form')); ?>
	
	<?php echo form_input(array('name'=>'receipt_tax',
				                    'id'=>'receipt_tax',
				                    'size'=>'17',
				                    'style'=>'float:left;',
				                    'placeholder'=>$this->lang->line('recvs_add_receipt_tax')));?>

	<!-- INSERT THE SPACE -->
	&nbsp;
	&nbsp;
	&nbsp;
	<!-- END OF SPACE -->
	<button class='btn btn-small btn-primary' id='add_tax_button' style=""> 
	   <?php echo $this->lang->line('recvs_add_receipt_tax');?>
	</button>
	
	<?php echo form_close();?>
	
	<?php endif;?>
	</div>
	
	<?php
	if(count($cart) > 0)
	{
	?>
	
	
	<div id="finish_sale">
		<?php echo form_open("receivings/complete",array('id'=>'finish_sale_form')); ?>
		<?php 
		
		if ($amount_due<=0)
		{
			echo "<div class='btn btn-large btn-success' id='finish_sale_button' style='margin:2px;width:240px'><span>".$this->lang->line('recvs_complete_receiving')."</span></div>";
		}
		?>
		<br />
		
		<?php echo form_textarea(array('name'=>'comment',
				                       'value'=>'',
				                       'rows'=>'3',
				                       'placeholder'=>$this->lang->line('common_comments'),
				                       'cols'=>'33'));?>
		<br />
		<?php echo form_close();?>
		
		<?php echo form_open("receivings/add_payment",array('id'=>'add_payment_form',
					                                       'style'=>'padding:2px;')); ?>
		<table width="100%">
		<tr><td>
		<?php	echo $this->lang->line('sales_payment').':   ';?>
		</td><td>
		<?php  echo form_dropdown('payment_type',$payment_options);?>
        </td>
        </tr>

        <tr> <td>
        <?php echo $this->lang->line('sales_amount_tendered').':   ';?>
		</td><td>
		<?php echo form_input(array('name'=>'amount_tendered',
				                    'value'=>to_currency_no_money($amount_due),
				                    'size'=>'10',
				                    'Style'=>'text-align:center',
										                      ) );	?>
        </td>  </tr>
        <tr>
        <td colspan=2>
        <div class='btn btn-small btn-primary' id='add_payment_button' style="width:240px">
				<span><?php echo $this->lang->line('sales_add_payment'); ?></span>
			</div>
			</td>
			</tr>
       </table>
        <br />
		<?php
		// Only show this part if there is at least one payment entered.
		if(count($payments) > 0)
		{
		?>
	    	<table id="register" >
	    	<thead>
			<tr>
			<th style="width:11%;color:#333333;" ><?php echo $this->lang->line('common_delete'); ?></th>
			<th style="width:60%;color:#333333;"><?php echo 'Type'; ?></th>
			<th style="width:18%;color:#333333;"><?php echo 'Amount'; ?></th>


			</tr>
			</thead>
			<tbody id="payment_contents">
			<?php
				foreach($payments as $payment_id=>$payment)
				{
				echo form_open("receivings/edit_payment/$payment_id",array('id'=>'edit_payment_form'.$payment_id));
				?>
	            <tr>
	            <td><?php echo anchor( "receivings/delete_payment/$payment_id", '['.$this->lang->line('common_delete').']' ); ?></td>

							<td><?php echo $payment['payment_type']; ?></td>
							<td style="text-align:right;"><?php echo to_currency( $payment['payment_amount'] ); ?></td>


				</tr>
				</form>
				<?php
				}
				?>
			</tbody>
			</table>
		
		<?php
		}
		?>
		<?php echo form_close()?>
		</div>

		

	    <?php echo form_open("receivings/cancel_receiving",array('id'=>'cancel_sale_form')); ?>
			    <div class='btn btn-small btn-inverse' id='cancel_sale_button' style="width:240px">
					<span>Cancel </span>
				</div>
        <?php echo form_close();?>
	</div>
	<?php
	}
	?>

</div>
<div class="clearfix" style="margin-bottom:30px;">&nbsp;</div>


<?php $this->load->view("partial/footer"); ?>


<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$("#add_payment_button").click(function()
			{
			   $('#add_payment_form').submit();
		    });

	$("#add_tax_button").click(function()
			{
			   $('#add_tax_form').submit();
		    });
    
    $("#item").autocomplete('<?php echo site_url("receivings/item_search"); ?>',
    {
    	minChars:0,
    	max:100,
       	delay:10,
       	selectFirst: false,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#item").result(function(event, data, formatted)
    {
		$("#add_item_form").submit();
    });

	$('#item').focus();

	$('#item').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
    });

	$('#item,#supplier').click(function()
    {
    	$(this).attr('value','');
    });

    $("#supplier").autocomplete('<?php echo site_url("receivings/supplier_search"); ?>',
    {
    	minChars:0,
    	delay:10,
    	max:100,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#supplier").result(function(event, data, formatted)
    {
		$("#select_supplier_form").submit();
    });

    $('#supplier').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('recvs_start_typing_supplier_name'); ?>");
    });

    $("#finish_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("recvs_confirm_finish_receiving"); ?>'))
    	{
    		$('#finish_sale_form').submit();
    	}
    });

    $("#cancel_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("recvs_confirm_cancel_receiving"); ?>'))
    	{
    		$('#cancel_sale_form').submit();
    	}
    });


});

function post_item_form_submit(response)
{
	if(response.success)
	{
		$("#item").attr("value",response.item_id);
		$("#add_item_form").submit();
	}
}

function post_person_form_submit(response)
{
	if(response.success)
	{
		$("#supplier").attr("value",response.person_id);
		$("#select_supplier_form").submit();
	}
}

</script>