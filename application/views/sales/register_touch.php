<?php $this->load->view("partial/header"); ?>

<div id="title_bar"><?php echo $this->lang->line('sales_register'); ?></div>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}

if (isset($warning))
{
	echo "<div class='warning_mesage'>".$warning."</div>";
}

if (isset($success))
{
	echo "<div class='success_message'>".$success."</div>";
}
?>
<div id="register_wrapper">
<?php echo form_open("sales/change_mode",array('id'=>'mode_form')); 


?>
	<span><?php echo $this->lang->line('sales_mode') ?></span>
	

<?php echo form_dropdown('mode',$modes,$mode,'onchange="$(\'#mode_form\').submit();"'); ?>

	<span><?php echo $this->lang->line('sales_type') ?></span>
<?php echo form_dropdown('type',$types,$type,'onchange="$(\'#mode_form\').submit();"'); ?>
<div id="show_suspended_sales_button">
	<?php echo anchor("sales/suspended/width:425",
	"<div class='btn btn-small btn-info'><span style='font-size:73%;'>".$this->lang->line('sales_suspended_sales')."</span></div>",
	array('class'=>'thickbox none','title'=>$this->lang->line('sales_suspended_sales')));
	?>
</div>
</form>
<?php echo form_open("sales/add",array('id'=>'add_item_form')); ?>
<label id="item_label" for="item">

<?php
if($mode=='sale')
{
	echo $this->lang->line('sales_find_or_scan_item');
}
else
{
	echo $this->lang->line('sales_find_or_scan_item_or_receipt');
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
<table id="rounded-corner" style="width:100%;">
<thead>
<tr>
<th ><?php echo $this->lang->line('common_delete'); ?></th>
<th ><?php echo $this->lang->line('sales_item_number'); ?></th>
<th><?php echo $this->lang->line('sales_item_name'); ?></th>
<th ><?php echo $this->lang->line('sales_price'); ?></th>
<th><?php echo $this->lang->line('sales_quantity'); ?></th>
<th ><?php echo $this->lang->line('sales_discount'); ?></th>
<th><?php echo $this->lang->line('sales_total'); ?></th>
<th ><?php echo $this->lang->line('sales_edit'); ?></th>
</tr>
</thead>
<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='8'>
<div class='warning_message' style='padding:7px;'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
</tr></tr>
<?php
}
else
{
	foreach(array_reverse($cart, true) as $line=>$item)
	{
		$cur_item_info = $this->Item->get_info($item['item_id']);
		echo form_open("sales/edit_item/$line");
	?>
		<tr>
		<td><?php echo anchor("sales/delete_item/$line",'['.$this->lang->line('common_delete').']');?></td>
		<td><?php echo $item['item_number']; ?></td>
		<td style="align:center;"><?php echo $item['name']; ?><br /> [<?php echo $cur_item_info->quantity; ?> in stock]</td>



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
        	if($item['is_serialized']==1)
        	{
        		echo $item['quantity'];
        		echo form_hidden('quantity',$item['quantity']);
        	}
        	else
        	{
        		echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));
        	}
		?>
		</td>

		<td><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'3'));?></td>
		<td id="reg_item_total">
		
		<?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
		
		<td><?php echo form_submit("edit_item", $this->lang->line('sales_edit_item'),'class=\'btn btn-small btn-success\'');?></td>
		</tr>
		<tr>
		<td style="color:#2F4F4F";><?php echo $this->lang->line('sales_description_abbrv').':';?></td>
		<td colspan=2 style="text-align:left;">

		<?php
        	if($item['allow_alt_description']==1)
        	{
        		echo form_input(array('name'=>'description','value'=>$item['description'],'size'=>'20'));
        	}
        	else
        	{
				if ($item['description']!='')
				{
					echo $item['description'];
        			echo form_hidden('description',$item['description']);
        		}
        		else
        		{
        			echo 'None';
        			echo form_hidden('description','');
        		}
        	}
		?>
		</td>
		<td>&nbsp;</td>
		<td style="color:#2F4F4F";>
		<?php
        	if($item['is_serialized']==1)
        	{
				echo $this->lang->line('sales_serial').':';
			}
		?>
		</td>
		<td colspan=3 style="text-align:left;">
		<?php
        	if($item['is_serialized']==1)
        	{
        		echo form_input(array('name'=>'serialnumber','value'=>$item['serialnumber'],'size'=>'20'));
			}
			else
			{
				echo form_hidden('serialnumber', '');
			}
		?>
		</td>


		</tr>
		<tr style="height:3px">
		<td colspan=8 style="background-color:white"> </td>
		</tr>		</form>
	<?php
	}
}
?>
</tbody>
</table>

