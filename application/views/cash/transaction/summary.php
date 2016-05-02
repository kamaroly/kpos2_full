<script language="javascript" type="text/javascript" src="<?php echo base_url();?>'public/shared/js/overlib_mini.js"></script>

<table style="VERTICAL-ALIGN: middle">
	<tr>
		<td align="left"> 
			<form method="post">
			Month: <?=$formdate->selectMonth(1)?>    
			Year: <?=$formdate->selectYear(1)?>    
			<input type="submit" class="btn btn-small btn-primary" value=" GO ">
			</form> 
		</td>
		<td>
			<form method="post">
			<input type="hidden" name="month" value="<?=mdate('%m', time())?>">    
			<input type="hidden" name="year" value="<?=mdate('%Y', time())?>">
			<input type="submit" class="btn btn-small btn-primary" value="This Month">
			</form>
		</td>
		<td>
			<form method="post">
			<input type="hidden" name="month" value="0">    
			<input type="hidden" name="year" value="0">
			<input type="submit" class="btn btn-small btn-primary" value="All Time">
			</form>
		</td>

 	</tr>

</table>

<table  class="table table-striped">
	<tr>
		<td class="tableHeaderLeft">Current Balance</td>
		<td class="tableContent1Left"><?=number_format($sum_amount+$camount, 2, '.', ',');?></td>
	</tr>
</table>
<?php echo $this->pagination->create_links();?>
<table  class="table table-striped">
	<tr>
		<td class="tableContent1Left" colspan="8" style="text-align: right;">
		<a href="<?=site_url('cash/transaction/additem/'.$cid);?>"><div class="btn btn-small btn-success" >Add Transaction</div></a></td> 
	</tr>
	<tr>
		<td class="tableHeaderCenter" colspan="8">
			<?=$cname;?>
			(<?=$tyear != 0 && $tmonth != 0 ? date("F", mktime(0, 0, 0, ($tmonth)))." ".$tyear : "All Time"?>)
		</td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Index</td>
		<td class="tableHeaderLeft">Date</td>
		<td class="tableHeaderLeft">Description</td>
		<td class="tableHeaderLeft">Transaction</td>
		<td class="tableHeaderLeft">Amount</td>
		<td class="tableHeaderLeft">Balance</td>
		<td class="tableHeaderLeft" colspan="2"></td>
	</tr>

	<?php  if (isset($tyear) && $tyear == 0) : ?>
	<tr>
		<td class="tableContent1Center" colspan="4"><i>Starting Balance</i></td>
		<td class="tableContent1Right"><?=$camount;?></td>
		<td class="tableContent1Right"><?=$camount;?></td>
		<td class="tableContent1Left"></td>
		<td class="tableContent1Left"></td>
	</tr>
	<?php  endif; ?>

<?php   $idx = 0;?>
<?php   foreach($table as $v):?>
<?php   $idx++;?>
<?php   extract($v); ?>
	<tr>
		<td class="tableContent1Left"><?=$idx;?></td>
		<td class="tableContent1Left"><?=$tdate;?></td>
		<td class="tableContent1Left">
			<?php  if(isset($tmemo) and $tmemo != '') : ?>
			<a HREF="javascript:void(0);" onmouseover="overlib('&lt;span class=\'mod_content_links_tooltip_introtext\'&gt;&lt;p&gt;&lt;/p&gt;&lt;p style=\&quot;margin-bottom: 0in\&quot;&gt;&lt;em&gt;&lt;strong&gt;<?=str_replace(array("'","\r","\r\n","\n\r","\n"),"`",$tmemo)?>&lt;/span&gt;', CAPTION, '<?=$tdesc;?>', FGCOLOR, '#C7C7C5', BGCOLOR, '#AF0000', BORDER, 0, CAPCOLOR, '#FFFFFF', TEXTCOLOR, '#59595B');" onmouseout="return nd();"><?=$tdesc;?></A>
			<?php  else : ?><?=$tdesc;?>
			<?php  endif; ?>
		</td>
		<td class="tableContent<?=$coupletype;?>Left">
			<?=$tr_amount < 0 ? "To : " : "From : ";?>
			<a href="<?php echo site_url('cash/transaction/summary/0/'.$tr_couple);?>">
			<?=$couplename;?></a>
		</td>
		<td class="tableContent1Right"><?=number_format($tr_amount, 2, '.', ',');?></td>
		<td class="tableContent1Right"><?=number_format($tr_acbalance + $camount, 2, '.', ',');?></td>
		<td class="tableContent1Left">
			<a href="<?php echo site_url('cash/transaction/edititem/'.$tid.'/'.$cid);?>">
			<img border="0" src="<?php echo base_url();?>/images/b_edit.png" title="Edit" alt="Edit">
			</a>
		</td>
		<td class="tableContent1Left">
			<a href="<?=site_url('cash/transaction/deleteitem/'.$tid);?>" onclick="return confirm('Are you sure you want to delete?')">
			<img border="0" src="<?php echo base_url();?>/images/b_drop.png" title="Delete" alt="Delete">
			</a>
		</td>
	</tr>
<?php   endforeach;?>
	<tr>
		<td class="tableContent1Left" colspan="8" style="text-align: right;"><a href="<?php echo site_url('cash/transaction/additem/'.$cid);?>"><div class="btn btn-small btn-success" >Add Transaction</div></a></td> 
	</tr>
</table>
<?php echo $this->pagination->create_links();?>
<br>
<table  class="table table-striped">
	<tr>
		<td class="tableHeaderLeft">Current Balance</td>
		<td class="tableContent1Left"><?=number_format($sum_amount+$camount, 2, '.', ',');?></td>
	</tr>
</table>
<br>
<table  class="table table-striped">
	<tr><td colspan="2" class="tableHeaderLeft">Legends</td></tr>
	<tr><td class="tableContentaLeft">&nbsp;&nbsp;</td><td class="tableContent1Left">Account</td></tr>
	<tr><td class="tableContentbLeft">&nbsp;&nbsp;</td><td class="tableContent1Left">Budget</td></tr>
</table>

