
<script type="text/javascript"><!--
//var groups=document.isc.example.options.length
//var group=new Array(groups)

function getidx() {
   for (var i = 0; i < document.getElementById("tfrom").length; i++) {
      if (document.getElementById("tfrom").options[i].value == <?=$cid;?>) {
         return document.getElementById("tfrom").options[i].index
      }
   }
   return null
}

function changetfrom() {
	var idx = getidx();
	document.getElementById("tfrom").selectedIndex = idx; 
}

function changetto() {
	var idx = getidx();
	document.getElementById("tto").selectedIndex = idx;
}
//--></script>

<?php 

function listTree( $tree=array(), $spacer="\t", $level=0 ) {
  if( !is_int( $level ) ) {
    $level = 0;
  }
  $level++;

  foreach( $tree as $key=>$val )
   {
	if (!isset($out)) $out = "";
    $out .= "\n<option value='".$val['nav_id']."'>".str_repeat( $spacer, $level );
    $out .= $val['nav_name'];

    if( isset($val['nav_children']) )
     {
      $out .= '</option>';
      $out .= listTree( $val['nav_children'], $spacer, $level+1 );
    }
    else
     {
      $out .= '</option>';
    }
  }

  return $out;
}

$listree = listTree( $tree, "&nbsp;&nbsp;" );
?>
<br>
<?php echo validation_errors(); ?>
<br>

<form name="transaction" method="post" name="transaction">
<table  class="table table-striped">
<tr>
<td>
<table  class="table table-striped">
   <tr>
		<td class="tableHeaderLeft">Date</td>
		<td class="tableContent1Left">
			Day: <?=$formdate->selectDay()?>    
			Month: <?=$formdate->selectMonth()?>    
			Year: <?=$formdate->selectYear()?>    
		</td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Description</td>
		<td class="tableContent1Left"><input type="text" SIZE="40" name="tdesc" id="tdesc" value="<?=isset($table['tdesc']) ? $table['tdesc'] :'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Ammount</td>
		<td class="tableContent1Left"><input type="text" SIZE="40" id="tamount" name="tamount" value="<?=isset($table['tamount']) ? $table['tamount']:'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Debit</td>
		<td class="tableContent1Left">
			<select  id="tfrom" name="tfrom" <?=(isset($table['gotchild']) && $table['gotchild'] == 1) ? 'DISABLED' : '';?> onchange="changetto();">

          	    <?php  foreach($accounts['asset'] as $v):?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['tr_cid1']) && $table['tr_cid1'] == $v['cid'] ) echo "SELECTED";?>>Account > Asset ><?=$v['acc_name'];?>  > <?=$v['cname'];?></option>
				<?php   endforeach;?>
				
				<?php  foreach($accounts['liability'] as $v):?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['tr_cid1']) && $table['tr_cid1'] == $v['cid'] ) echo "SELECTED";?>>Account > Liability > <?=$v['cname'];?></option>
				<?php   endforeach;?>

				<?php  //<option value="null">No Parent</option> ?>
				<?php  $hold = 'no'; ?> 
				<?php  foreach($tree_table as $v):?>
				<?php  
					if (isset($table['cid']) && $table['cid'] == $v['cid'] ) {
						$cur_lvl = $v['lvl'];
						$hold = 'yes';
					}
					elseif ($hold == 'yes') {
						$hold = ($cur_lvl >= $v['lvl']) ? 'no' : 'yes';
					} 
				?>
				<?php  if (!isset($table['cid'])) $table['cid'] = 'dummy'; ?>
				<?php  if ($table['cid'] != $v['cid'] && $hold != 'yes' && $v['lvl'] != 1) : ?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['tr_cid1']) && $table['tr_cid1'] == $v['cid'] ) echo "SELECTED";?>>Budget > <?=$v['cname'];?></option>
				<?php  endif; ?>
				<?php   endforeach;?>
			</select>
		</td>
	</tr>


	<tr>
		<td class="tableHeaderLeft">Credit</td>
		<td class="tableContent1Left">
			<select id="tto"  name="tto" <?=(isset($table['gotchild']) && $table['gotchild'] == 1) ? 'DISABLED' : '';?> onchange="changetfrom();">
            	<?php  foreach($accounts['asset'] as $v):?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Account > Asset ><?=$v['acc_name'];?>  > <?=$v['cname'];?>
					</option>
				<?php   endforeach;?>

				
				<?php  foreach($accounts['liability'] as $v):?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Account > Liability > <?=$v['cname'];?>
					</option>
				<?php   endforeach;?>

            	
				<?php  $hold = 'no'; ?> 
				<?php  foreach($tree_table as $v):?>
				<?php  
					if (isset($table['cid']) && $table['cid'] == $v['cid'] ) {
						$cur_lvl = $v['lvl'];
						$hold = 'yes';
					}
					elseif ($hold == 'yes') {
						$hold = ($cur_lvl >= $v['lvl']) ? 'no' : 'yes';
					} 
				?>
				<?php  if (!isset($table['cid'])) $table['cid'] = 'dummy'; ?>  
				<?php  if ($table['cid'] != $v['cid'] && $hold != 'yes' && $v['lvl'] != 1) : ?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['tr_cid2']) && $table['tr_cid2'] == $v['cid'] ) echo "SELECTED";?>>
					   Budget > <?=$v['cname'];?>
					</option>
				<?php  endif; ?>
				<?php   endforeach;?>
			</select>
		</td>
	</tr>

	<tr>
		<td class="tableHeaderLeft">Memo</td>
		<td class="tableContent1Left">
			<textarea name='tmemo'><?=isset($table['tmemo']) ? $table['tmemo']:'';?></textarea>
		</td>
	</tr>

	<tr>
		<td class="tableHeaderLeft"></td>
		<td class="tableContent1Left">
			<input type="submit" class="btn btn-small btn-primary" <?=isset($table['tdesc']) ? "onclick=\"return confirm('Are you sure you want to update?')\"" : ''; ?>>
		</td>
	</tr>
</table>
</td>

<td>

<?php  $this->load->view('cash/transaction/predefined'); ?>

</td>

</tr>
</table>
</form>
