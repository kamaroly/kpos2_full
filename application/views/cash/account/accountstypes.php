
<?php if($this->session->flashdata('message')):?>
<div>
<?php echo $this->session->flashdata('message')?$this->session->flashdata('message'):''?>
</div>
<?php endif;?>
<?php echo $this->pagination->create_links();?>
<table  class="table table-striped">
	<tr>
		<td class="tableHeaderCenter" colspan="4">Accounts types</td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Account ID</td>
		<td class="tableHeaderLeft">Account NAME</td>
	    <td class="tableHeaderLeft">Account DESCRIPTION</td>
	    
		<td class="tableHeaderRight">Actions</td>
	</tr>

<?php   foreach($table as $acc_type):?>

	</tr>
		  
		<td class="tableContent1Left"><?=$acc_type->acc_id;?></td>
		<td class="tableContent1left"><?=$acc_type->acc_name;?></td>
		<td class="tableContent1left"><?=$acc_type->acc_description;?></td>
		<td class="tableContent1right">
			<a href="<?php echo site_url('cash/accounts/addtype/'.$acc_type->acc_id);?>">
			<img border="0" src="<?php echo base_url();?>/images/b_edit.png" title="Edit" alt="Edit">
			</a>
			<a href="<?php echo site_url('cash/accounts/deletetype/'.$acc_type->acc_id);?>" onclick="return confirm('All Children accounts will be deleted !Are you sure you want to delete? ')">
			<img border="0" src="http://localhost/kpos2/images/b_drop.png" title="Delete" alt="Delete">
			</a>
		</td>
	</tr>
<?php   endforeach;?>
	<tr>
		<td class="tableContent1Left" colspan="4" style="text-align: right;"><a href="<?php echo site_url('cash/accounts/addtype');?>"><div class="btn btn-small btn-success" >Add Item</div></a></td> 
	</tr>
</table>
<?php echo $this->pagination->create_links();?>