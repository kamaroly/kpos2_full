<div class="headerbar">

	<h1><?php echo $this->lang->line('invoices'); ?></h1>
	
	<div class="pull-right">
		<a class="create-invoice btn btn-primary" href="#"><i class="icon-plus icon-white"></i> <?php echo $this->lang->line('new'); ?></a>
	</div>

	
    <div class="pull-right">
        <a href="<?php echo site_url('invoices/calendar'); ?>" class="btn btn-info" title="<?php echo $this->lang->line('calendar_view'); ?>"><i class="icon-calendar icon-white"></i></a>
    </div>

	<div class="pull-right">
		<ul class="nav nav-pills index-options">
			<li <?php if ($status == 'open') { ?>class="active"<?php } ?>><a href="<?php echo site_url('invoices/status/open'); ?>"><?php echo $this->lang->line('open'); ?></a></li>
			<li <?php if ($status == 'closed') { ?>class="active"<?php } ?>><a href="<?php echo site_url('invoices/status/closed'); ?>"><?php echo $this->lang->line('closed'); ?></a></li>
			<li <?php if ($status == 'overdue') { ?>class="active"<?php } ?>><a href="<?php echo site_url('invoices/status/overdue'); ?>"><?php echo $this->lang->line('overdue'); ?></a></li>
            <li <?php if ($status == 'all') { ?>class="active"<?php } ?>><a href="<?php echo site_url('invoices/status/all'); ?>"><?php echo $this->lang->line('all'); ?></a></li>
		</ul>
	</div>

</div>
<?php exit;?>
<div id="filter_results">
<?php $this->layout->load_view('invoices/partial_invoice_table', array('invoices' => $invoices)); ?>
</div>