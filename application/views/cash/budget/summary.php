<table  class="table table-striped">
	<tr>
		<td>
			<form method="post">
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


<table  class="table table-striped">
	<tr>
		<td class="tableContent1Left" colspan="4" style="text-align: right;"><a href="<?php echo site_url('cash/budget/additem');?>"><div class="btn btn-small btn-success" >Add Item</div></a></td> 
	</tr>
	<tr>
		<td class="tableHeaderLeft">Budget</td>
		<td class="tableHeaderLeft">Monthly<br>Allocation</td>
		<td class="tableHeaderLeft">Actual <br>This Month</td>
		<?php  /* <td class="tableHeaderLeft">Overall</td> */ ?>
		<td class="tableHeaderLeft"></td>
	</tr>
<?php   foreach($table as $v):?>
<?php   extract($v); ?>
	</tr>
		<td class="<?=($cincome == 1) ? "tableContent1Left" : "tableContent3Left"; ?>">
			<?=str_repeat( "&nbsp;&nbsp;", $lvl );?>
			<?php  if ($lvl != 1) : ?><img border="0" src="<?php echo base_url();?>/images/joinbottom.gif"> <?php  endif; ?>
			<a href="<?php echo site_url('cash/transaction/summary/'.$cid);?>"><?=$cname;?></a>
		</td>
		<td class="<?=($cincome == 1) ? "tableContent1Left" : "tableContent3Left"; ?>" style="text-align: right;">
			<?=$camount;?>
			<?php  if (isset($btotal)) {echo " (".number_format($btotal, 2, '.', ',').")"; unset($btotal);}?>
		</td>
		<td class="<?=($cincome == 1) ? "tableContent1Left" : "tableContent3Left"; ?>" style="text-align: right;">
			<?=isset($total['per_month'][$cid]) ? number_format(abs($total['per_month'][$cid]), 2, '.', ',') : '0.00';?>
		</td>
		
		
		<td class="<?=($cincome == 1) ? "tableContent1Left" : "tableContent3Left"; ?>">
			<?php  if ($lvl != 1) : ?>
			<a href="<?php echo site_url('cash/budget/edititem/'.$cid);?>">
			<img border="0" src="<?php echo base_url();?>/images/b_edit.png" title="Edit" alt="Edit">
			</a>
			<?php  endif; ?>
		</td>
	</tr>
<?php   endforeach;?>
	<tr>
		<td class="tableContent1Left" colspan="4" style="text-align: right;">
		<a href="<?php echo site_url('cash/budget/additem');?>"><div class="btn btn-small btn-success" >Add Item</div></a>
		</td> 
	</tr>
</table>