<?php
if(count($items)>0):
echo anchor('sales/change_category','Change Category','class="btn btn-small btn-danger"');
foreach ($items as $item):
  $image_properties = array(
	  	'src' => 'images/items/items/'.$item->image,
		'alt' => $item->description,
		'class' => 'post_images',
		'width' => '80',
		'height' => '80',
		'title' => $item->name,
		'rel' => 'lightbox',
);

echo anchor('sales/add/'.$item->item_id,'<DIV>'.img($image_properties).'<BR>'.$item->name.'</DIV>');

endforeach;
endif;
?>

<?php
if(count($categories)>0):
foreach ($categories->result() as $category):
$image_properties = array(
          'src' => 'images/items/categories/category.png',
          'alt' => $category->description,
          'class' => 'post_images',
          'width' => '100',
          'height' => '100',
          'title' => $category->cname,
          'rel' => 'lightbox',
);

echo anchor('sales/items_by_category/'.$category->id,img($image_properties));

endforeach;
endif;

?>
</div>



<div id="overall_sale">
	<?php
	if(isset($customer))
	{
		echo $this->lang->line("sales_customer").': <b>'.$customer. '</b><br />';
		echo anchor("sales/remove_customer",'['.$this->lang->line('common_remove').' '.$this->lang->line('customers_customer').']');
	}
	else
	{
		echo form_open("sales/select_customer",array('id'=>'select_customer_form')); ?>
		<label id="customer_label" for="customer"><?php echo $this->lang->line('sales_select_customer'); ?></label>
		<?php echo form_input(array('name'=>'customer','id'=>'customer','size'=>'17','value'=>$this->lang->line('sales_start_typing_customer_name')));?>
		<div style="float:right;">
		<?php echo $this->lang->line('common_or'); ?>
		<?php echo anchor("customers/view/-1/width:350",
		"<div class='btn btn-small btn-primary' style='margin:0 auto;'><span>".$this->lang->line('sales_new_customer')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_customer')));
		?>
		</div>
		
		</form>
		
		<div class="clearfix">&nbsp;</div>
		<?php
	}
	?>

	<div id='sale_details'>
		<div class="float_left" style="width:55%;"><?php echo $this->lang->line('sales_sub_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($subtotal); ?></div>

		<?php foreach($taxes as $name=>$value) { ?>
		<div class="float_left" style='width:55%;'><?php echo $name; ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($value); ?></div>
		<?php }; ?>
         
		<div class="float_left" style='width:55%;font-size:16px;font-weight:bold;background-color:#ffc002;' >
		<?php echo $this->lang->line('sales_total'); ?>:</div>
		<div class="float_left " id="to_be_paid" style="width:45%;font-size:16px;font-weight:bold;background-color:#ffc002;">
		<?php if ($currency==$this->config->item('exchange_rate_name')):?>
		<?php echo to_currency($total*$this->config->item('exchange_rate')); ?>
		<?php else:?>
		<?php echo to_currency($total); ?>
		<?php endif;?>
		</div>
		
		<div class="float_left btn-inverse disabled" style='width:55%;font-size:16px;font-weight:bold;background-color:#ffc002;' >
		<?php echo $this->lang->line('sales_total_foreign'); ?>:</div>
		<div class="float_left btn-inverse disabled"  style="width:45%;font-size:16px;font-weight:bold;">
		<?php if ($currency==$this->config->item('currency_symbol')):?>
		<?php echo $this->config->item('exchange_rate_name').' '.round(($total/$this->config->item('exchange_rate')),2); ?>
		<?php else:?>
		<?php echo $this->config->item('exchange_rate_name').' '.$total; ?>
		<?php endif;?>
		</div>
	   
	</div>


  

	<?php
	// Only show this part if there are Items already in the sale.
	if(count($cart) > 0)
	{
	?>

    	<div id="Cancel_sale">
		<?php echo form_open("sales/cancel_sale",array('id'=>'cancel_sale_form')); ?>
		<div class='btn btn-small btn-primary' id='cancel_sale_button' style='margin-top:5px;'>
			<span><?php echo $this->lang->line('sales_cancel_sale'); ?></span>
		</div>
		<?php 	echo "<div class='btn btn-small btn-inverse' id='suspend_sale_button' style='float:right;margin-top:5px;'><span>".$this->lang->line('sales_suspend_sale')."</span></div>";
		?>
    	</form>
    	</div>
		


    <table width="100%"><tr>
    <td style="width:55%; ">
    <div class="float_left"><?php echo 'Payments Total:' ?></div></td>
    <td style="width:45%; text-align:right;">
    <div class="float_left " style="text-align:right;font-weight:bold;" 
    id="payment_total">
    <?php echo to_currency($payments_total) ; ?></div>
    </td>
	</tr>
	<tr>
	<td style="width:55%; " id="covered"><div class="float_left" ><?php echo 'Amount Due:' ?></div></td>
	<td style="width:45%; text-align:right; " id="covered">
	<div class="float_left" style="text-align:right;font-weight:bold;"><?php echo to_currency($amount_due); ?></div>
	</td>
	</tr>
	
	</table>

	<div id="Payment_Types" >

		<div  class="table table-striped">
	      <table width="100%" height="100%">
			<tr>
			<td><?php echo $this->lang->line('sales_payment_currency').':   ';?>
			
			</td>
			<td>
			<?php echo form_open("sales/change_currency",array('id'=>'currency_form')); ?>
			
				<?php echo form_dropdown( 'payment_currency',
						                   $payment_currency, 
						                   $currency,
						                  'id="payment_currency" style="float:right;"
						                   onchange="$(\'#currency_form\').submit();"' ); ?>
		    <?php echo form_close();?>
			</td>
			</tr>
			</table>
			<?php echo form_open("sales/add_payment",array('id'=>'add_payment_form',
					                                       'style'=>'padding:2px;')); ?>
					                                       
		    <table width="100%" height="100%">
			<tr>
			<td><?php echo $this->lang->line('sales_payment').':   ';?>
			
			</td>
			<td>
				<?php echo form_dropdown( 'payment_type',
						                  $payment_options, '',
						                  'id="payment_types" style="float:right;"' ); ?>
			</td>
			</tr>
			<tr id="client_insurance">
			<td >
			<span id="amount_tendered_label">
			<?php echo $this->lang->line( 'sales_amount_tendered' ).': '; ?></span>
			</td>
			<td >
			
			    <?php echo form_dropdown( 'insurance_type', 
			    		                  $insurance_options,
			    		                  array(), 'id="insurance_type" style="float:right;"'); ?>
				</td>
			</tr>
			<tr id="client_insurance2">
			<td >
			<?php echo $this->lang->line( 'sales_insurance_percentage' ).': '; ?>
			</td>
			<td  >    		    
			<div style='float:right;'>        
	          <?php echo form_input(array(
		                    'name'=>'contribution',
		                    'id'=>'contribution',
                    		'size'=>'2',
		                    'value'=>'100',
	          		       ));?>%
	       </div>
			</td>
			</tr>
			<tr>
			<!--  
			<td>
			<span id="amount_tendered_label"
			style="float:left;"><?php echo $this->lang->line( 'sales_amount_tendered' ).': '; ?></span>
			</td>
			-->
			<td colspan="2">
			<div style='float:right;'>
				<?php echo form_input( array( 'name'=>'amount_tendered',
						                      'id'=>'amount_tendered', 
						                      'value'=>to_currency_no_money($amount_due),
						                      'size'=>'10',
						                      ) );	?>
			</div>
			</td>
			</tr>
			<tr>
			
			<td colspan="2">
			<div class='btn btn-small btn-success' id='add_payment_button' style="width:240px">
				<span><?php echo $this->lang->line('sales_add_payment'); ?></span>
			</div>
			</td>
			</tr>
        	</table>
			
		</div>
		</form>
        	
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
				echo form_open("sales/edit_payment/$payment_id",array('id'=>'edit_payment_form'.$payment_id));
				?>
	            <tr>
	            <td><?php echo anchor( "sales/delete_payment/$payment_id", '['.$this->lang->line('common_delete').']' ); ?></td>

							<td><?php echo $payment['payment_type']; ?></td>
							<td style="text-align:right;"><?php echo to_currency( $payment['payment_amount'] ); ?></td>


				</tr>
				</form>
				<?php
				}
				?>
			</tbody>
			</table>
			<div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
			<?php
		}
		?>
		<?php
		// Only show this part if there is at least one payment entered.
		if(count($payments) > 0):
		?>
			<div id="finish_sale">
				<?php echo form_open("sales/complete",array('id'=>'finish_sale_form')); ?>
				<?php echo form_textarea(array('name'=>'comment', 
						                       'id' => 'comment', 
						                       'value'=>$comment,
						                       'rows'=>'4',
						                       'cols'=>'33',
						                       'placeholder'=>$this->lang->line('common_comments')));?>
				<br /><br />
				
				<?php
				
				if(!empty($customer_email))
				{
					echo $this->lang->line('sales_email_receipt'). ': '. form_checkbox(array(
					    'name'        => 'email_receipt',
					    'id'          => 'email_receipt',
					    'value'       => '1',
					    'checked'     => (boolean)$email_receipt,
					    )).'<br />('.$customer_email.')<br />';
				}
				 
				if ($payments_cover_total)
				{
					echo "<div class='btn btn-large btn-primary' id='finish_sale_button' style='margin-top:5px;width:240px'><span>".$this->lang->line('sales_complete_sale')."</span></div>";
				}
				?>
			</div>
			</form>
		<?php endif;		?>
			
		    <br />
		



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
	
	$("#client_insurance").fadeOut();
	//getting the total number of the 
	//================================
	var text=$("#to_be_paid").text();
	var total_to_be_payed=Number(text.replace(/[^0-9\.]+/g,""));

	$("#contribution").keyup(function()
			{
		      if($("#contribution").val()<100 && $("#contribution").val()>0)
		    		  {
		             $("#amount_tendered").val(($("#contribution").val()/100)*total_to_be_payed);
		             $("#payment_total").html($("#amount_tendered").val()+$("#payment_total").val());
		    		  }
		      else
		      {
			      alert('Contribution can only be between 1 % and  100 %');
		    	  $("#contribution").val(80);
		    	  $("#contribution").focus();
		    	  $("#amount_tendered").val(($("#contribution").val()/100)*total_to_be_payed);
		          $("#payment_total").html($("#amount_tendered").val()+$("#payment_total").val());
		    		
		      }
	});

	
    $("#item").autocomplete('<?php echo site_url("sales/item_search"); ?>',
    {
    	minChars:0,
    	max:100,
    	selectFirst: false,
       	delay:10,
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

	$('#item,#customer').click(function()
    {
    	$(this).attr('value','');
    });

    $("#customer").autocomplete('<?php echo site_url("sales/customer_search"); ?>',
    {
    	minChars:0,
    	delay:10,
    	max:100,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#customer").result(function(event, data, formatted)
    {
		$("#select_customer_form").submit();
    });

    $('#customer').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_customer_name'); ?>");
    });
	
	$('#comment').change(function() 
	{
		$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()});
	});
	
	$('#email_receipt').change(function() 
	{
		$.post('<?php echo site_url("sales/set_email_receipt");?>', {email_receipt: $('#email_receipt').is(':checked') ? '1' : '0'});
	});
	
	
    $("#finish_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("sales_confirm_finish_sale"); ?>'))
    	{
    		$('#finish_sale_form').submit();
    	}
    });

	$("#suspend_sale_button").click(function()
	{
		if (confirm('<?php echo $this->lang->line("sales_confirm_suspend_sale"); ?>'))
    	{
			$('#finish_sale_form').attr('action', '<?php echo site_url("sales/suspend"); ?>');
    		$('#finish_sale_form').submit();
    	}
	});

    $("#cancel_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("sales_confirm_cancel_sale"); ?>'))
    	{
    		$('#cancel_sale_form').submit();
    	}
    });

	$("#add_payment_button").click(function()
	{
	   $('#add_payment_form').submit();
    });

	$("#payment_types").change(checkPaymentTypeGiftcard).ready(checkPaymentTypeGiftcard)
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
		$("#customer").attr("value",response.person_id);
		$("#select_customer_form").submit();
	}
}

function checkPaymentTypeGiftcard()
{
	var text=$("#to_be_paid").text();
	var total_to_be_payed=Number(text.replace(/[^0-9\.]+/g,""));
	
	if ($("#payment_types").val() == "<?php echo $this->lang->line('sales_giftcard'); ?>")
	{
		$("#client_insurance").fadeOut();
		$("#client_insurance2").fadeOut();
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_giftcard_number'); ?>");
		$("#amount_tendered").val('');
		$("#amount_tendered").focus();
	}
	if ($("#payment_types").val() == "<?php echo $this->lang->line('sales_insurance'); ?>")
	{
		$("#client_insurance").fadeIn();
		$("#client_insurance2").fadeIn();
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_select_insurance'); ?>");
		$("#amount_tendered").attr("class","btn btn-small btn-primary disabled");
		
		$("#amount_tendered").val(($("#contribution").val()/100)*total_to_be_payed);
        $("#payment_total").html($("#amount_tendered").val()+$("#payment_total").val());
		$("#contribution").focus();
	}
	else
	{
		$("#client_insurance").fadeOut();
		$("#client_insurance2").fadeOut();
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_amount_tendered'); ?>");		
	}
}

</script>
