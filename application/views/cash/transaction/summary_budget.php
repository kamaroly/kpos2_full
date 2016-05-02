<script language="javascript" type="text/javascript" src="<?=base_url()?>public/shared/js/overlib_mini.js"></script>
<table style="VERTICAL-ALIGN: middle">
	<tr>
		<td align="laft">
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
		<td class="tableHeaderLeft">Total</td>
		<td class="tableHeaderLeft" colspan="2"></td>
	</tr>

<?php   foreach($table as $v):?>


<?php   extract($v); ?>
	<tr>
		<td class="tableContent1Left"><?=$tr_index;?></td>
		<td class="tableContent1Left"><?=$tdate;?></td>
		<td class="tableContent1Left">
			<?php  if(isset($tmemo) and $tmemo != '') : ?>
			<a HREF="javascript:void(0);" onmouseover="overlib('&lt;span class=\'mod_content_links_tooltip_introtext\'&gt;&lt;p&gt;&lt;/p&gt;&lt;p style=\&quot;margin-bottom: 0in\&quot;&gt;&lt;em&gt;&lt;strong&gt;<?=str_replace(array("'","\r","\r\n","\n\r","\n"),"`",$tmemo)?>&lt;/span&gt;', CAPTION, '<?=$tdesc;?>', FGCOLOR, '#C7C7C5', BGCOLOR, '#AF0000', BORDER, 0, CAPCOLOR, '#FFFFFF', TEXTCOLOR, '#59595B');" onmouseout="return nd();"><?=$tdesc;?></A>
			<?php  else : ?><?=$tdesc;?>
			<?php  endif; ?>
		</td>
		<td class="tableContent<?=$coupletype;?>Left">
			<?=$tr_amount < 0 ? "Credit Into : " : "From : ";?>
			<a href="<?=site_url('cash/transaction/summary/'.$tr_couple);?>">
			<?=$couplename;?></a>
		</td>
		<td class="tableContent1Right"><?=number_format(0-$tr_amount, 2, '.', ',');?></td>
		<td class="tableContent1Right"><?=number_format(0-$tr_acbalance , 2, '.', ',');?></td>
		<td class="tableContent1Left">
			<a href="<?=site_url('cash/transaction/edititem/'.$tid.'/'.$cid);?>">
			<img border="0" src="<?php echo base_url();?>/images/b_edit.png" title="Edit" alt="Edit">
			</a>
		</td>
		<td class="tableContent1Left">
			<a href="<?php echo site_url('cash/cash/transaction/deleteitem/'.$tid);?>" onclick="return confirm('Are you sure you want to delete?')">
			<img border="0" src="<?php echo base_url();?>/images/b_drop.png" title="Delete" alt="Delete">
			</a>
		</td>
	</tr>
<?php   endforeach;?>
	<tr>
		<td class="tableContent1Left" colspan="8" style="text-align: right;"><a href="<?php echo site_url('cash/cash/transaction/additem/'.$cid);?>"><div class="btn btn-small btn-success" >Add Item</div></a></td> 
	</tr>
</table>
<br>

<?php  if($cincome == 1) : ?>
<table  class="table table-striped">
	<tr>
		<td class="tableHeaderLeft">Monthly Projection</td>
		<td class="tableContent1Left"><?=number_format($camount, 2, '.', ',');?></td>
	</tr>
	
	<tr>
		<td class="tableHeaderLeft">Total Amount Received</td>
		<td class="tableContent1Left"><?=number_format(abs($sum_amount), 2, '.', ',');?></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Budget Vs Actual Income</td>
		<?php  
			$bal = $camount - abs($sum_amount);
			if ($bal == 0) 
             {
				$cl = 1;
				$str = "You've got the same amount as per projected income for $cname";
			}
			else if ($bal < 0) {
				$cl = 1;
				$str = "Your actual $cname income is ".number_format(abs($bal), 2, '.', ',')." more than the projected amount";
			}
			else {
				$cl = 3;
				//$amt = number_format($camount + $cbalance, 2, '.', ',');
				$str = "You are short of ".number_format(abs($bal), 2, '.', ',')." from the projected income";
			}
		?>
		<td class="tableContent<?=$cl;?>Left">
			<?=$str;?>
		</td>
	</tr>
</table>

<?php  else : ?>

<table  class="table table-striped">
	<tr>
		<td class="tableHeaderLeft">Monthly Allocation</td>
		<td class="tableContent1Left"><?=$camount;?></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Total Amount Spent</td>
		<td class="tableContent1Left"><?=number_format($sum_amount, 2, '.', ',');?></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Budget Vs Actual</td>
		<?php  
			$bal = $camount - $sum_amount;
			if ($bal >= 0) {
				$cl = 1;
				$str = "You have $bal more to spend on $cname for this month";
			}
			else {
				$cl = 3;
				$amt = 0 - $bal;
				$str = "You have overspent ".number_format($amt, 2, '.', ',')." from the allocated budget";
			}
		?>
		<td class="tableContent<?=$cl;?>Left">
			<?=$str;?>
		</td>
	</tr>
</table>

<?php  endif; ?>

<br>
<table  class="table table-striped">
	<tr><td colspan="2" class="tableHeaderLeft">Legends</td></tr>
	<tr><td class="tableContentaLeft">&nbsp;&nbsp;</td><td class="tableContent1Left">Account</td></tr>
	<tr><td class="tableContentbLeft">&nbsp;&nbsp;</td><td class="tableContent1Left">Budget</td></tr>
</table>

