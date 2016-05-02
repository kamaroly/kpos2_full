<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/navigationmenu.css" />

<?php $customer_id=isset($customer->person_id)?$customer->person_id:'';?>
    <nav>
       <ul id="top_nav" class="clearfix"> 
       <?php if ($total_paid < $total): ?>
	     <li><?php echo anchor('invoices/payment/'.$sale_number."/width:365",$this->lang->line('menu_enter_payment')
	     		                ,array('class'=>'thickbox'));?></li>
	   <?php endif;?>
         <li><?php echo anchor("invoices/pdf/$sale_number"."/width:400/height:auto", $this->lang->line('download_pdf')
         		               ); ?></li>
		 <li><?php echo anchor("invoices/choose_clientcontacts/".$customer_id."/".$sale_number."/width:400/height:auto", $this->lang->line('send_email')
		 		               ,array('class'=>'thickbox')); ?></li>
		 <li><?php echo anchor("invoices/copy_invoice/$sale_number"."/width:400/height:auto", $this->lang->line('copy_invoice')
		 		                ,array('class'=>'thickbox')); ?></li>
		 <li><?php echo anchor("invoices/create_recurring/$sale_number"."/width:400/height:auto", $this->lang->line('create_recurring')
		 		                ,array('class'=>'thickbox')); ?></li>
		 <li><?php echo anchor("invoices/delete-invoice/$sale_number"."/width:400/height:auto", $this->lang->line('delete')
		 		                ,array('class'=>'thickbox')); ?></li>
			
        </ul>
      </nav>