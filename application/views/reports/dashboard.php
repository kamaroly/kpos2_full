<table width="100%">
<tr>
<td width="50%">
<div class="widget-box">
<div class="widget-header widget-header-flat widget-header-small">
<h5><?php echo $this->lang->line('reports_sales_summary_report');?></h5>
</div>
   <div class="widget-body">
     <div class="widget-main"><?php echo $graphical_summary_sales_graph;?></div><!--/widget-main-->
   </div><!--/widget-body-->
</div>
</td width="50%">
<td>
<div class="widget-box">
<div class="widget-header widget-header-flat widget-header-small">

<h5><?php echo $this->lang->line('reports_items_summary_report');?></h5>

</div>
   <div class="widget-body">
     <div class="widget-main"><?php echo $graphical_summary_items_graph;?></div><!--/widget-main-->
   </div><!--/widget-body-->
</div>
</td>
</tr>
<tr>
<td width="50%">
<div class="widget-box">
<div class="widget-header widget-header-flat widget-header-small">

<h5><?php echo $this->lang->line('reports_payments_summary_report');?></h5>

</div>
   <div class="widget-body">
     <div class="widget-main"><?php echo $graphical_summary_payments_graph;?></div><!--/widget-main-->
   </div><!--/widget-body-->
</div>
</td>
<td  width="50%">
 <div class="widget-box">
<div class="widget-header widget-header-flat widget-header-small">
<h5>INCOME VS EXPENSES</h5>
</div>
   <div class="widget-body">
     <div class="widget-main"><?php echo $income_vs_expenses;?></div><!--/widget-main-->
   </div><!--/widget-body-->
</div>
</td>
</tr>
</table>
