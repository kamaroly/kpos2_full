<table class="sideMenu">
<tr>
	<th class="sideNavHeading widget-header"><?php echo $this->lang->line('reports_reports'); ?></td>
</tr>
	<tr><td class="sideNavSubHeader"><?php echo $this->lang->line('reports_graphical_reports'); ?>
		</td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/graphical_summary_sales');?>"><?php echo $this->lang->line('reports_sales'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/graphical_summary_categories');?>"><?php echo $this->lang->line('reports_categories'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/graphical_summary_customers');?>"><?php echo $this->lang->line('reports_customers'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/graphical_summary_suppliers');?>"><?php echo $this->lang->line('reports_suppliers'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/graphical_summary_items');?>"><?php echo $this->lang->line('reports_items'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/graphical_summary_employees');?>"><?php echo $this->lang->line('reports_employees'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/graphical_summary_taxes');?>"><?php echo $this->lang->line('reports_taxes'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/graphical_summary_discounts');?>"><?php echo $this->lang->line('reports_discounts'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/graphical_summary_payments');?>"><?php echo $this->lang->line('reports_payments'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('cash/report/income_vs_expenses');?>" class="sideNavLink">Income Vs Expenses</a></td></tr>
	        <tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('cash/report/income');?>" class="sideNavLink">Income Breakdown</a></td></tr>
    		<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('cash/report/expenses');?>" class="sideNavLink">Expenditure Breakdown</a></td></tr>
	    	<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('ash/report/budgetvsactual');?>" class="sideNavLink">Budget Vs Actual</a></td></tr>
	
	<tr><td class="sideNavSubHeader"><?php echo $this->lang->line('reports_summary_reports'); ?>
		</td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_sales');?>"><?php echo $this->lang->line('reports_sales'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_categories');?>"><?php echo $this->lang->line('reports_categories'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_customers');?>"><?php echo $this->lang->line('reports_customers'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_suppliers');?>"><?php echo $this->lang->line('reports_suppliers'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_receiving_per_suppliers');?>"><?php echo $this->lang->line('reports_receiving_per_suppliers'); ?></a></td></tr>
			
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_items');?>"><?php echo $this->lang->line('reports_items'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_employees');?>"><?php echo $this->lang->line('reports_employees'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_taxes');?>"><?php echo $this->lang->line('reports_taxes'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_discounts');?>"><?php echo $this->lang->line('reports_discounts'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/summary_payments');?>"><?php echo $this->lang->line('reports_payments'); ?></a></td></tr>
		</tr>
	
	<tr><td class="sideNavSubHeader"><?php echo $this->lang->line('reports_detailed_reports'); ?>
		</td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/detailed_sales');?>"><?php echo $this->lang->line('reports_sales'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/detailed_receivings');?>"><?php echo $this->lang->line('reports_receivings'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/specific_customer');?>"><?php echo $this->lang->line('reports_customer'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/specific_employee');?>"><?php echo $this->lang->line('reports_employee'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('customers/index/export');?>"><?php echo $this->lang->line('reports_customers_export'); ?></a></td></tr>
		
		</tr>
	
	
	<tr><td class="sideNavSubHeader"><?php echo $this->lang->line('reports_inventory_reports'); ?>
		</td></tr>
		    <tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/inventory_expiring');?>"><?php echo $this->lang->line('reports_expiring_inventory_report'); ?></a></td></tr>
		
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/inventory_low');?>"><?php echo $this->lang->line('reports_low_inventory'); ?></a></td></tr>
			<tr><td class="sideNav"><a class="sideNavLink"href="<?php echo site_url('reports/inventory_summary');?>"><?php echo $this->lang->line('reports_inventory_summary'); ?></a></td></tr>
		</tr>

<tr>
	<td class="sideNav">&nbsp;</td>
</tr>


</table>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>


<script type="text/javascript" language="javascript">
$(document).ready(function()
{
});
</script>
