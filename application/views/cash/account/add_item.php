<?php  
function listTree( $tree=array(), $spacer="\t", $level=0 ) 
  {
     if( !is_int( $level ) ) 
     {
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
    else 
    {
      $out .= '</option>';
    }
  }

  return $out;
}

?>



<?php echo validation_errors(); ?>

<br>
<form method="post">
<table  class="table table-striped">
	<tr>
		<td class="tableHeaderLeft">Name</td>
		<td class="tableContent1Left"><input type="text" SIZE="40" name="cname" value="<?=isset($table['cname'])?$table['cname']:'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Starting Balance</td>
		<td class="tableContent1Left"><input type="text" SIZE="40" name="camount" value="<?=isset($table['camount'])?$table['camount']:'';?>"></td>
	</tr>
	<tr>
		<td class="tableHeaderLeft">Parent</td>
		<td class="tableContent1Left">
			<select name="cparent">
				<?php   foreach($acc_type as $v):?>
					<option value="<?=$v['acc_id'];?>" <?php  if(isset($v['acc_id']) && isset($table['cparent']) && $v['acc_id'] == $table['cparent']) echo "SELECTED";?>> <?=$v['acc_name'];?></option>
				<?php   endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tableHeaderLeft"></td>
		<td class="tableContent1Left">
			<input type="hidden" name="ctype" value="a"> 
			<input type="submit" class="btn btn-small btn-primary">
		</td>
	</tr>
</table>
</form>
