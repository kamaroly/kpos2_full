<?php
function listTree( $tree=array(), $spacer="\t", $level=0 ) 
{
  if( !is_int( $level ) ) {
    $level = 0;
  }
  $level++;

  
  foreach( $tree as $key=>$val ) {
	if (!isset($out)) $out = "";

	$out .= "\n<option value='".$val['nav_id']."'>".str_repeat( $spacer, $level );
    $out .= $val['nav_name'];

      if( isset($val['nav_children']) ) {
      $out .= '</option>';
      $out .= listTree( $val['nav_children'], $spacer, $level+1 );
    }
    else {
      $out .= '</option>';
    }
  }

  return $out;
}
//&nbsp;
$listree = listTree( $tree, "&nbsp;&nbsp;" );
?>

<?php echo validation_errors(); ?>

<br>
<form method="post">
<table  class="table table-striped">
	<tr>
		<td class="tableHeaderLeft">Title</td>
		<td class="tableContent1Left"><input type="text" SIZE="40" name="cname" value="<?=isset($table['cname'])?$table['cname']:'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Amount</td>
		<td class="tableContent1Left"><input type="text" SIZE="40" name="camount" value="<?=isset($table['camount'])?$table['camount']:'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Parent</td>
		<td class="tableContent1Left">
			<select name="cparent" <?=(isset($table['gotchild']) && $table['gotchild'] == 1) ? 'DISABLED' : '';?>>
				<?php  //<option value="null">No Parent</option> ?>
				<?php   $hold = 'no'; ?> 
				<?php   foreach($tree_table as $v):?>
				<?php  
					if ($table['cid'] == $v['cid'] ) {
						$cur_lvl = $v['lvl'];
						$hold = 'yes';
					}
					elseif ($hold == 'yes') {
						$hold = ($cur_lvl >= $v['lvl']) ? 'no' : 'yes';
					} 
				?>
				<?php   if ($table['cid'] != $v['cid'] && $hold != 'yes') : ?>
					<option value="<?=$v['cid'];?>" <?php  if(isset($v['cid']) && isset($table['cparent']) && $v['cid'] == $table['cparent']) echo "SELECTED";?>><?=str_repeat( "&nbsp;&nbsp;", $v['lvl'] );?> <?=$v['cname'];?></option>
				<?php   endif; ?>
				<?php   endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tableHeaderLeft"></td>
		<td class="tableContent1Left">
			<?php  /*<input type="hidden" name="baseurl" id="baseurl" value="<?=$_SERVER['SCRIPT_NAME'];?>"> */ ?>
			<input type="submit" class="btn btn-small btn-primary">
		</td>
	</tr>
</table>
</form>
