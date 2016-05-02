<style type="text/css">
/**
 * Invoice view styles notes
 *
 * This file NEEDS a locally located stylesheet to generate the appropriate formatting for 
 * transformation into a PDF.  If you alter this file (and you are encouraged to do so) just
 * keep in mind that all of your formatting must be located here.  You might also find that
 * there is limited or no support for a specific CSS style you want (ie: floating) and you'll
 * need to work around with old-school tables.  Sorry for that... ;)  
 */

h1, h2, h3, h4, h5, h6, li, blockquote, p, th, td {
	font-family: Helvetica, Arial, Verdana, sans-serif; /*Trebuchet MS,*/
}
h1, h2, h3, h4 {
	color: #5E88B6;
	font-weight: normal;
}
h4, h5, h6 {
	color: #5E88B6;
}
h2 {
	margin: 0 auto auto auto;
	font-size: x-large;
}
h2 span {
	text-transform: uppercase;
}
li, blockquote, p, th, td {
	font-size: 100%;
}

table {
	width: 100%;
	border:1px solid #000000;
}
td p {
	font-size: small;
	margin: 0;
}
th {
	color: #FFF;
	text-align: center;
	background-color:#2b61a9;
	border-bottom:1px solid #c9c9c9;
	text-transform: uppercase;
}
td {
    color:#000000;
	text-align:center; 
    vertical-align:middle;
	border-left:1px solid #c9c9c9;
}
.bamboo_invoice_bam {
	color: #5E88B6;
	font-weight: bold;
	text-transform: capitalize;
}
.bamboo_invoice_inv {
	font-weight: bold;
	font-variant: small-caps;
	color: #333;
}
#footer {
	border-top: 1px solid #CCC;
	text-align: right;
	font-size: 6pt;
	color: #999999;
}
#footer a {
	color: #999999;
	text-decoration: none;
}
table.stripe {
	border-collapse: collapse;
	border:1px solid #000000;
	page-break-after: auto;
}
table.stripe td {
	border-bottom: 1pt solid black;
	border:1px solid #000000;
}
</style>

<div id="page_subtitle" style="margin-bottom:8px;"><?php echo $subtitle ?></div>
<div >
	<table class="table" >
		<thead>
			<tr>
				<?php foreach ($headers as $header) { ?>
				<th><?php echo $header; ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data as $row) { ?>
			<tr>
				<?php foreach ($row as $cell) { ?>
				<td><?php echo $cell; ?></td>
				<?php } ?>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div id="report_summary">
<?php foreach($summary_data as $name=>$value) 
{ 
?>
	<div class="summary_row"><?php echo $this->lang->line('reports_'.$name). ': '.to_currency($value); ?></div>
<?php }?>
</div>
<?php 
if($export_excel == 1){
	$this->load->view("partial/footer_excel");
	$content = ob_end_flush();
	
	$filename = trim($filename);
	$filename = str_replace(array(' ', '/', '\\'), '', $title);
	$filename .= "_Export.xls";
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $content;
	die();
	
}else{
	//Old footer used to be here
?>

<script type="text/javascript" language="javascript">
function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(); 
	}
}
$(document).ready(function()
{
	init_table_sorting();
});
</script>
<?php 
} // end if not is excel export 
?>