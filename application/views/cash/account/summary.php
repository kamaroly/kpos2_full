<table  class="table table-striped">
	<tr>
		<td class="tableHeaderCenter" colspan="4">Asset</td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Account</td>
		<td class="tableHeaderLeft">Account Type</td>
		<td class="tableHeaderLeft">Balance</td>
		<td class="tableHeaderLeft"></td>
	</tr>
<?php   $tot_amt = 0; ?>
<?php   foreach($table['asset'] as $v):?>
<?php   
	extract($v); 
	$amt = $cbalance + $camount;
	$tot_amt = $tot_amt + $amt;
?>
	</tr>
		<td class="tableContent1Left"><a href="<?php echo site_url('cash/transaction/summary/0/'.$cid);?>"><?=$cname;?></a></td>
		<td class="tableContent1Left"><?=$acc_name;?></td>
		<td class="tableContent1Right"><?=number_format($amt, 2, '.', ',');?></td>
		<td class="tableContent1Left">
			<a href="<?php echo site_url('cash/accounts/edititem/'.$cid);?>">
			<img border="0" src="<?php echo base_url();?>/images/b_edit.png" title="Edit" alt="Edit">
			</a>
		</td>
	</tr>
<?php   endforeach;?>
	<tr>
		<td class="tableContent1Left" colspan="4" style="text-align: right;"><a href="<?php echo site_url('cash/accounts/additem');?>"><div class="btn btn-small btn-success" >Add Item</div></a></td> 
	</tr>
</table>
<br>
<table  class="table table-striped">
	<?php   foreach($sumtype as $v):?>
	<tr>
		<td class="tableHeaderLeft"><?=$v['acc_name']?></td>
		<td class="tableContent1Right"><?=number_format($v['sum'], 2, '.', ',');?></td>
	</tr>
	<?php   endforeach;?>
	<tr>
		<td class="tableHeaderLeft">Total Net Value</td>
		<td class="tableContent1Right"><?=number_format($tot_amt, 2, '.', ',');?></td>
	</tr>
</table>


