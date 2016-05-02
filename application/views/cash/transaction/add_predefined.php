<?php
function listTree( $tree=array(), $spacer="\t", $level=0 ) {
  if( !is_int( $level ) ) {
    $level = 0;
  }
  $level++;
//  if( $level == 1 ) {
//    if (isset($out)) $out .= '<ul>';
//	else $out = '<ul>';
//  }
  foreach( $tree as $key=>$val ) {
	if (!isset($out)) $out = "";
//    $out .= "\n<option value='".$val['nav_id']."'>".str_repeat( $spacer, $level );
	$out .= "\n<option value='".$val['nav_id']."'>".str_repeat( $spacer, $level );
    $out .= $val['nav_name'];
//	$out .= "(".$val['camount'].")";
    if( isset($val['nav_children']) ) {
      $out .= '</option>';
      $out .= listTree( $val['nav_children'], $spacer, $level+1 );
      //$out .= "\n".str_repeat( $spacer, $level+1 ).'</ul>';
      //$out .= "\n".str_repeat( $spacer, $level ).'</li>';
    }
    else {
      $out .= '</option>';
    }
  }
//  if( $level == 1 ) {
//    $out .= "\n".'</ul>';
//  }
  return $out;
}
//&nbsp;
$listree = listTree( $tree, "&nbsp;&nbsp;" );
?>

<?php echo validation_errors(); ?>

<br>
<form name="predefined" method="post" name="predefined">
<table  class="table table-striped">
<tr><td>
<table  class="table table-striped">

	<tr>
		<td class="tableHeaderLeft">Description</td>
		<td class="tableContent1Left"><input type="text" SIZE="40" name="ptdesc" id="tdesc" value="<?=isset($table['ptdesc']) ? $table['ptdesc'] :'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Amount</td>
		<td class="tableContent1Left"><input type="text" SIZE="40" id="tamount" name="ptamount" value="<?=isset($table['ptamount']) ? $table['ptamount']:'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Debit</td>
		<td class="tableContent1Left">
			<select id="tfrom" name="ptfrom">

				<?php  foreach($accounts['asset'] as $v):?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['ptfrom']) && $table['ptfrom'] == $v['cid'] ) echo "SELECTED";?>>Account > Asset > <?=$v['cname'];?></option>
				<?php   endforeach;?>

				
				<?php  foreach($accounts['liability'] as $v):?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['ptfrom']) && $table['ptfrom'] == $v['cid'] ) echo "SELECTED";?>>Account > Liability > <?=$v['cname'];?></option>
				<?php   endforeach;?>


				<?php  //<option value="null">No Parent</option> ?>
				<?php  $hold = 'no'; ?> 
				<?php  foreach($tree_table as $v):?>
				<?php  
					if ($table['cid'] == $v['cid'] ) {
						$cur_lvl = $v['lvl'];
						$hold = 'yes';
					}
					elseif ($hold == 'yes') {
						$hold = ($cur_lvl >= $v['lvl']) ? 'no' : 'yes';
					} 
				?>
				<?php  if ($table['cid'] != $v['cid'] && $hold != 'yes' && $v['lvl'] != 1) : ?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['ptfrom']) && $table['ptfrom'] == $v['cid'] ) echo "SELECTED";?>>Budget > <?=$v['cname'];?></option>
				<?php  endif; ?>
				<?php   endforeach;?>
			</select>
		</td>
	</tr>


	<tr>
		<td class="tableHeaderLeft">Credit</td>
		<td class="tableContent1Left">
			<select id="tto"  name="ptto">

				<?php  foreach($accounts['asset'] as $v):?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['ptto']) && $table['ptto'] == $v['cid'] ) echo "SELECTED";?>>Account > Asset > <?=$v['cname'];?></option>
				<?php   endforeach;?>

				
				<?php  foreach($accounts['liability'] as $v):?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['ptto']) && $table['ptto'] == $v['cid'] ) echo "SELECTED";?>>Account > Liability > <?=$v['cname'];?></option>
				<?php   endforeach;?>


				<?php  //<option value="null">No Parent</option> ?>
				<?php  $hold = 'no'; ?> 
				<?php  foreach($tree_table as $v):?>
				<?php  
					if ($table['cid'] == $v['cid'] ) {
						$cur_lvl = $v['lvl'];
						$hold = 'yes';
					}
					elseif ($hold == 'yes') {
						$hold = ($cur_lvl >= $v['lvl']) ? 'no' : 'yes';
					} 
				?>
				<?php  if ($table['cid'] != $v['cid'] && $hold != 'yes' && $v['lvl'] != 1) : ?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($table['ptto']) && $table['ptto'] == $v['cid'] ) echo "SELECTED";?>>Budget > <?=$v['cname'];?></option>
				<?php  endif; ?>
				<?php   endforeach;?>
			</select>
		</td>
	</tr>

	<tr>
		<td class="tableHeaderLeft">Memo</td>
		<td class="tableContent1Left">
			<textarea name='ptmemo'><?=isset($table['ptmemo']) ? $table['ptmemo']:'';?></textarea>
		</td>
	</tr>

	<tr>
		<td class="tableHeaderLeft"></td>
		<td class="tableContent1Left">
			<input type="submit" class="btn btn-small btn-primary" <?=isset($table['ptdesc']) ? "onclick=\"return confirm('Are you sure you want to update?')\"" : ''; ?>>
		</td>
	</tr>
</table>
</td>

<td>

<?php  //$this->load->view('cash/transaction/predefined'); ?>

</td>

</tr>
</table>
</form>
