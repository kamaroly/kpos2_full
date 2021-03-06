<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

    <head>

        <meta charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>KPos</title>
        <script type="text/javascript">

    $(function() {

        $('#btn_generate_pdf').click(function() {
            window.location = '<?php echo site_url('invoices/generate_pdf/' . $sale_info_id); ?>';
        });

    });

</script>

        <meta name="viewport" content="width=device-width,initial-scale=1">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/default/css/style.css">

        <style>
            body {
                color: #000 !important;
				overflow-y: auto;
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

    </head>

    <body>
    <?php if(!isset($remove_buttons)):?>
        <div id="menu-container">
            
            
            <a href="<?php echo site_url('guest/view/generate_invoice_pdf/' . $sale_info['invoice_url_key']); ?>" class="btn btn-primary"><i class="icon-white icon-print"></i> <?php echo $this->lang->line('download_pdf'); ?></a> 
            <a href="<?php echo site_url('guest/payment_handler/make_payment/' . $sale_info['invoice_url_key']); ?>" class="btn btn-success"><i class="icon-white icon-ok"></i> <?php echo $this->lang->line('pay_now'); ?></a>
            
         <?php if (in_array( $sale_info['invoice_status_id'], array(2,3))) { ?>
        <a href="<?php echo site_url('guest/quotes/approve/' . $sale_info['invoice_url_key']); ?>" class="btn btn-success"><i class="icon-white icon-check"></i> <?php echo $this->lang->line('approve_this_quote'); ?></a>
        <a href="<?php echo site_url('guest/quotes/reject/' . $sale_info['invoice_url_key']); ?>" class="btn btn-danger"><i class="icon-white icon-ban-circle"></i> <?php echo $this->lang->line('reject_this_quote'); ?></a>
        <?php } elseif ($sale_info['invoice_status_id'] == 4) { ?>
        <a href="#" class="btn btn-success"><?php echo $this->lang->line('quote_approved'); ?></a>
        <?php } elseif ($sale_info['invoice_status_id'] == 5) { ?>
        <a href="#" class="btn btn-danger"><?php echo $this->lang->line('quote_rejected'); ?></a>
        <?php } ?>
            <?php if (isset($flash_message)) { ?>
            <div class="alert flash-message">
                <?php echo $flash_message; ?>
            </div>
            <?php } ?>
        </div>
    <?php endif;;?>
        <div id="invoice-container">

       
            <div id="header">
                <table>
                    <tr>
                        <td id="company-name">
                            <h2> <?php echo $this->config->item('company');?></h2>
                            <p><?php if ($this->config->item('address')!='') { echo $this->config->item('address') . '<br>'; } ?>
                                <?php if ($this->config->item('city')!='') { echo  $this->config->item('city') . ' '; } ?>
                                <?php if ($this->config->item('country')!='') { echo $this->config->item('country') . '<br>'; } ?>
                                <?php if ($this->config->item('phone')!='') { ?><abbr>P:</abbr><?php echo $this->config->item('phone'); ?><br><?php } ?>
                                <?php if ($this->config->item('email')!='') { ?><abbr>P:</abbr><?php echo $this->config->item('email'); ?><br><?php } ?>
                                <?php if ($this->config->item('website')!='') { ?><abbr>F:</abbr><?php echo $this->config->item('website'); ?><?php } ?>
                            </p>
                        </td>
                        <td class="alignr"><h2><?php echo $this->lang->line('invoice'); ?> <?php echo $sale_info['invoice_number']; ?></h2></td>
                    </tr>
                </table>
            </div>
            
            <div id="invoice-to">
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <h2><?php echo $client->last_name.' '. $client->first_name; ?></h2>
                            <p><?php if ($client->address_1) { echo $client->address_1 . '<br>'; } ?>
                                <?php if ($client->address_2) { echo $client->address_2 . '<br>'; } ?>
                                <?php if ($client->city) { echo $client->city . ' '; } ?>
                                <?php if ($client->state) { echo $client->state . ' '; } ?>
                                <?php if ($client->zip) { echo $client->zip . '<br>'; } ?>
                                <?php if ($client->phone_number) { ?><abbr>P:</abbr><?php echo $client->phone_number; ?><br><?php } ?>
                            </p>
                        </td>
                        <td style="width:40%;"></td>
                        <td>
                            <table>
                                <tbody>
                                    <tr>
                                        <td><?php echo $this->lang->line('invoice_date'); ?></td>
                                        <td><?php echo $sale_info['sale_time']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->lang->line('due_date'); ?></td>
                                        <td><?php echo $sale_info['invoice_date_due']; ?></td>
                                    </tr>
                                     <tr>
                                        <td><?php echo $this->lang->line('po_number'); ?></td>
                                        <td><?php echo $sale_info['po_number']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->lang->line('amount_due'); ?></td>
                                        <td><?php echo $amount_change; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
         
            <div id="invoice-items">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('sales_quantity'); ?></th>
                            <th><?php echo $this->lang->line('items_item'); ?></th>
                            <th><?php echo $this->lang->line('sales_description_abbrv'); ?></th>
                            <th><?php echo $this->lang->line('common_price'); ?></th>
                            <th><?php echo $this->lang->line('sales_total'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item) : ?>
                            <tr>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo nl2br($item['description']); ?></td>
                                <td><?php echo $item['price']; ?></td>
                                <td><?php echo  to_currency(round($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)); ?>; </td>
                            </tr>
                        <?php endforeach ?>
                          
                        <tr>
                            <td colspan="3"></td>
                            <td><strong><?php echo $this->lang->line('sales_sub_total'); ?>:</strong></td>
                            <td><?php echo to_currency($subtotal); ?></td>
                        </tr>
     
                        
                        <?php foreach($taxes as $name=>$value) : ?>
                        <tr>
                                <td class="no-bottom-border" colspan="3"></td>
                                <td><strong><?php echo $name; ?></strong></td>
                                <td><?php echo to_currency(round($value)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                       
                        <tr>
                            <td class="no-bottom-border" colspan="3"></td>
                            <td><strong><?php echo $this->lang->line('sales_total'); ?>:</strong></td>
                            <td><?php echo to_currency($total); ?></td>
                        </tr>
                       
	
                        <tr>
                            <td class="no-bottom-border" colspan="3"></td>                
                            <td><strong><?php echo $this->lang->line('sales_change_due'); ?></strong></td>
                            <td><strong><?php echo  $amount_change; ?></strong></td>
                        </tr>
                        <tr>
                        <td class="no-bottom-border" colspan="3"></td> 
                          <td>
                             <table>
                               <caption>Payments</caption>
                               <tbody>
                                    <?php foreach($payments as $payment_id=>$payment): ?>
		                <tr>
		                <td class="no-bottom-border" colspan="3"></td>
		                <td  ><?php echo $this->lang->line('sales_payment'); ?> <strong><?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?> </strong></td>
	                    <td  ><?php echo to_currency( round($payment['payment_amount']) * -1 ); ?>  </td>
	                    </tr>
	                    <?php  endforeach; ?>
                               </tbody>
                             </table>
                          </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="seperator"></div>
                
                <?php if ($sale_info['invoice_terms']) { ?>
                <h4><?php echo $this->lang->line('terms'); ?></h4>
                <p><?php echo nl2br($sale_info['invoice_terms']); ?></p>
                <?php } ?>
            </div>

        </div>

    </body>
</html>