<style>
<!--
#overall_sale{
float:right;
}
-->
</style>
<?php
if(isset($error))
{
	
	echo "<div class='alert-danger alert'> <strong>".$error."</strong></div>";
}

if (isset($warning))
{
	echo "<div class='alert-warning alert'> <strong>".$warning."</strong></div>";
}

if (isset($success))
{
	echo "<div class='alert-success alert'> <strong>".$success."</strong></div>";
}
?>

<!--  Start the Client Area -->

	<?php if(isset($customer)):?>
        <div class="control-group" style="float:left;">
               
               <div class="control-label"><strong><?php echo $this->lang->line("sales_customer");?></strong></div>
				<div class="controls">
                <?php echo $customer;?>
		        <?php echo anchor("invoices/sales/remove_customer",'[<img border="0" src="'.base_url().'images/b_drop.png" title="Delete" alt="Delete">]'); ?>
		        </div>
		                  <p><?php if ($client->address_1) { echo $client->address_1 . '<br>'; } ?>
                                <?php if ($client->address_2) { echo $client->address_2 . '<br>'; } ?>
                                <?php if ($client->city) { echo $client->city . ' '; } ?>
                                <?php if ($client->state) { echo $client->state . ' '; } ?>
                                <?php if ($client->zip) { echo $client->zip . '<br>'; } ?>
                                <?php if ($client->phone_number) { ?><abbr>P:</abbr><?php echo $client->phone_number; ?><br><?php } ?>
                            </p>
			</div>
	
	<?php else:?>
	    <div class="control-label" style="float:left;">
		<?php echo form_open("invoices/sales/select_customer",array('id'=>'select_customer_form')); ?>
		<label id="customer_label" for="customer"><?php echo $this->lang->line('sales_select_customer'); ?></label>
		<?php echo form_input(array('name'=>'customer',
				                    'id'=>'customer',
				                    'size'=>'10',
				                    'value'=>$this->lang->line('sales_start_typing_customer_name')));?>
	
		<?php echo $this->lang->line('common_or'); ?>
		<?php echo anchor("customers/view/-1/width:350",
		"<div class='btn btn-small btn-primary' style='margin:0 auto;'><span>".$this->lang->line('sales_new_customer')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_customer')));
		?>
		
		
		<?php echo form_close();?>
		</div>
	<?php endif;?>

<!-- End of the client area -->		
	
	<!-- Start of the invoice informations -->
	<table style="float:right;"><caption></caption>
	  <tbody>
           <tr>
	            <td><Strong>Room Number </Strong></td>
	            <td>
	            
	            <?php $rooms=array('0'=>'Select room');?>
	            
	            <?php for($room=1;$room<=11;$room++):?>
	            <?php $rooms["Room $room"]="Room $room";?>  
	            <?php endfor;?>
	            <?php echo form_dropdown('room_name',$rooms);?>
	            </td>
	      </tr>
	      <tr>
	            <td><Strong>Date of Issue </Strong></td>
	            <td><input type="text"  name="date_issued"  size="30" value="<?php echo ($date_issued!='')?$date_issued:''?>"  id="date_issued" />
	            </td>
	      </tr>
	      <tr>
	            <td><Strong>PO Number </Strong></td>
	            <td><input  type="text" name="po_number" id="po_number" value="<?php echo ($po_number!='')?$po_number:''?>" />
	      </tr>
	  </tbody>
	</table>
		
		
		
	<div class="clearfix">&nbsp;</div>
	
<!-- Start of the invoice informations -->	
	
<?php echo form_open("invoices/sales/add",array('id'=>'add_item_form')); ?>
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

<?php echo form_close();?>

<table id="rounded-corner" style="border:1px;" >
<thead>
<tr>
<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_item_number'); ?></th>
<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_item_name'); ?></th>
<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_price'); ?></th>
<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_quantity'); ?></th>
<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_discount'); ?></th>
<th style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('sales_total'); ?></th>
<th colspan="2" style="background-color: #2b61a9;color: #FFFFFF;"><?php echo $this->lang->line('common_actions'); ?></th>
</tr>
</thead>
<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='8'>
<div class='alert-warning alert' style='padding:7px;'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
</tr></tr>
<?php
}
else
{
	foreach(array_reverse($cart, true) as $line=>$item)
	{
		$cur_item_info = $this->Item->get_info($item['item_id']);
		echo form_open("invoices/sales/edit_item/$line");
	?>
		<tr>
		<td><?php echo $item['item_number']; ?></td>
		<td style="align:center;"><?php echo $item['name']; ?></td>



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
		
		<td>
		<button class="btn btn-small btn-warning"><img border="0" src="<?php echo base_url();?>/images/b_edit.png" title="Edit" alt="Edit"></button>
		</td>
		<td>
	     <?php echo anchor("invoices/sales/delete_item/$line",'<img border="0" src="'.base_url().'images/b_drop.png" class="btn btn-small btn-inverse" title="Delete" alt="Delete">');?>
	    </td>
		</tr>
		<tr>
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



	<div id='sale_details'>
	  <table   style="float:right;">
	    <tr>
	       <td><div class="float_left" style="width:55%;"><Strong><?php echo $this->lang->line('sales_sub_total'); ?> </Strong></div></td>
		   <td><div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($subtotal); ?></div></td>
       </tr>
       
		<?php foreach($taxes as $name=>$value) { ?>
		<tr>
	       <td><div class="float_left" style='width:55%;'><Strong><?php echo $name; ?></Strong></div></td>
		   <td><div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($value); ?></div></td>
		</tr>   
		  <?php }; ?>
        
        <tr style="background-color: #2b61a9;color: #FFFFFF;">
	       <td><div class="float_left" style='width:55%;font-size:16px;' ><Strong><?php echo $this->lang->line('sales_total'); ?></Strong></div></td>
		   <td><div class="float_left " id="to_be_paid" style="width:45%;font-size:14px;text-decoration:underline;"><strong><?php echo to_currency($total); ?></strong></div></td>
        </tr>
      </table>
</div>
     
     





	<?php
	// Only show this part if there are Items already in the sale.
	if(count($cart) > 0):
	?>

    	<div id="Cancel_sale">
		<?php echo form_open("invoices/sales/cancel_sale",array('id'=>'cancel_sale_form')); ?>
    	<?php echo form_close();?>
    	</div>
		


			<div id="finish_sale">
				<?php echo form_open("invoices/sales/complete",array("id"=>"finish_sale_form")); ?>
				
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
				 
			 
				
				?>
		     <div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
				
				<?php echo form_textarea(array('name'=>'comment', 
						                       'id' => 'comment', 
						                       'value'=>$comment,
						                       'rows'=>'3',
						                       'cols'=>'33',
						                       'placeholder'=>$this->lang->line('common_comments')));?>
			</div>
			<?php echo form_close();?>
		<?php endif;?>
		

<div class="clearfix" style="margin-bottom:30px;">&nbsp;</div>
<?php if($invoice_url_key!=null):?>
 <p class="padded"><?php echo $this->lang->line('guest_url'); ?>: <?php echo auto_link(site_url('guest/view/invoice/' .$invoice_url_key)); ?></p>
 <?php endif;?>

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

	
    $("#item").autocomplete('<?php echo site_url("invoices/sales/item_search"); ?>',
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

    $("#customer").autocomplete('<?php echo site_url("invoices/sales/customer_search"); ?>',
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
		$.post('<?php echo site_url("invoices/sales/set_comment");?>', {comment: $('#comment').val()});
	});
	
	$('#email_receipt').change(function() 
	{
		$.post('<?php echo site_url("invoices/sales/set_email_receipt");?>', {email_receipt: $('#email_receipt').is(':checked') ? '1' : '0'});
	});

	/** Saving PO number with ajax **/
	$("#save_po_number").click(function()
			{
		$('#po_number').ajaxSubmit({
			success:function(response)
			{
				if(response.success)
				{
					set_feedback(response.message,'success_message',false);		
				}
				else
				{
					set_feedback(response.message,'error_message',true);		
				}
			},
			dataType:'json'
		});
			});
   /**End of the saving PO Number **/
	
	$("#finish_sale_button").click(function()
			    {
	  <?php if($this->config->item('finish_sale_confirm')):?>
  
    	   if (confirm('<?php echo $this->lang->line("sales_confirm_finish_sale"); ?>'))
    	     {
    		  $('#finish_sale_form').submit();
    	     }
    
      <?php else:?>
        $('#finish_sale_form').submit();
  <?php endif;?>
	  });
	$("#suspend_sale_button").click(function()
	{
	        $('#finish_sale_form').attr('action', '<?php echo site_url("invoices/sales/suspend"); ?>');
    		$('#finish_sale_form').submit();
    	
	});

	   //Check if the user wants to save this sale as quotation
	   $("#quotation_sale_button").click(function()
		{
			
				$('#finish_sale_form').attr('action', '<?php echo site_url("invoices/sales/save_quotation"); ?>');
	    		$('#finish_sale_form').submit();
	    	
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


	/**Save invoice number using AJAX **/
	$('#invoice_number').blur(function() 
			{
				$.post('<?php echo site_url("invoices/sales/set_invoice_number");?>', {invoice_number: $('#invoice_number').val()});
			});
			
	/**Saving date issued using AJAX **/
	 $('#date_issued').blur(function ()
    		{
    	       $.post('<?php echo site_url("invoices/sales/set_date_issued");?>', {date_issued: $('#date_issued').val()});
    		});
	 /**Saving PO number issued using AJAX **/
	 $('#po_number').blur(function ()
    		{
    	       $.post('<?php echo site_url("invoices/sales/set_po_number");?>', {po_number: $('#po_number').val()});
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
	
	<?php if ($payments_cover_total):?>
	$("#finish_sale_button").focus();
	<?php endif;?>
	

	$('#date_issued').datePicker({ dateFormat: 'dd-mm-yy' });
}

</script>
	
	<?php echo $right_footer;?>
