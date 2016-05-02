<table  class="table table-striped">
	<tr>
		<td class="tableHeaderLeft">Id</td>
		<td class="tableHeaderLeft">Description</td>
		<td class="tableHeaderLeft">Debit</td>
		<td class="tableHeaderLeft">Credit</td>
		<td class="tableHeaderLeft">Amount</td>
		<td class="tableHeaderLeft">Memo</td>
		<td class="tableHeaderLeft" colspan="2"></td>
	</tr>
<?php   foreach($predefined as $v):?>
<?php   extract($v); ?>
	</tr>
		<td class="tableContent1Left"><?=$pid;?></td>
		<td class="tableContent1Left"><?=$ptdesc;?></td>
		<td class="tableContent1Left">
			<a href="<?php echo site_url('cash/ransaction/summary/'.$ptfrom);?>"><?=$ptfromname;?></a>
		</td>
		<td class="tableContent1Left">
			<a href="<?php echo site_url('cash/transaction/summary/'.$ptto);?>"><?=$pttoname;?></a>
		</td>
		<td class="tableContent1Right"><?=number_format($ptamount, 2, '.', ',');?></td>
		<td class="tableContent1Left"><?=$ptmemo;?></td>
		<td class="tableContent1Left">
			<a href="<?php echo site_url('cash/transaction/editpredefined/'.$pid);?>/">
			<img border="0" src="<?php echo base_url();?>/images/b_edit.png" title="Edit" alt="Edit">
			</a>
		</td>
		<td class="tableContent1Left">
			<a href="<?php echo site_url('cash/transaction/deletepredefined/'.$pid);?>/" onclick="return confirm('Are you sure you want to delete?')">
			<img border="0" src="<?php echo base_url();?>/images/b_drop.png" title="Delete" alt="Delete">
			</a>
		</td>
	</tr>
<?php   endforeach;?>
	<tr>
		<td class="tableContent1Left" colspan="8" style="text-align: right;">
		<a href="<?php echo site_url('cash/transaction/addpredefined/');?>"><div class="btn btn-small btn-success" >Add Item</div></a></td> 
	</tr>
</table>
