<?php
$this->load->view("partial/header_excel");


?>
<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/ospos.css" />

<table id="rounded-corner">
<tr><th><?php echo  $this->lang->line('common_first_name');?></th><th><?php echo $this->lang->line('common_last_name');?></th>
<th><?php echo $this->lang->line('common_email');?></th><th><?php echo $this->lang->line('common_phone_number');?></th>
</tr>
<?php foreach($manage_table as $customers)
{
	?>
	<tr>
	<td><?php echo $customers->first_name;?></td>
	<td><?php echo $customers->last_name;?></td>
	<td><?php echo $customers->email;?></td>
	<td><?php echo $customers->phone_number;?></td>
	</tr>
	<?php
}

?>
</table>


<?php 

$this->load->view("partial/footer_excel");
$content = ob_end_flush();

$filename = trim($filename);
$filename = str_replace(array(' ', '/', '\\'), '', $title);
$filename .= "Customers_list_Export.xls";
header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.$filename);



?>