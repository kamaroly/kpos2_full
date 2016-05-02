
<?php  
$cur1 = $this->uri->segment(1);
$cur2 = $this->uri->segment(2);

//$menu['general'][] = array('title' => 'Home', 'item' => 'index');
//$menu['general'][] = array('title' => 'Credit', 'item' => 'credit');


	$menu['accounts'][] = array('title' => 'Add Account', 'item' => 'additem');
	$menu['accounts'][] = array('title' => 'Summary', 'item' => 'summary');
	$menu['accounts'][] = array('title' => 'Add Account type', 'item' => 'addtype');
	$menu['accounts'][] = array('title' => 'Account tpe summary', 'item' => 'acctypes');
	$menu['budget'][] = array('title' => 'Add Budget Item', 'item' => 'additem');
	$menu['budget'][] = array('title' => 'Summary', 'item' => 'summary');
	$menu['transaction'][] = array('title' => 'Summary', 'item' => 'summary');
	$menu['transaction'][] = array('title' => 'Predefined', 'item' => 'listpredefined');
	$menu['transaction'][] = array('title' => 'Add Transaction', 'item' => 'additem');
	$menu['report'][] = array('title' => 'Income Vs Expenses', 'item' => 'income_vs_expenses');
	$menu['report'][] = array('title' => 'Income Breakdown', 'item' => 'income');
	$menu['report'][] = array('title' => 'Expenditure Breakdown', 'item' => 'expenses');
	$menu['report'][] = array('title' => 'Budget Vs Actual', 'item' => 'budgetvsactual');

/**
	$menu['auth'][] = array('title' => 'Login', 'item' => 'login');
	$menu['auth'][] = array('title' => 'Register', 'item' => 'register');
	$menu['auth'][] = array('title' => 'Forgotten Password', 'item' => 'forgotten_password');
**/
?>

<table class="sideMenu">
<tr>
	<td class="sideNavHeading">MENU</td>
</tr>
<tr>
	<td class="sideNav">&nbsp;</td>
</tr>


<?php   foreach ($menu as $k => $v) : ?>
<tr>
<td class="sideNavSubHeader"><?=ucfirst($k);?></td>
</tr>
	<?php   foreach ($v as $k1 => $v1) : ?>
		<tr>
			<?php   $active = ($cur1 == $k && $cur2 == $v1['item']) ? 1 : 0; ?>
			<td class="<?= $active ? 'sideNavActive' : 'sideNav';?>"><a href="<?php echo site_url('cash/'.$k.'/'.$v1['item']);?>" class="sideNavLink"><?=$v1['title'];?></a></td>
		</tr>
	<?php   endforeach; ?>
<tr>
	<td class="sideNav">&nbsp;</td>
</tr>

<?php   endforeach; ?>

<tr>
	<td class="sideNav">&nbsp;</td>
</tr>


</table>

