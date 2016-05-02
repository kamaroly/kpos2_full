<table  class="table table-striped">
	<tr>
		<td>
			<form method="post" action="cash/report/income_vs_expenses">
			Month: <?=$formdate->selectMonth()?>    
			Year: <?=$formdate->selectYear()?>
			<input type="submit" class="btn btn-small btn-primary" value="GO">
			</form>    
		</td>
		<td>
			<form method="post">
			<input type="hidden" name="month" value="<?=mdate('%m', time())?>">    
			<input type="hidden" name="year" value="<?=mdate('%Y', time())?>">
			<input type="submit" class="btn btn-small btn-primary" value="This Month">
			</form>
		</td>
	<tr>
</table>    


<?php
$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->set_header("Pragma: public");

foreach($data as $label=>$value)
{
    	$graph_data[] = "['".(string)$label."',". (float)$value."]";
}

?>
<?php

?>
<style>
#chartArea{background:red;}
#chart_div{background-color:white;}</style>

    <!--Load the AJAX API-->
    <script type="text/javascript" src="<?php echo base_url();?>js/googlechart.js"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string','Rep');
        data.addColumn('number','Money');
        data.addRows([
   
          <?php echo implode(",", $graph_data); ?>
               ]);

        // Set chart options
        var options = {'title':'<?php echo $page_title;?>'};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>

    <!--Div that will hold the pie chart-->
    <div id="chart_div" style="width="100px"></div>
    
    
    
<table  class="table table-striped">
	<tr><td class="tableHeaderLeft">Total Income</td><td class="tableContent1Right"><?=number_format($income, 2, '.', ',')?></td></tr>
	<tr><td class="tableHeaderLeft">Total Expenses</td><td class="tableContent1Right"><?=number_format($expenses, 2, '.', ',')?></td></tr>
	<tr><td class="tableHeaderLeft">Balance</td><td class="tableContent1Right"><?=number_format($balance, 2, '.', ',')?></td></tr>
</table>
 