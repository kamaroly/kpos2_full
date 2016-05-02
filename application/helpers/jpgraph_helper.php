<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * JpGraph Plugin Class
 *
 * @package     MyFina
 * @subpackage  Plugins
 * @author      Jamalulkhair Khairedin (jalte)
 * @copyright   Copyright (c) 2007, www.ridinglinux.org
 * @license		http://myfina.ridinglinux.org/public/myfina_license.txt
 * @link 		http://myfina.ridinglinux.org
 * @version 	0.1
 *
 */

function linechart($ydata, $title='Line Chart')
{
    require_once("jpgraph/jpgraph.php");
    require_once("jpgraph/jpgraph_line.php");    
    
    // Create the graph. These two calls are always required
    $graph = new Graph(350,250,"auto",60);
    $graph->SetScale("textlin");
    
    // Setup title
    $graph->title->Set($title);
    
    // Create the linear plot
    $lineplot=new LinePlot($ydata);
    $lineplot->SetColor("blue");
    
    // Add the plot to the graph
    $graph->Add($lineplot);
    
    return $graph; // does PHP5 return a reference automatically?
}

function barchart($data, $legend, $title='Bar Chart')
{
    require_once("jpgraph/jpgraph.php");
    require_once("jpgraph/jpgraph_bar.php");    
    
    // Create the graph. These two calls are always required
    $graph = new Graph(400,200,"auto");    
	$graph->img->SetMargin(40,40,40,40);
    $graph->SetScale("textlin");
    
    // Setup title
    $graph->title->Set($title);
    
	// Create the grouped bar plot
	//$bplot = new BarPlot($data);


	// Create the bar plots
	$aa = array($data[0]);
	$bb = array($data[1]);
	$b1plot = new BarPlot($aa);
	$b1plot->SetFillColor("blue");
	$b2plot = new BarPlot($bb);
	$b2plot->SetFillColor("orange");
	$b1plot->SetLegend("Income");
	$b2plot->SetLegend("Expenses");
	$b1plot->value->Show();
	$b2plot->value->Show();


	//$b1plot->value->Show();
	//$b1plot->value->SetAngle(90);
	// Create the grouped bar plot
	$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
	
	
	// ...and add it to the graPH
	$graph->Add($gbplot);
    //$ll = array(array("Income"),array("Expenses"));
	//$graph->xaxis->SetTickLabels();
	//$graph->xaxis->SetLabelAngle(90);
    // Add the plot to the graph
    //$graph->Add($bplot);
    
    return $graph; // does PHP5 return a reference automatically?
}

function barchart_horizontal($data, $legend, $title='Bar Chart')
{
    require_once("jpgraph/jpgraph.php");
    require_once("jpgraph/jpgraph_bar.php");    
    
    // Create the graph. These two calls are always required
    $graph = new Graph(800,600,"auto");    
	$graph->img->SetMargin(40,110,20,200);
    $graph->SetScale("textlin");
    
    // Setup title
    $graph->title->Set($title);
    
	// Create the grouped bar plot
	//$bplot = new BarPlot($data);

$top = 50;
$bottom = 80;
$left = 200;
$right = 20;
$graph->Set90AndMargin($left,$right,$top,$bottom);

	// Create the bar plots
	$b1plot = new BarPlot($data[0]);
	$b1plot->SetFillColor("orange");
	$b2plot = new BarPlot($data[1]);
	$b2plot->SetFillColor("blue");
	$b2plot->SetLegend("Actual");
	$b1plot->SetLegend("Budget");
	//$b1plot->value->Show();
	//$b2plot->value->Show();


	//$b1plot->value->Show();
	//$b1plot->value->SetAngle(90);
	// Create the grouped bar plot
	$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
	
	
	// ...and add it to the graPH
	$graph->Add($gbplot);
    
	$graph->xaxis->SetTickLabels($legend);
	//$graph->xaxis->SetLabelAngle(90);
    // Add the plot to the graph
    //$graph->Add($bplot);
    
    return $graph; // does PHP5 return a reference automatically?
}


function piechart($data, $legend, $title='Pie Chart')
{
    require_once("jpgraph/jpgraph.php");
    require_once("jpgraph/jpgraph_pie.php");    
    
    // Create the graph. These two calls are always required
    $graph = new PieGraph(550,250,"auto");
	$graph->SetShadow();
    
    // Setup title
    $graph->title->Set($title);
    
    // Create the linear plot
	$p1 = new PiePlot($data);
	$p1->SetLegends($legend);
	$p1->SetCenter(0.4);
    
    // Add the plot to the graph
	$graph->Add($p1);

    return $graph; // does PHP5 return a reference automatically?
}

?> 