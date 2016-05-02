<?php echo validation_errors(); ?>


<form method="post" action="<?=site_url($this->uri->uri_string());?>">
<table  class="table table-striped">
	<tr>
		<td class="tableHeaderLeft">Type Name</td>
		<td class="tableContent1Left">
		<input type="text" SIZE="40" name="acc_name" 
		value="<?=isset($table->acc_name)?$table->acc_name:'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Description</td>
		<td class="tableContent1Left">
		<input type="text" SIZE="40" name="acc_description" value="<?=isset($table->acc_description)?$table->acc_description:'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft"></td>
		<td class="tableContent1Left">
			<input type="submit" value="save"  class="btn btn-small btn-primary">
		</td>
	</tr>
</table>
</form>