<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

    <head>

        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>FusionInvoice</title>

        <meta name="viewport" content="width=device-width,initial-scale=1">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/default/css/bootstrap.min.css">

        <style>
            body {
                color: #000 !important;
            }
            table {
                width:100%;
            }
            #header table {
                width:100%;
                padding: 0px;
            }
            #header table td {
                vertical-align: text-top;
                padding: 5px;
            }
            #company-name{
                color:#000;
                font-size: 18px;
            }
            #invoice-to {
                /*                display: table;*/
                /*                content: "";*/
            }
            #invoice-to td {
                text-align: left
            }
            .seperator {
                height: 25px
            }
            .top-border {
                border-top: none;
            }
            .no-bottom-border {
                border:none !important;
                background-color: white !important;
            }
            .alignr {
                text-align: right;
            }
            #invoice-container {
                margin: auto;
                margin-top: 25px;
                width: 900px;
                padding: 20px;
                top:10px;
                background-color: white;
                box-shadow: 4px 4px 14px rgba(0, 0, 0, 0.8);
                overflow-y: hidden;
            }
            #menu-container {
                margin: auto;
                margin-top: 25px;
                width: 900px;
                top:10px;
                overflow-y: hidden;
            }
            .flash-message {
                font-size: 120%;
                font-weight: bold;
            }
        </style>
   <div class="headerbar">
	<h1><?php echo $this->lang->line('invoice_invoice');?> <?php echo $sale_number;?></h1>
    <?php if (isset($invoice->invoice_is_recurring)): ?>
    <span class="label label-info" style="margin-left: 10px;">
    <?php echo $this->lang->line('menu_recurring'); ?></span>
    <?php endif;  ?>

	<div class="pull-right">

		<div class="options btn-group pull-left">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#" style="margin-right: 5px;"><i class="icon-cog"></i> <?php echo $this->lang->line('options'); ?></a>
			<ul class="dropdown-menu">
				
				<li><?php echo anchor("invoices/payment/$sale_number", $this->lang->line('enter_payment')); ?></li>
				<li><?php echo anchor("invoices/generate_pdf/$sale_number", $this->lang->line('download_pdf')); ?></li>
				<li><?php echo anchor("mailer/invoice/$sale_number", $this->lang->line('send_email')); ?></li>
				<li><?php echo anchor("invoices/copy_invoice/$sale_number", $this->lang->line('copy_invoice')); ?></li>
				<li><?php echo anchor("invoices/create_recurring/$sale_number", $this->lang->line('create_recurring')); ?></li>
				<li><?php echo anchor("invoices/delete-invoice/$sale_number", $this->lang->line('delete')); ?></li>
				
			</ul>
		</div>
		
		<a href="#" class="btn" id="btn_add_item" style="margin-right: 5px;"><i class="icon-plus-sign"></i> <?php echo $this->lang->line('add_item'); ?></a>
		
		<a href="#" class="btn btn-primary" id="btn_save_invoice"><?php echo $this->lang->line('save'); ?></a>
	</div>

</div>


<div class="content">
	
	<form id="invoice_form" class="form-horizontal">

		<div class="invoice">

			<div class="cf">

				<div class="pull-left">

                    <h2><?php echo isset($customer->last_name)?$customer->last_name:''.' '.isset($customer->first_name)?$customer->first_name:'';?></h2><br>
					<span>
		<?php if ($customer->address_1 != '') {echo $customer->address_1;}?>
		
		<?php if ($customer->address_2 != '') {echo ', ' . $customer->address_2;}?>
		<?php if ($customer->address_1 != '' || $customer->address_2 != '') {echo '<br />';}?>
		<?php if ($customer->city != '') {$customer->city;}?>
	
		<?php if ($customer->state != '') {if ($customer->city != '') {echo ', ';} echo $customer->state;}?>
		<?php if ($customer->country != '') {if ($customer->zip != '' || ($customer->state == '' && $customer->city != '')){echo ', ';} echo $customer->country;}?>
		<?php if ($customer->zip != '') {echo ' ' . $customer->zip;}?>
		<?php if ($customer->city != '' || $customer->state != '' || $customer->country != '' || $customer->zip != '') {echo '<br />';}?>
				
					</span>
					<br><br>
					<?php if ($customer->phone_number) { ?>
					<span><strong><?php echo $this->lang->line('phone'); ?>:</strong> <?php echo $customer->phone_number; ?></span><br>
					<?php } ?>
					<?php if ($customer->email) { ?>
					<span><strong><?php echo $this->lang->line('email'); ?>:</strong> <?php echo $customer->email; ?></span>
					<?php } ?>

				</div>

				<table style="width: auto" class="pull-right table table-striped table-bordered">
                    
                    <tbody>
                        <tr>
                            <td>
                                <div class="control-group">
                                    <label class="control-label"><?php echo $this->lang->line('invoice'); ?> #</label>
                                    <div class="controls">
                                        <input type="text" id="invoice_number" class="input-small" 
                                        value="<?php echo $sale_number; ?>" style="margin: 0px;">    
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo $this->lang->line('date'); ?></label>
                                    <div class="controls">
                                        <input type="text" id="invoice_date_created" class="input-small" value="
                                        <?php echo $date_invoice_issued; ?>" style="margin: 0px;">    
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo $this->lang->line('due_date'); ?></label>
                                    <div class="controls">
                                        <input type="text" id="invoice_date_due" class="input-small" value="
                                        <?php echo $due_date; ?>" style="margin: 0px;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>

				</table>

			</div>

			<?php $this->layout->load_view('invoices/partial_item_table'); ?>
			
			<p><strong><?php echo $this->lang->line('invoice_terms'); ?></strong></p>
			<textarea id="invoice_terms" name="invoice_terms" style="width: 100%;" rows="5"><?php echo $invoice->invoice_terms; ?></textarea>
            <br><br>
            
            <?php foreach ($custom_fields as $custom_field) { ?>
            <p><strong><?php echo $custom_field->custom_field_label; ?></strong></p>
                    <input type="text" name="custom[<?php echo $custom_field->custom_field_column; ?>]" id="<?php echo $custom_field->custom_field_column; ?>" value="<?php echo $this->mdl_invoices->form_value('custom[' . $custom_field->custom_field_column . ']'); ?>">
            <?php } ?>

            <p class="padded"><?php echo $this->lang->line('guest_url'); ?>: <?php echo auto_link(site_url('guest/view/invoice/' . $invoice->invoice_url_key)); ?></p>
            
		</div>
		
	</form>

</div>