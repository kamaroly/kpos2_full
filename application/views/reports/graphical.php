
<div style="text-align: center;">

<?php echo $graph; ?>
</div>
<div id="chart_wrapper">
	<div id="chart"></div>
</div>
<div id="report_summary">
<?php foreach($summary_data as $name=>$value) { ?>

	<div class="summary_row"><?php echo $this->lang->line('reports_'.$name). ': '.to_currency($value); ?></div>
<?php }?>
</div>
<?php
//Old footer used to be here
?>