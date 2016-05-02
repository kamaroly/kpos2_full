<script type="text/javascript"><!--

function gettid(nom) {
   for (var i = 0; i < document.getElementById("tfrom").length; i++) {
      if (document.getElementById("tfrom").options[i].value == nom) {
         return document.getElementById("tfrom").options[i].index
      }
   }
   return null
}

function predefined(tfrom,tto,tdesc,tamount) {
	var tfrom1 = gettid(tfrom);
	var tto1 = gettid(tto);
	document.getElementById("tfrom").selectedIndex = tfrom1; 
	document.getElementById("tto").selectedIndex = tto1; 
	document.getElementById("tdesc").value = tdesc; 
	document.getElementById("tamount").value = tamount; 
	return false;
}

//--></script>
<table  class="table table-striped">
	<tr>
		<td class="tableHeaderLeft">Predefined Transactions</td>
	</tr>
	<tr>
		<td class="tableContent1Left">
			<?php  foreach ($predefined as $val) : ?>
				<a  href="javascript:void(0)" onClick="predefined(<?=$val['ptfrom']?>,<?=$val['ptto']?>,'<?=$val['ptdesc']?>','<?=$val['ptamount']?>');">
				  <div class="btn btn-small btn-success"><?=$val['ptdesc']?>
				  </div>
				  </a><br>
			<?php  endforeach; ?>
			
			
		</td>
	</tr>
</table>


