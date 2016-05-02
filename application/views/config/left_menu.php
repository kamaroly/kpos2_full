
<?php  
$cur1 = $this->uri->segment(1);
$cur2 = $this->uri->segment(2);

//$menu['general'][] = array('title' => 'Home', 'item' => 'index');
//$menu['general'][] = array('title' => 'Credit', 'item' => 'credit');


	$menu['Global Settings'][] = array('title' => 'Company', 'item' => 'config');
	$menu['Global Settings'][] = array('title' => 'Sales Data Controller', 'item' => 'config/index/sdc');
	$menu['Global Settings'][] = array('title' => 'Currency', 'item' => 'config/currency');
	$menu['Global Settings'][] = array('title' => 'Items', 'item' => 'config/index/items');
	$menu['Global Settings'][] = array('title' => 'Receipt', 'item' => 'config/index/receipt');
	$menu['Global Settings'][] = array('title' => 'Finance', 'item' => 'config/index/Finance');
	$menu['Global Settings'][] = array('title' => 'Images', 'item' => 'config/images');
	
	$menu['Maintenance'][] = array('title' => 'Back up database', 'item' => 'config/backup');
	$menu['Maintenance'][] = array('title' => 'Optimize', 'item' => 'config/optimize');
	
	
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
			<td class="<?= $active ? 'sideNavActive' : 'sideNav';?>">
			<a href="<?php echo site_url($v1['item']);?>" class="
            <?php echo ($v1['item']=="config/optimize")?"thickbox":"";?>
			sideNavLink"><?php echo $v1['title'];?></a></td>
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

